<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;  // Add this use statement

/**
 * @property int $id
 * @property string $username
 * @property string $password_hash
 * @property string $auth_key
 * @property string $access_token
 */
class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'users';
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public static function findByUsername(string $username): ?self
    {
        return static::findOne(['username' => $username]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->auth_key === $authKey;
    }

    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }


    public function setPassword(string $password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generateAccessToken()
    {
        $this->access_token = Yii::$app->security->generateRandomString();
    }
}
