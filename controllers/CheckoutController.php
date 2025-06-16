<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Cart;
use app\models\Addresses;
use app\models\Orders;
use app\models\Payments;
use app\models\OrderItems;

class CheckoutController extends Controller
{
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $userId = Yii::$app->user->id;
        $cart = Cart::find()->where(['UserID' => $userId, 'Status' => 'open'])->with('cartItems.product')->one();

        if (!$cart || $cart->isEmpty()) {
            Yii::$app->session->setFlash('error', 'Your cart is empty.');
            return $this->redirect(['cart/index']);
        }

        $addressModel = new Addresses();

        return $this->render('index', [
            'cart' => $cart,
            'addressModel' => $addressModel,
        ]);
    }

    public function actionSaveAddress()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $addressModel = new Addresses();
        $addressModel->UserID = Yii::$app->user->id;
        $addressModel->is_default = 0;

        if ($addressModel->load(Yii::$app->request->post())) {
            if ($addressModel->save()) {
                Yii::$app->session->setFlash('success', 'Address saved successfully!');

                // After saving address, redirect to orders/index to create the order
                return $this->redirect(['orders/index']);
            } else {
                Yii::error('Address save failed: ' . print_r($addressModel->errors, true));
                Yii::$app->session->setFlash('error', 'Error saving address: ' . implode(', ', $addressModel->getFirstErrors()));
            }
        } else {
            Yii::$app->session->setFlash('error', 'No data received.');
        }

        // If there's an error, redirect back to checkout with the address model
        return $this->redirect(['checkout/index']);
    }

    public function actionSuccess($id)
    {
        $order = Orders::findOne($id);

        return $this->render('success', [
            'order' => $order
        ]);
    }


    public function actionOrderSuccess()
    {
        return $this->render('order-success');
    }
}
