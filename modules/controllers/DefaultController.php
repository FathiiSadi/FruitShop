<?php

namespace app\modules\controllers;

use Yii;
use yii\web\Controller;
use app\modules\models\Admin;
use app\modules\models\User;
use app\modules\models\Products;
use app\modules\models\Orders;

/**
 * Default controller for the `modules` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */


    /**
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionIndex()
    {
        $products = Products::find()->indexBy('name')->all();
        $cities = Orders::getOrdersByCity();
        $orders = Orders::getOrdersByTotal();
        $data = [
            'userCount' => Admin::getUserCount(),
            // 'soldProductCount' => Admin::getSoldProductCount(),
            // 'profit' => Admin::getProfit(),
            'outOfStockCount' => Admin::getOutOfStockProductCount(),
            'lowStockCount' => Admin::getLowStockProductCount(10),
            'numberOfCarts' => Admin::getNumberOfCarts(),
            // 'profitByproduct'  => Admin::profitByproduct(),
            'totalProfit' => Admin::getTotalProfit(),
            'profitByProduct' => Admin::profitByProduct(),
            'products' => $products,
            'OrdersThisMonth' => Orders::getOrdersThisMonth(),
        ];

        return $this->render('index', ['data' => $data, 'cities' => $cities, 'orders' => $orders]);
    }
}
