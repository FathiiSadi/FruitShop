<?php


namespace app\models;

use Yii;

/**
 * This is the model class for table "shop".
 *
 * @property Products[] $products
//  * @property Order[] $orders
 */


class Shop extends \yii\db\ActiveRecord
{
    /**
     * ENUM field values
     */
    const STATUS_OPEN = 'open';
    const STATUS_CLOSED = 'checked_out';


    /**
     * {@inheritdoc}
     */
    // public static function tableName()
    // {
    //     return 'shop';
    // }

    /**
     * {@inheritdoc}
     */
    // public function showProducts()
    // {
    //     return $this->hasMany(Products::class, ['shop_id' => 'id']);   
    // }
    /**
     * {@inheritdoc}
     */
    // public function attributeLabels()
    // {
    //     return [
    //         'id' => 'Cart ID',
    //         'user_id' => 'User ID',
    //         'Name' => 'Shop Name',
    //         'Status' => 'Status',
    //         'updated_at' => 'Updated At',
    //     ];
    // }
}
