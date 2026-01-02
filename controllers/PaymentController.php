<?php

namespace app\controllers;

use app\models\Payments;
use app\models\PaymentSearch;
use yii\web\Controller;
use app\models\Cart;
use app\models\Orders;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * PaymentsController implements the CRUD actions for Payments model.
 */
class PaymentController extends Controller
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
     * Lists all Payments models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Payments model.
     * @param int $payment_id Payment ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($payment_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($payment_id),
        ]);
    }

    /**
     * Creates a new Payments model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Payments();

        $userId = Yii::$app->user->id;
        $cart = Cart::find()->where(['user_id' => $userId, 'status' => 'open'])->with('cartItems.product')->one();

        if ($cart === null) {
            throw new NotFoundHttpException('No active cart found.');
        }
        $totalAmount = $cart->getTotalWithTax() + 15;
        $model->amount = $totalAmount;
        $model->payment_date = date('Y-m-d H:i:s');
        $model->id = Orders::find()->where(['user_id' => $userId, 'status' => 'pending'])->orderBy(['id' => SORT_DESC])->one()->id;

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'payment_id' => $model->payment_id]);
            }
        } else {
            $model->loadDefaultValues();
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Payments model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $payment_id Payment ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($payment_id)
    {
        $model = $this->findModel($payment_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'payment_id' => $model->payment_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Payments model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $payment_id Payment ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($payment_id)
    {
        $this->findModel($payment_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Payments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $payment_id Payment ID
     * @return Payments the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($payment_id)
    {
        if (($model = Payments::findOne(['payment_id' => $payment_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
