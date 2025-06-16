<?php

namespace app\controllers;

use app\models\Cart;
use app\Models\CartSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\web\Response;
use yii\filters\AccessControl;

/**
 * CartController implements the CRUD actions for Cart model.
 */
class CartController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Cart models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CartSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $userId = Yii::$app->user->id; // Assuming user is logged in
        $cart = Cart::find()->where([
            'UserID' => $userId,
            'Status' => 'open'
        ])->with('cartItems.product')->one();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'cart' => $cart,
            'userId' => $userId,
        ]);
    }
}
