<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "CartItem".
 *
 * @property int $id
 * @property int $id
 * @property int $id
 * @property int $quantity
 * @property float $price
 * @property string|null $added_at
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
        return 'cart_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quantity'], 'default', 'value' => 1],
            [['id', 'id', 'price'], 'required'],
            [['id', 'id', 'quantity'], 'integer'],
            [['price'], 'number'],
            [['added_at'], 'safe'],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => Cart::class, 'targetAttribute' => ['id' => 'id']],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Cart Item ID',
            'id' => 'Cart ID',
            'id' => 'Product ID',
            'quantity' => 'quantity',
            'price' => 'price',
            'added_at' => 'Added At',
        ];
    }

    /**
     * Gets query for [[Cart]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCart()
    {
        return $this->hasOne(Cart::class, ['id' => 'id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::class, ['id' => 'id']);
    }
}
