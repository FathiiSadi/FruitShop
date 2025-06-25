<?php

namespace app\services;

use Yii;
use yii\base\BaseObject;
use app\models\Payments;
use app\models\Cart;
use app\components\PaymentComponent;
use yii\helpers\Url;
use Exception;

class PaymentProcessor extends BaseObject
{
    /** @var PaymentComponent */
    public $paymentComponent;

    public function __construct(PaymentComponent $paymentComponent, $config = [])
    {
        $this->paymentComponent = $paymentComponent;
        parent::__construct($config);
    }

    public function createPaymentModel(Cart $cart): Payments
    {
        $paymentModel = new Payments();
        $paymentModel->amount = $cart->getTotalWithTax() + 15;
        $paymentModel->payment_date = date('Y-m-d H:i:s');
        $paymentModel->order_id = Yii::$app->session->get('checkout_order_id');
        $paymentModel->payment_method = Payments::PAYMENT_METHOD_CHECKOUT_COM;

        return $paymentModel;
    }

    public function processPayment(string $token, float $amount, int $orderId): array
    {
        $payload = [
            'source' => [
                'type' => 'token',
                'token' => $token,
            ],
            'amount' => ($amount * 100),
            'currency' => 'GBP',
            'processing_channel_id' => env('PROCESSING_ID'),
            'capture' => true,
            'reference' => 'Order-' . $orderId,
            '3ds' => [
                'enabled' => true,
                'attempt_n3d' => false,
            ],
            'success_url' => Url::to(['orders/index'], true),
            'failure_url' => Url::to(['checkout/failure'], true),
        ];

        return $this->makeApiRequest(Yii::$app->get('payment')->apiUrl, $payload);
    }

    public function getPaymentDetails(string $paymentId): array
    {
        $url = env('API_URL') . '/' . $paymentId;
        return $this->makeApiRequest($url, null, false);
    }

    public function completeOrder(Cart $cart, Payments $payment): bool
    {
        $transaction = Yii::$app->db->beginTransaction();

        $cart->Status = 'checked_out';
        if (!$cart->save()) {
            throw new Exception('Failed to save cart: ' . json_encode($cart->errors));
        }


        $transaction->commit();
        return true;
    }

    private function makeApiRequest(string $url, ?array $payload, bool $isPost = true): array
    {
        $headers = [
            'Authorization: ' . env('PRIVATE_KEY'),
            'Content-Type: application/json',
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($isPost) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        }

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
}
