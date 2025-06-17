<?php

namespace app\modules\models;

use app\models\User;
use app\models\Products;
use app\models\Cart;
use app\models\Cartitem;

class Admin
{
    public static function getUserCount()
    {
        return User::find()->count();
    }


    // public static function getSoldProductCount()
    // {
    //     return Products::find()->where(['status' => 'sold'])->count();
    // }

    // public static function getProfit()
    // {
    //     return Products::find()
    //         ->where(['status' => 'sold'])
    //         ->sum('price');
    // }

    public static function getOutOfStockProductCount()
    {
        return Products::find()->where(['stock' => 0])->count();
    }

    public static function getLowStockProductCount($quantity = 10)
    {
        return Products::find()->where(['<', 'stock', $quantity])->count();
    }

    public static function getNumberOfCarts()
    {
        return Cart::find()->count();
    }




    public static function getNumberOfProducts()
    {
        return Products::find()->count();
    }

    public static function getTotalProfit()
    {
        $totalProfit = 0;
        $cartItems = Cartitem::find()->all();
        foreach ($cartItems as $i) {
            $totalProfit += ($i->price * $i->quantity);
        }

        return $totalProfit;
    }

    public static function profitByProduct()
    {
        $profitByProduct = [];
        $cartItems = Cartitem::find()->all();
        foreach ($cartItems as $i) {
            $profitByProduct[$i->product->name] = ($profitByProduct[$i->product->name] ?? 0) + ($i->price * $i->quantity);
        }
        arsort($profitByProduct);

        return $profitByProduct;
    }


    public static function getProductByName($name)
    {
        return Products::find()->where(['name' => $name])->one();
    }



    public static function getTotalTaxs($totalProfit, $taxRate = 0.16)
    {
        return $totalProfit * $taxRate;
    }
}
