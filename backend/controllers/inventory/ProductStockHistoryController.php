<?php

namespace backend\controllers\inventory;

use Yii;
use backend\models\inventory\GoodsMovementDtl;
use backend\models\inventory\search\GoodsMovementDtl as GoodsMovementDtlSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductStockHistoryController implements the CRUD actions for GoodsMovementDtl model.
 */
class ProductStockHistoryController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all GoodsMovementDtl models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GoodsMovementDtlSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GoodsMovementDtl model.
     * @param integer $movement_id
     * @param integer $product_id
     * @return mixed
     */
    public function actionView($movement_id, $product_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($movement_id, $product_id),
        ]);
    }

    /**
     * Creates a new GoodsMovementDtl model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GoodsMovementDtl();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'movement_id' => $model->movement_id, 'product_id' => $model->product_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing GoodsMovementDtl model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $movement_id
     * @param integer $product_id
     * @return mixed
     */
    public function actionUpdate($movement_id, $product_id)
    {
        $model = $this->findModel($movement_id, $product_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'movement_id' => $model->movement_id, 'product_id' => $model->product_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing GoodsMovementDtl model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $movement_id
     * @param integer $product_id
     * @return mixed
     */
    public function actionDelete($movement_id, $product_id)
    {
        $this->findModel($movement_id, $product_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the GoodsMovementDtl model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $movement_id
     * @param integer $product_id
     * @return GoodsMovementDtl the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($movement_id, $product_id)
    {
        if (($model = GoodsMovementDtl::findOne(['movement_id' => $movement_id, 'product_id' => $product_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
