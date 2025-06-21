<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Cart;
use app\models\Addresses;
use app\models\Orders;
use app\models\Payments;

class CheckoutController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'save-address', 'payment', 'process-payment'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'save-address' => ['POST'],
                    'process-payment' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $userId = Yii::$app->user->id;
        $cart = Cart::find()->where(['UserID' => $userId, 'Status' => 'open'])->with('cartItems.product')->one();

        if (!$cart || $cart->isEmpty()) {
            Yii::$app->session->setFlash('error', 'Your cart is empty.');
            return $this->redirect(['cart/index']);
        }

        $addressModel = new Addresses();

        return $this->render('index', [
            'cart' => $cart,
            'addressModel' => $addressModel,
        ]);
    }

    public function actionSaveAddress()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $addressModel = new Addresses();
        $addressModel->UserID = Yii::$app->user->id;
        $addressModel->is_default = 0;

        if ($addressModel->load(Yii::$app->request->post())) {
            if ($addressModel->save()) {
                Yii::$app->session->set('checkout_address_id', $addressModel->address_id);

                $userId = Yii::$app->user->id;
                $cart = Cart::find()->where(['UserID' => $userId, 'Status' => 'open'])->with('cartItems.product')->one();

                if (!$cart || $cart->isEmpty()) {
                    Yii::$app->session->setFlash('error', 'Your cart is empty.');
                    return $this->redirect(['cart/index']);
                }

                $order = new Orders();
                $order->UserID = $userId;
                $order->address_id = $addressModel->address_id;
                $order->order_date = date('Y-m-d H:i:s');
                $order->status = 'pending';
                $order->subtotal = $cart->getSubtotal();
                $order->tax_amount = $cart->getTaxAmount();
                $order->shipping_cost = 15;
                $order->total_amount = $cart->getTotalWithTax() + 15;

                if ($order->save()) {
                    Yii::$app->session->set('checkout_order_id', $order->order_id);
                    return $this->redirect(['checkout/payment']);
                } else {
                    Yii::error('Order save failed: ' . print_r($order->errors, true));
                    Yii::$app->session->setFlash('error', 'Error creating order: ' . implode(', ', $order->getFirstErrors()));
                    return $this->redirect(['checkout/index']);
                }
            } else {
                Yii::error('Address save failed: ' . print_r($addressModel->errors, true));
                Yii::$app->session->setFlash('error', 'Error saving address: ' . implode(', ', $addressModel->getFirstErrors()));
            }
        } else {
            Yii::$app->session->setFlash('error', 'No data received.');
        }

        return $this->redirect(['checkout/index']);
    }


    public function actionPayment()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $addressId = Yii::$app->session->get('checkout_address_id');
        if (!$addressId) {
            Yii::$app->session->setFlash('error', 'Please complete the address step first.');
            return $this->redirect(['checkout/index']);
        }

        $userId = Yii::$app->user->id;
        $cart = Cart::find()->where(['UserID' => $userId, 'Status' => 'open'])->with('cartItems.product')->one();

        if (!$cart || $cart->isEmpty()) {
            Yii::$app->session->setFlash('error', 'Your cart is empty.');
            return $this->redirect(['cart/index']);
        }

        $addressModel = Addresses::findOne($addressId);
        $paymentModel = new Payments();
        $paymentModel->amount = $cart->getTotalWithTax() + 15;
        $paymentModel->payment_date = date('Y-m-d H:i:s');
        $paymentModel->order_id = Yii::$app->session->get('checkout_order_id');

        return $this->render('payment', [
            'cart' => $cart,
            'addressModel' => $addressModel,
            'paymentModel' => $paymentModel,
        ]);
    }

    public function actionProcessPayment()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $addressId = Yii::$app->session->get('checkout_address_id');
        if (!$addressId) {
            Yii::$app->session->setFlash('error', 'Session expired. Please start checkout again.');
            return $this->redirect(['checkout/index']);
        }

        $userId = Yii::$app->user->id;
        $cart = Cart::find()->where(['UserID' => $userId, 'Status' => 'open'])->with('cartItems.product')->one();

        if (!$cart || $cart->isEmpty()) {
            Yii::$app->session->setFlash('error', 'Your cart is empty.');
            return $this->redirect(['cart/index']);
        }

        $paymentModel = new Payments();
        $paymentModel->amount = $cart->getTotalWithTax() + 15;
        $paymentModel->payment_date = date('Y-m-d H:i:s');
        $paymentModel->order_id = Yii::$app->session->get('checkout_order_id');

        if ($paymentModel->load(Yii::$app->request->post())) {
            $paymentData = [
                'payment_method' => $paymentModel->payment_method,
                'cardholder_name' => $paymentModel->cardholder_name,
                'expiry_month' => $paymentModel->expiry_month,
                'expiry_year' => $paymentModel->expiry_year,
                'amount' => $paymentModel->amount,
                'last_four_digits' => $paymentModel->last_four_digits
            ];

            if ($paymentModel->validate()) {
                if ($paymentModel->save()) {
                    Yii::$app->session->set('checkout_payment_data', $paymentData);
                    return $this->redirect(['orders/index']);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Payment validation failed: ' . implode(', ', $paymentModel->getFirstErrors()));
            }
        }
        $addressModel = Addresses::findOne($addressId);
        return $this->render('payment', [
            'cart' => $cart,
            'addressModel' => $addressModel,
            'paymentModel' => $paymentModel,
        ]);
    }
    
}
