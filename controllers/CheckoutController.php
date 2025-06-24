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

use yii\helpers\Url;
use app\models\Payments;
use \Exception;

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
            return $this->redirect(['checkout/index']);
        }

        $userId = Yii::$app->user->id;
        $cart = Cart::find()->where(['UserID' => $userId, 'Status' => 'open'])->with('cartItems.product')->one();

        if (!$cart || $cart->isEmpty()) {
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
        if (Yii::$app->request->isPost) {
            $userId = Yii::$app->user->id;
            $cart = Cart::find()->where(['UserID' => $userId, 'Status' => 'open'])->with('cartItems.product')->one();

            if (!$cart || $cart->isEmpty()) {
                Yii::$app->session->setFlash('error', 'Cart is empty');
                return $this->redirect(['cart/index']);
            }

            $token = Yii::$app->request->post('token');
            if (!$token) {
                Yii::$app->session->setFlash('error', 'Payment token missing');
                return $this->redirect(['checkout/payment']);
            }

            $paymentModel = new Payments();
            $paymentModel->amount = $cart->getTotalWithTax() + 15;
            $paymentModel->payment_date = date('Y-m-d H:i:s');
            $paymentModel->order_id = Yii::$app->session->get('checkout_order_id');
            $paymentModel->payment_method = Payments::PAYMENT_METHOD_CHECKOUT_COM;
            $paymentModel->payment_status = Payments::PAYMENT_STATUS_PENDING;

            $response = $this->processPaymentWithCheckout($token, $paymentModel->amount);

            // Log the full response for debugging
            Yii::info('Full payment response: ' . json_encode($response), 'payment');

            if (isset($response['status']) && $response['status'] === 'Pending' && isset($response['_links']['redirect']['href'])) {
                $paymentModel->transaction_id = $response['id'];
                $paymentModel->payment_status = Payments::PAYMENT_STATUS_PENDING;
                if (!$paymentModel->save()) {
                    Yii::error('Failed to save payment: ' . json_encode($paymentModel->errors), 'payment');
                }

                return $this->redirect($response['_links']['redirect']['href']);
            }

            if (isset($response['approved']) && $response['approved'] === true) {
                $paymentModel->payment_status = Payments::PAYMENT_STATUS_COMPLETED;
                $paymentModel->transaction_id = $response['id'];
                $paymentModel->save();

                $cart->Status = 'completed';
                $cart->save();

                Yii::$app->session->setFlash('success', 'Payment completed successfully');
                return $this->redirect(['orders/index']);
            } else {
                $paymentModel->payment_status = Payments::PAYMENT_STATUS_FAILED;
                $paymentModel->save();

                $errorMessage = 'Payment failed';
            }

            $addressId = Yii::$app->session->get('checkout_address_id');
            $addressModel = Addresses::findOne($addressId);

            return $this->render('payment', [
                'cart' => $cart,
                'addressModel' => $addressModel,
                'paymentModel' => $paymentModel,
            ]);
        }

        return $this->redirect(['checkout/payment']);
    }

    private function processPaymentWithCheckout($token, $amount)
    {
        $url = 'https://api.sandbox.checkout.com/payments';
        $headers = [
            'Authorization: Bearer sk_sbox_l5lhlcy4u4rdaciaujh6ykg3o4t',
            'Content-Type: application/json',
        ];

        $payload = [
            'source' => [
                'type' => 'token',
                'token' => $token,
            ],
            'amount' => ($amount * 100),
            'currency' => 'GBP',
            'processing_channel_id' => 'pc_eoifzuuhhwkevgwmwcfohvy62u',
            'capture' => true,
            'reference' => 'Order-' . Yii::$app->session->get('checkout_order_id'),
            '3ds' => [
                'enabled' => true,
                'attempt_n3d' => false,
            ],
            'success_url' => Url::to(['orders/index'], true),
            'failure_url' => Url::to(['checkout/failure'], true),
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            throw new Exception('cURL error: ' . $curlError);
        }

        $decodedResponse = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON response from payment gateway');
        }

        return $decodedResponse;
    }

    public function actionSuccess()
    {
        $paymentId = Yii::$app->request->get('cko-payment-id');

        if (!$paymentId) {
            Yii::$app->session->setFlash('error', 'Payment ID missing');
            return $this->redirect(['checkout/payment']);
        }

        try {
            $paymentDetails = $this->getPaymentDetails($paymentId);

            if (!$paymentDetails) {
                throw new Exception('Could not retrieve payment details');
            }

            $paymentModel = Payments::find()->where(['transaction_id' => $paymentId])->one();
            if (!$paymentModel) {
                $paymentModel = new Payments();
                $paymentModel->transaction_id = $paymentId;
                $paymentModel->order_id = Yii::$app->session->get('checkout_order_id');
                $paymentModel->payment_method = Payments::PAYMENT_METHOD_CHECKOUT_COM;
            }

            if ($paymentDetails['approved'] === true) {
                $paymentModel->payment_status = Payments::PAYMENT_STATUS_COMPLETED;
                $paymentModel->amount = $paymentDetails['amount'] / 100;
                $paymentModel->payment_date = date('Y-m-d H:i:s');
                $paymentModel->save();

                $userId = Yii::$app->user->id;
                $cart = Cart::find()->where(['UserID' => $userId, 'Status' => 'open'])->one();
                if ($cart) {
                    $cart->Status = 'completed';
                    $cart->save();
                }

                Yii::$app->session->setFlash('success', 'Payment completed successfully');
                return $this->redirect(['orders/index']);
            } else {
                $paymentModel->payment_status = Payments::PAYMENT_STATUS_FAILED;
                $paymentModel->save();

                $errorMessage = 'Payment failed after 3DS authentication';
                if (isset($paymentDetails['response_summary'])) {
                    $errorMessage .= ': ' . $paymentDetails['response_summary'];
                }
                Yii::$app->session->setFlash('error', $errorMessage);
                return $this->redirect(['checkout/payment']);
            }
        } catch (Exception $e) {
            Yii::error('3DS success handler error: ' . $e->getMessage(), 'payment');
            Yii::$app->session->setFlash('error', 'Payment verification failed: ' . $e->getMessage());
            return $this->redirect(['checkout/payment']);
        }
    }

    public function actionFailure()
    {
        $paymentId = Yii::$app->request->get('cko-payment-id');

        if ($paymentId) {
            $paymentModel = Payments::find()->where(['transaction_id' => $paymentId])->one();
            if (!$paymentModel) {
                $paymentModel = new Payments();
                $paymentModel->transaction_id = $paymentId;
                $paymentModel->order_id = Yii::$app->session->get('checkout_order_id');
                $paymentModel->payment_method = Payments::PAYMENT_METHOD_CHECKOUT_COM;
            }

            $paymentModel->payment_status = Payments::PAYMENT_STATUS_FAILED;
            $paymentModel->save();
        }

        return $this->redirect(['site/index']);
    }

    private function getPaymentDetails($paymentId)
    {
        $url = 'https://api.sandbox.checkout.com/payments/' . $paymentId;
        $headers = [
            'Authorization: Bearer sk_sbox_l5lhlcy4u4rdaciaujh6ykg3o4t',
            'Content-Type: application/json',
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);


        $decodedResponse = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON response from payment gateway');
        }

        return $decodedResponse;
    }
}
