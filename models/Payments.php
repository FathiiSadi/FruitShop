<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payments".
 *
 * @property int $payment_id
 * @property int $order_id
 * @property string $payment_method
 * @property float $amount
 * @property string|null $payment_status
 * @property string|null $payment_date
 * @property string|null $cardholder_name
 * @property string|null $last_four_digits
 * @property string|null $expiry_month
 * @property string|null $expiry_year
 *
 * @property Orders $order
 */
class Payments extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const PAYMENT_METHOD_CASH_ON_DELIVERY = 'cash_on_delivery';
    const PAYMENT_METHOD_VISA = 'visa';
    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_COMPLETED = 'completed';
    const PAYMENT_STATUS_FAILED = 'failed';
    const PAYMENT_STATUS_REFUNDED = 'refunded';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payments';
    }

    /**
     * {@inheritdoc}
     */
    public function actionSaveAddress()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $userId = Yii::$app->user->id;
        $cart = Cart::find()->where(['UserID' => $userId, 'Status' => 'open'])->one();

        if (!$cart || $cart->isEmpty()) {
            Yii::$app->session->setFlash('error', 'Your cart is empty.');
            return $this->redirect(['cart/index']);
        }

        $addressModel = new Addresses();
        $addressModel->UserID = $userId;
        $addressModel->is_default = 0;

        if ($addressModel->load(Yii::$app->request->post())) {
            if ($addressModel->save()) {
                // Create the order
                $order = new Orders();
                $order->UserID = $userId;
                $order->order_date = date('Y-m-d H:i:s');
                $order->status = 'pending';
                $order->total_amount = $cart->getTotalWithTax() + 15; // including shipping
                $order->address_id = $addressModel->address_id;

                if ($order->save()) {
                    // Save cart items to order items
                    foreach ($cart->cartItems as $item) {
                        $orderItem = new OrderItems();
                        $orderItem->order_id = $order->order_id;
                        $orderItem->product_id = $item->product_id;
                        $orderItem->quantity = $item->quantity;
                        $orderItem->price = $item->price;
                        $orderItem->save();
                    }

                    return $this->redirect(['checkout/payment', 'order_id' => $order->order_id]);
                } else {
                    Yii::error('Order save failed: ' . print_r($order->errors, true));
                    Yii::$app->session->setFlash('error', 'Error creating order: ' . implode(', ', $order->getFirstErrors()));
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
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'payment_id' => 'Payment ID',
            'order_id' => 'Order ID',
            'payment_method' => 'Payment Method',
            'amount' => 'Amount',
            'payment_status' => 'Payment Status',
            'payment_date' => 'Payment Date',
            'cardholder_name' => 'Cardholder Name',
            'last_four_digits' => 'Last Four Digits',
            'expiry_month' => 'Expiry Month',
            'expiry_year' => 'Expiry Year',
        ];
    }


    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Orders::class, ['order_id' => 'order_id']);
    }



    /**
     * column payment_method ENUM value labels
     * @return string[]
     */
    public static function optsPaymentMethod()
    {
        return [
            self::PAYMENT_METHOD_CASH_ON_DELIVERY => 'cash_on_delivery',
            self::PAYMENT_METHOD_VISA => 'visa',
        ];
    }

    /**
     * column payment_status ENUM value labels
     * @return string[]
     */
    public static function optsPaymentStatus()
    {
        return [
            self::PAYMENT_STATUS_PENDING => 'pending',
            self::PAYMENT_STATUS_COMPLETED => 'completed',
            self::PAYMENT_STATUS_FAILED => 'failed',
            self::PAYMENT_STATUS_REFUNDED => 'refunded',
        ];
    }

    /**
     * @return string
     */
    public function displayPaymentMethod()
    {
        return self::optsPaymentMethod()[$this->payment_method];
    }

    /**
     * @return bool
     */
    public function isPaymentMethodCashondelivery()
    {
        return $this->payment_method === self::PAYMENT_METHOD_CASH_ON_DELIVERY;
    }

    public function setPaymentMethodToCashondelivery()
    {
        $this->payment_method = self::PAYMENT_METHOD_CASH_ON_DELIVERY;
    }

    /**
     * @return bool
     */
    public function isPaymentMethodVisa()
    {
        return $this->payment_method === self::PAYMENT_METHOD_VISA;
    }

    public function setPaymentMethodToVisa()
    {
        $this->payment_method = self::PAYMENT_METHOD_VISA;
    }

    /**
     * @return string
     */
    public function displayPaymentStatus()
    {
        return self::optsPaymentStatus()[$this->payment_status];
    }

    /**
     * @return bool
     */
    public function isPaymentStatusPending()
    {
        return $this->payment_status === self::PAYMENT_STATUS_PENDING;
    }

    public function setPaymentStatusToPending()
    {
        $this->payment_status = self::PAYMENT_STATUS_PENDING;
    }

    /**
     * @return bool
     */
    public function isPaymentStatusCompleted()
    {
        return $this->payment_status === self::PAYMENT_STATUS_COMPLETED;
    }

    public function setPaymentStatusToCompleted()
    {
        $this->payment_status = self::PAYMENT_STATUS_COMPLETED;
    }

    /**
     * @return bool
     */
    public function isPaymentStatusFailed()
    {
        return $this->payment_status === self::PAYMENT_STATUS_FAILED;
    }

    public function setPaymentStatusToFailed()
    {
        $this->payment_status = self::PAYMENT_STATUS_FAILED;
    }

    /**
     * @return bool
     */
    public function isPaymentStatusRefunded()
    {
        return $this->payment_status === self::PAYMENT_STATUS_REFUNDED;
    }

    public function setPaymentStatusToRefunded()
    {
        $this->payment_status = self::PAYMENT_STATUS_REFUNDED;
    }

    /**
     * Get payment method label
     * @return string
     */
    public function getPaymentMethodLabel()
    {
        $options = self::optsPaymentMethod();
        return isset($options[$this->payment_method]) ? $options[$this->payment_method] : $this->payment_method;
    }

    /**
     * Get payment status label
     * @return string
     */
    public function getPaymentStatusLabel()
    {
        $options = self::optsPaymentStatus();
        return isset($options[$this->payment_status]) ? $options[$this->payment_status] : $this->payment_status;
    }
}
