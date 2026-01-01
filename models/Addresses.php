<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "addresses".
 *
 * @property int $id
 * @property int $user_id
 * @property string $recipient_name
 * @property string $street_address
 * @property string $city
 * @property string|null $state
 * @property string $postal_code
 * @property string $country
 * @property string|null $phone_number
 * @property int|null $is_default
 * @property string|null $created_at
 *
 * @property Orders[] $orders
 * @property User $user
 */
class Addresses extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'addresses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            // [['user_id'], 'integer'],
            // [['created_at'], 'safe'],
            // [['recipient_name', 'city', 'state', 'country'], 'string', 'max' => 100],
            // [['street_address'], 'string', 'max' => 255],
            // [['postal_code', 'phone_number'], 'string', 'max' => 20],
            [['state', 'phone_number'], 'default', 'value' => null],
            [['is_default'], 'default', 'value' => 0],
            // [['user_id', 'recipient_name', 'street_address', 'city', 'postal_code', 'country'], 'required'],
            [['user_id', 'is_default'], 'integer'],
            [['created_at'], 'safe'],
            [['recipient_name', 'city', 'state', 'country'], 'string', 'max' => 100],
            [['street_address'], 'string', 'max' => 255],
            [['postal_code', 'phone_number'], 'string', 'max' => 20],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }



    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Address ID',
            'user_id' => 'User ID',
            'recipient_name' => 'Recipient Name',
            'street_address' => 'Street Address',
            'city' => 'City',
            'state' => 'State',
            'postal_code' => 'Postal Code',
            'country' => 'Country',
            'phone_number' => 'Phone Number',
            'is_default' => 'Is Default',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Orders::class, ['id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            if (empty($this->user_id)) {
                $this->user_id = Yii::$app->user->id;
            }
            return true;
        }
        return false;
    }
}
