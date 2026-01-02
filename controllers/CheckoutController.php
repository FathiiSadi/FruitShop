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
use app\services\PaymentProcessor;
use app\services\CheckoutManager;
use app\models\Payments;
use Exception;


class CheckoutController extends Controller
{


    /** @var PaymentProcessor */
    protected $paymentProcessor;

    /** @var CheckoutManager */
    protected $checkoutManager;

    public function __construct(
        $id,
        $module,
        PaymentProcessor $paymentProcessor,
        CheckoutManager $checkoutManager,
        $config = []
    ) {
        $this->paymentProcessor = $paymentProcessor;
        $this->checkoutManager = $checkoutManager;
        parent::__construct($id, $module, $config);
    }
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
        $cart = Cart::find()->where(['user_id' => $userId, 'status' => 'open'])->with('cartItems.product')->one();

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
        $addressModel->user_id = Yii::$app->user->id;
        $addressModel->is_default = 0;

        if ($addressModel->load(Yii::$app->request->post())) {
            if ($addressModel->save()) {
                Yii::$app->session->set('checkout_id', $addressModel->id);

                $userId = Yii::$app->user->id;
                $cart = Cart::find()->where(['user_id' => $userId, 'status' => 'open'])->with('cartItems.product')->one();

                if (!$cart || $cart->isEmpty()) {
                    Yii::$app->session->setFlash('error', 'Your cart is empty.');
                    return $this->redirect(['cart/index']);
                }

                $order = new Orders();
                $order->user_id = $userId;
                $order->id = $addressModel->id;
                $order->order_date = date('Y-m-d H:i:s');
                $order->status = 'pending';
                $order->subtotal = $cart->getSubtotal();
                $order->tax_amount = $cart->getTaxAmount();
                $order->shipping_cost = 15;
                $order->total_amount = $cart->getTotalWithTax() + 15;

                if ($order->save()) {
                    Yii::$app->session->set('checkout_id', $order->id);
                    return $this->redirect(['checkout/payment']);
                } else {
                    Yii::error('Order save failed: ');
                    return $this->redirect(['checkout/index']);
                }
            } else {
                Yii::error('Address save failed: ');
            }
        }

        return $this->redirect(['checkout/index']);
    }
    public function actionPayment()
    {
        if (!$this->checkoutManager->validateCheckout()) {
            return $this->redirect(['site/login']);
        }

        $checkoutData = $this->checkoutManager->getCheckoutData();
        $paymentModel = Yii::$app->payment->createPaymentModel($checkoutData['cart']);

        return $this->render('payment', [
            'cart' => $checkoutData['cart'],
            'addressModel' => $checkoutData['address'],
            'paymentModel' => $paymentModel,
        ]);
    }

    public function actionProcessPayment()
    {
        if (!Yii::$app->request->isPost) {
            return $this->redirect(['checkout/payment']);
        }

        $checkoutData = $this->checkoutManager->getCheckoutData();
        if (!$checkoutData['cart']) {
            Yii::$app->session->setFlash('error', 'Cart is empty');
            return $this->redirect(['cart/index']);
        }

        $token = Yii::$app->request->post('token');



        $paymentModel = Yii::$app->payment->createPaymentModel($checkoutData['cart']);


        $paymentModel->payment_status = Payments::PAYMENT_STATUS_PENDING;

        $response = Yii::$app->payment->processPayment($token, $paymentModel->amount, $paymentModel->id);
        //        $response = $this->paymentProcessor->processPayment(
        //            $token,
        //            $paymentModel->amount,
        //            $paymentModel->id
        //        );

        Yii::info('Payment response: ' . json_encode($response), 'payment');

        if (isset($response['status']) && $response['status'] === 'Pending' && isset($response['_links']['redirect']['href'])) {
            return $this->redirect($response['_links']['redirect']['href']);
        }

        if (isset($response['approved']) && $response['approved'] === true) {
            $paymentModel->payment_status = Payments::PAYMENT_STATUS_COMPLETED;

            return $this->redirect(['orders/index']);
        }

        $paymentModel->payment_status = Payments::PAYMENT_STATUS_FAILED;
        $paymentModel->save();
        $errorMessage = 'Payment failed';


        return $this->render('payment', [
            'cart' => $checkoutData['cart'],
            'addressModel' => $checkoutData['address'],
            'paymentModel' => $paymentModel,
        ]);
    }

    public function actionSuccess()
    {
        $paymentId = Yii::$app->request->get('cko-payment-id');
        $paymentDetails = Yii::$app->payment->getPaymentDetails($paymentId);


        $paymentModel = Payments::findOne(['transaction_id' => $paymentId]);
        if ($paymentDetails['approved'] === true) {
            $paymentModel->payment_status = Payments::PAYMENT_STATUS_COMPLETED;
            $paymentModel->payment_date = date('Y-m-d H:i:s');

            $cart = $this->checkoutManager->getUserCart();
            if ($cart) {
                Yii::$app->session->setFlash('success', 'Payment completed successfully');
                return $this->redirect(['orders/index']);
            }
        }


        return $this->redirect(['checkout/payment']);
    }

    public function actionFailure()
    {
        return $this->redirect('payment');
    }
}
