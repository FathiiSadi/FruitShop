<?php

namespace app\modules\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string|null $password
 * @property string $authKey
 * @property string $accessToken
 * @property string|null $role
 *
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password'], 'default', 'value' => 123456],
            [['authKey'], 'default', 'value' => Yii::$app->security->generateRandomString()],
            [['accessToken'], 'default', 'value' => Yii::$app->security->generateRandomString()],
            [['role'], 'default', 'value' => 'user'],
            [['username', 'authKey', 'accessToken'], 'required'],
            [['username'], 'string', 'max' => 50],
            [['password', 'role'], 'string', 'max' => 255],
            [['authKey', 'accessToken'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'authKey' => 'Auth Key',
            'accessToken' => 'Access Token',
            'role' => 'Role',
        ];
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return array
     */
    public static function getAdmin()
    {
        return User::find()->where(['role' => 'admin'])->all();
    }
}
