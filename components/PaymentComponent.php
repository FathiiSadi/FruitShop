<?php

namespace app\components;

use yii\base\Component;

use Yii;
use yii\base\BaseObject;
use app\models\Payments;
use app\models\Cart;
use yii\helpers\Url;
use Exception;
// use GuzzleHttp\Client;
use yii\httpclient\Client;

class PaymentComponent extends Component
{
    public $apiUrl;
    public $privateKey;
    public $processingId;

    public $publicKey;


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
            'processing_channel_id' => $this->processingId,
            'capture' => true,
            'reference' => 'Order-' . $orderId,
            '3ds' => [
                'enabled' => true,
                'attempt_n3d' => false,
            ],
            'success_url' => Url::to(['orders/index'], true),
            'failure_url' => Url::to(['checkout/failure'], true),
        ];

        return $this->makeApiRequest($this->apiUrl, $payload);
    }

    public function getPaymentDetails(string $paymentId): array
    {
        $url = $this->apiUrl . '/' . $paymentId;
        return $this->makeApiRequest($url, null);
    }

    // public function completeOrder(Cart $cart, Payments $payment): bool
    // {
    //     $transaction = Yii::$app->db->beginTransaction();

    //     $cart->Status = 'checked_out';
    //     if (!$cart->save()) {
    //         throw new Exception('Failed to save cart: ' . json_encode($cart->errors));
    //     }

    //     if (!$payment->save()) {
    //         throw new Exception('Failed to save payment: ' . json_encode($payment->errors));
    //     }

    //     $transaction->commit();
    //     return true;
    // }


    private function makeApiRequest(string $url, ?array $payload): array
    {
        $client = new Client();

        $request = $client->createRequest()
            ->setUrl($url)
            ->setMethod('POST')
            ->addHeaders([
                'Authorization' => $this->privateKey,
                'Content-Type' => 'application/json',
            ])
            ->setFormat(Client::FORMAT_JSON);

        if ($payload !== null) $request->setData($payload);

        $response = $request->send();

        if (!$response->isOk) throw new Exception('API request failed: ' . $response->content);


        return $response->data;



        // $client = new Client([
        //     'base_uri' => $this->apiUrl,
        //     'timeout' => 30,
        //     'verify' => true,
        // ]);

        // $headers = [
        //     'Authorization' => $this->privateKey,
        //     'Content-Type' => 'application/json',
        // ];


        //     $options = [
        //         'headers' => $headers,
        //     ];

        //     if ($payload !== null) {
        //         $options['json'] = $payload;
        //     }

        //     $response = $client->request('POST', $url, $options);
        //     $statusCode = $response->getStatusCode();

        //     if ($statusCode >= 400) {
        //         throw new Exception("HTTP request failed with status code: $statusCode");
        //     }

        //     $body = $response->getBody()->getContents();
        //     $decodedResponse = json_decode($body, true);

        //     if (json_last_error() !== JSON_ERROR_NONE) {
        //         throw new Exception('Invalid JSON response from payment gateway');
        //     }

        //     return $decodedResponse;

    }
}
