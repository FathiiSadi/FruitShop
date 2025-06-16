<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "addresses".
 *
 * @property int $address_id
 * @property int $UserID
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

            // [['UserID'], 'integer'],
            // [['created_at'], 'safe'],
            // [['recipient_name', 'city', 'state', 'country'], 'string', 'max' => 100],
            // [['street_address'], 'string', 'max' => 255],
            // [['postal_code', 'phone_number'], 'string', 'max' => 20],
            [['state', 'phone_number'], 'default', 'value' => null],
            [['is_default'], 'default', 'value' => 0],
            // [['UserID', 'recipient_name', 'street_address', 'city', 'postal_code', 'country'], 'required'],
            [['UserID', 'is_default'], 'integer'],
            [['created_at'], 'safe'],
            [['recipient_name', 'city', 'state', 'country'], 'string', 'max' => 100],
            [['street_address'], 'string', 'max' => 255],
            [['postal_code', 'phone_number'], 'string', 'max' => 20],
            [['UserID'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['UserID' => 'id']],
        ];
    }



    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'address_id' => 'Address ID',
            'UserID' => 'User ID',
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
        return $this->hasMany(Orders::class, ['address_id' => 'address_id']);
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

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            if (empty($this->UserID)) {
                $this->UserID = Yii::$app->user->id;
            }
            return true;
        }
        return false;
    }
}
