<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "CartItem".
 *
 * @property int $CartItemID
 * @property int $CartID
 * @property int $ProductID
 * @property int $quantity
 * @property float $price
 * @property string|null $AddedAt
 *
 * @property Cart $cart
 * @property Products $product
 */
class CartItem extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'CartItem';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quantity'], 'default', 'value' => 1],
            [['CartID', 'ProductID', 'price'], 'required'],
            [['CartID', 'ProductID', 'quantity'], 'integer'],
            [['price'], 'number'],
            [['AddedAt'], 'safe'],
            [['CartID'], 'exist', 'skipOnError' => true, 'targetClass' => Cart::class, 'targetAttribute' => ['CartID' => 'CartID']],
            [['ProductID'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['ProductID' => 'ProductID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'CartItemID' => 'Cart Item ID',
            'CartID' => 'Cart ID',
            'ProductID' => 'Product ID',
            'quantity' => 'quantity',
            'price' => 'price',
            'AddedAt' => 'Added At',
        ];
    }

    /**
     * Gets query for [[Cart]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCart()
    {
        return $this->hasOne(Cart::class, ['CartID' => 'CartID']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::class, ['ProductID' => 'ProductID']);
    }
}
