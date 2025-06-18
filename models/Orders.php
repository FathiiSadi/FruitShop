<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $order_id
 * @property int $UserID
 * @property int $address_id
 * @property string|null $order_date
 * @property string|null $status
 * @property float $subtotal
 * @property float $tax_amount
 * @property float $shipping_cost
 * @property float $total_amount
 * @property string|null $notes
 *
 * @property Addresses $address
 * @property OrderItems[] $orderItems
 * @property Payments[] $payments
 * @property User $user
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * ENUM field values
     */
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'default', 'value' => 'pending'],
            [['shipping_cost'], 'default', 'value' => 15.00],
            [['UserID', 'address_id', 'subtotal', 'tax_amount', 'total_amount'], 'required'],
            [['UserID', 'address_id'], 'integer'],
            [['order_date'], 'safe'],
            [['status', 'notes'], 'string'],
            [['subtotal', 'tax_amount', 'shipping_cost', 'total_amount'], 'number'],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
            [['UserID'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['UserID' => 'id']],
            // Fixed: address_id should link to address_id in Addresses table
            [['address_id'], 'exist', 'skipOnError' => true, 'targetClass' => Addresses::class, 'targetAttribute' => ['address_id' => 'address_id']],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'UserID' => 'User ID',
            'address_id' => 'Address ID',
            'order_date' => 'Order Date',
            'status' => 'Status',
            'subtotal' => 'Subtotal',
            'tax_amount' => 'Tax Amount',
            'shipping_cost' => 'Shipping Cost',
            'total_amount' => 'Total Amount',
            'notes' => 'Notes',
        ];
    }

    /**
     * Remove the beforeSave method since we're handling this in the controller
     */

    /**
     * Gets query for [[Address]].
     * Fixed: address_id should link to address_id in Addresses table
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(Addresses::class, ['address_id' => 'address_id']);
    }

    public function getUsername()
    {
        $user = User::find()
            ->select('username')
            ->where(['id' => $this->UserID])
            ->scalar();
        return $user;
    }

    public function getPayment()
    {
        return $this->hasOne(Payments::className(), ['order_id' => 'order_id']);
    }
    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItems::class, ['order_id' => 'order_id']);
    }

    /**
     * Gets query for [[Payments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayments()
    {
        return $this->hasMany(Payments::class, ['order_id' => 'order_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'UserID']);
    }

    /**
     * column status ENUM value labels
     * @return string[]
     */
    public static function optsStatus()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_SHIPPED => 'Shipped',
            self::STATUS_DELIVERED => 'Delivered',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    /**
     * @return string
     */
    public function displayStatus()
    {
        return self::optsStatus()[$this->status] ?? 'Unknown';
    }

    /**
     * @return bool
     */
    public function isStatusPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function setStatusToPending()
    {
        $this->status = self::STATUS_PENDING;
    }

    /**
     * @return bool
     */
    public function isStatusProcessing()
    {
        return $this->status === self::STATUS_PROCESSING;
    }

    public function setStatusToProcessing()
    {
        $this->status = self::STATUS_PROCESSING;
    }

    /**
     * @return bool
     */
    public function isStatusShipped()
    {
        return $this->status === self::STATUS_SHIPPED;
    }

    public function setStatusToShipped()
    {
        $this->status = self::STATUS_SHIPPED;
    }

    /**
     * @return bool
     */
    public function isStatusDelivered()
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    public function setStatusToDelivered()
    {
        $this->status = self::STATUS_DELIVERED;
    }

    /**
     * @return bool
     */
    public function isStatusCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function setStatusToCancelled()
    {
        $this->status = self::STATUS_CANCELLED;
    }
}
