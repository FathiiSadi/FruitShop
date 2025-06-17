<?php

namespace app\controllers;

use app\models\Orders;
use app\models\OrdersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Cart;
use app\models\Addresses;
use app\models\OrderSearch;
use Yii;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends Controller
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
     * Create and save a new order, then redirect to success page
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $user = Yii::$app->user->id;

        $cart = Cart::find()->where(['UserID' => $user, 'Status' => 'open'])->with('cartItems.product')->one();

        if (!$cart || $cart->isEmpty()) {
            Yii::$app->session->setFlash('error', 'Your cart is empty.');
            return $this->redirect(['cart/index']);
        }

        $address = Addresses::find()->where(['UserID' => $user])->orderBy(['address_id' => SORT_DESC])->one();

        if (!$address) {
            Yii::$app->session->setFlash('error', 'No address found. Please add an address first.');
            return $this->redirect(['checkout/index']);
        }

        $model = new Orders();
        $model->UserID = $user;
        $model->address_id = $address->address_id;
        $model->subtotal = $cart->getSubtotal();
        $model->tax_amount = $cart->getTaxAmount();
        $model->shipping_cost = 15;
        $model->total_amount = $cart->getTotalWithTax() + 15;
        $model->status = Orders::STATUS_PROCESSING;

        if ($model->save()) {
            $cart->Status = 'checked_out';
            $cart->save();

            return $this->redirect(['success', 'order_id' => $model->order_id]);
        }
    }

    /**
     * Lists all Orders models for admin/user view.
     */
    public function actionList()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Orders model.
     * @param int $order_id Order ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($order_id)
    {
        $order = Orders::find()
            ->where(['order_id' => $order_id, 'UserID' => Yii::$app->user->id])
            ->one();
        return $this->render('view', [
            'model' => $this->findModel($order_id),
            'order' => $order,
        ]);
    }

    /**
     * Creates a new Orders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Orders();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'order_id' => $model->order_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Orders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $order_id Order ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($order_id)
    {
        $model = $this->findModel($order_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'order_id' => $model->order_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Orders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $order_id Order ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($order_id)
    {
        $this->findModel($order_id)->delete();

        return $this->redirect(['list']);
    }

    /**
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $order_id Order ID
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($order_id)
    {
        if (($model = Orders::findOne(['order_id' => $order_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Show order success page
     */
    public function actionSuccess($order_id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        // Find order and ensure it belongs to current user
        $order = Orders::find()
            ->where(['order_id' => $order_id, 'UserID' => Yii::$app->user->id])
            ->one();

        if (!$order) {
            throw new NotFoundHttpException('Order not found.');
        }

        return $this->render('success', [
            'order' => $order,
        ]);
    }
}
