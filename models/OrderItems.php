<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_items".
 *
 * @property int $order_item_id
 * @property int $order_id
 * @property int $product_id
 * @property int $quantity
 * @property float $unit_price
 * @property float $total_price
 *
 * @property Orders $order
 * @property Products $product
 * @property CartItem $cartItem
 */
class OrderItems extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'quantity', 'unit_price', 'total_price'], 'required'],
            [['order_id', 'product_id', 'quantity'], 'integer'],
            [['unit_price', 'total_price'], 'number'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Orders::class, 'targetAttribute' => ['order_id' => 'order_id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'ProductID']],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->total_price = $this->unit_price * $this->quantity;
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_item_id' => 'Order Item ID',
            'order_id' => 'Order ID',
            'product_id' => 'Product ID',
            'quantity' => 'Quantity',
            'unit_price' => 'Unit Price',
            'total_price' => 'Total Price',
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
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::class, ['ProductID' => 'product_id']);
    }
}
