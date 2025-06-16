<?php

namespace app\controllers;

use app\models\CartItem;
use app\Models\CartItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CartItemController implements the CRUD actions for CartItem model.
 */
class CartItemController extends Controller
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
     * Lists all CartItem models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CartItemSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('/admin/cart-item', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single CartItem model.
     * @param int $CartItemID Cart Item ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($CartItemID)
    {
        return $this->render('view', [
            'model' => $this->findModel($CartItemID),
        ]);
    }

    /**
     * Creates a new CartItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new CartItem();

        $model->AddedAt = date('Y-m-d H:i:s');

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'CartItemID' => $model->CartItemID]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CartItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $CartItemID Cart Item ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($CartItemID)
    {
        $model = $this->findModel($CartItemID);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'CartItemID' => $model->CartItemID]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CartItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $CartItemID Cart Item ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($CartItemID)
    {
        $this->findModel($CartItemID)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CartItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $CartItemID Cart Item ID
     * @return CartItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($CartItemID)
    {
        if (($model = CartItem::findOne(['CartItemID' => $CartItemID])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
