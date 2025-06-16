<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_repeat;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username, email, password are required
            [['username', 'email', 'password', 'password_repeat'], 'required'],

            // username should be unique and contain only letters and numbers
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9]+$/', 'message' => 'Username can only contain letters and numbers.'],
            ['username', 'string', 'min' => 3, 'max' => 255],

            // email should be a valid email format and unique
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This email address has already been taken.'],

            // password validation
            ['password', 'string', 'min' => 6],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => "Passwords don't match"],

        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the user was successfully created
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        return $user->save();
    }
}
