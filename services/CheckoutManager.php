<?php

namespace app\services;

use Yii;
use yii\base\BaseObject;
use app\models\Cart;
use app\models\Addresses;

class CheckoutManager extends BaseObject
{


    public function validateCheckout(): bool
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        $addressId = Yii::$app->session->get('checkout_id');
        if (!$addressId) {
            return false;
        }

        return $this->getUserCart() !== null;
    }

    public function getUserCart(): ?Cart
    {
        $userId = Yii::$app->user->id;
        $cart = Cart::find()
            ->where(['user_id' => $userId, 'status' => 'open'])
            ->with('cartItems.product')
            ->one();

        return ($cart && !$cart->isEmpty()) ? $cart : null;
    }

    public function getCheckoutData(): array
    {
        $cart = $this->getUserCart();
        $addressId = Yii::$app->session->get('checkout_id');

        return [
            'cart' => $cart,
            'address' => Addresses::findOne($addressId),
        ];
    }
}
