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
                // Store address ID in session for the payment step
                Yii::$app->session->set('checkout_address_id', $addressModel->address_id);

                // Redirect to payment page instead of orders
                return $this->redirect(['checkout/payment']);
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

        // Check if address was saved
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

        // Set the total amount
        $paymentModel->amount = $cart->getTotalWithTax() + 15; // Including shipping

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

        if ($paymentModel->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // Create the order first
                $orderModel = new Orders();
                $orderModel->UserID = $userId;
                $orderModel->address_id = $addressId;
                $orderModel->total_amount = $paymentModel->amount;
                $orderModel->tax_amount = $cart->getTaxAmount();
                $orderModel->shipping_cost = 15;
                $orderModel->status = 'pending';
                $orderModel->order_date = date('Y-m-d H:i:s');

                if (!$orderModel->save()) {
                    throw new \Exception('Failed to create order: ' . implode(', ', $orderModel->getFirstErrors()));
                }

                // Set order_id for payment
                $paymentModel->order_id = $orderModel->order_id;

                // Process card details for Visa payments
                if ($paymentModel->payment_method === 'visa') {
                    $cardNumber = Yii::$app->request->post('card_number', '');
                    if (strlen($cardNumber) >= 4) {
                        $paymentModel->last_four_digits = substr($cardNumber, -4);
                    }
                }

                if ($paymentModel->save()) {
                    // Update cart status to closed
                    $cart->Status = 'closed';
                    $cart->save();

                    // Clear session
                    Yii::$app->session->remove('checkout_address_id');

                    $transaction->commit();

                    Yii::$app->session->setFlash('success', 'Order placed successfully!');
                    return $this->redirect(['orders/view', 'id' => $orderModel->order_id]);
                } else {
                    throw new \Exception('Failed to process payment: ' . implode(', ', $paymentModel->getFirstErrors()));
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        // If we get here, there was an error
        $addressModel = Addresses::findOne($addressId);
        return $this->render('payment', [
            'cart' => $cart,
            'addressModel' => $addressModel,
            'paymentModel' => $paymentModel,
        ]);
    }
}
