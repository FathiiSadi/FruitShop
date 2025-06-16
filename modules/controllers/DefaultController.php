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
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return !Yii::$app->user->isGuest && Yii::$app->user->identity->role === 'admin';
                        },
                    ],
                ],
            ],
        ];
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



    public function actionUser()
    {

        $users = User::find()->all();
        $admin = User::getAdmin();
        return $this->render('user', ['users' => $users, 'admin' => $admin]);
    }

    public function actionUserEdit()
    {
        return $this->render('user-edit', [
            'user' => Yii::$app->user->identity,
        ]);
    }

    public function actionProducts()
    {
        $products = Products::find()->all();
        $profit = Products::getExpectedProfit();
        $lowStockCount = Admin::getLowStockProductCount(10);
        $outOfStockCount = Admin::getOutOfStockProductCount();
        return $this->render('products', ['products' => $products, 'profit' => $profit, 'lowStockCount' => $lowStockCount, 'outOfStockCount' => $outOfStockCount]);
    }
    public function actionOrders()
    {
        $orders = Orders::find()->all();
        $max = Orders::getMax();
        $pending = Orders::getStatusPending();
        $bestMonth = Orders::getOrdersByMonth();
        $bestCity = Orders::getOrdersByCity();

        return $this->render('orders', ['orders' => $orders, 'max' => $max, 'pending' => $pending, 'bestMonth' => $bestMonth, 'bestCity' => $bestCity]);
    }
}
