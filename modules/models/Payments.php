<?php

namespace app\modules\models;

use Yii;

/**
 * This is the model class for table "Payments".
 *
 * @property int $payment_id
 * @property int $id
 * @property string $payment_method
 * @property float $amount
 * @property string|null $payment_status
 * @property string|null $payment_date
 * @property string|null $cardholder_name
 * @property string|null $last_four_digits
 * @property string|null $expiry_month
 * @property string|null $expiry_year
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
        return 'Payments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cardholder_name', 'last_four_digits', 'expiry_month', 'expiry_year'], 'default', 'value' => null],
            [['payment_status'], 'default', 'value' => 'pending'],
            [['id', 'payment_method', 'amount'], 'required'],
            [['id'], 'integer'],
            [['payment_method', 'payment_status'], 'string'],
            [['amount'], 'number'],
            [['payment_date'], 'safe'],
            [['cardholder_name'], 'string', 'max' => 100],
            [['last_four_digits', 'expiry_year'], 'string', 'max' => 4],
            [['expiry_month'], 'string', 'max' => 2],
            ['payment_method', 'in', 'range' => array_keys(self::optsPaymentMethod())],
            ['payment_status', 'in', 'range' => array_keys(self::optsPaymentStatus())],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::class, 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'payment_id' => 'Payment ID',
            'id' => 'Order ID',
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
}
