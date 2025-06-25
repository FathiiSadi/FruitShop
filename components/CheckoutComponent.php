<?php

namespace app\components;

use Yii;
use yii\base\Component;
use app\models\Cart;
use app\models\Addresses;

class CheckoutComponent extends Component
{
    public function validateCheckout(): bool
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        $addressId = Yii::$app->session->get('checkout_address_id');
        if (!$addressId) {
            return false;
        }

        $userId = Yii::$app->user->id;
        $cart = Cart::find()
            ->where(['UserID' => $userId, 'Status' => 'open'])
            ->with('cartItems.product')
            ->one();

        if (!$cart || $cart->isEmpty()) {
            return false;
        }

        return true;
    }

    public function getCheckoutData(): array
    {
        $userId = Yii::$app->user->id;
        $addressId = Yii::$app->session->get('checkout_address_id');

        $cart = Cart::find()
            ->where(['UserID' => $userId, 'Status' => 'open'])
            ->with('cartItems.product')
            ->one();

        $address = Addresses::findOne($addressId);

        return [
            'cart' => $cart,
            'address' => $address,
        ];
    }
}
