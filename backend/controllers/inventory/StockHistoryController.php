<?php

namespace backend\controllers\inventory;

use Yii;
use app\models\inventory\ProductStockHistory;
use app\models\inventory\search\ProductStockHistory as ProductStockHistorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StockHistoryController implements the CRUD actions for ProductStockHistory model.
 */
class StockHistoryController extends Controller
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
     * Lists all ProductStockHistory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductStockHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProductStockHistory model.
     * @param double $time
     * @param integer $warehouse_id
     * @param integer $product_id
     * @return mixed
     */
    public function actionView($time, $warehouse_id, $product_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($time, $warehouse_id, $product_id),
        ]);
    }

    /**
     * Creates a new ProductStockHistory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProductStockHistory();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'time' => $model->time, 'warehouse_id' => $model->warehouse_id, 'product_id' => $model->product_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ProductStockHistory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param double $time
     * @param integer $warehouse_id
     * @param integer $product_id
     * @return mixed
     */
    public function actionUpdate($time, $warehouse_id, $product_id)
    {
        $model = $this->findModel($time, $warehouse_id, $product_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'time' => $model->time, 'warehouse_id' => $model->warehouse_id, 'product_id' => $model->product_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ProductStockHistory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param double $time
     * @param integer $warehouse_id
     * @param integer $product_id
     * @return mixed
     */
    public function actionDelete($time, $warehouse_id, $product_id)
    {
        $this->findModel($time, $warehouse_id, $product_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ProductStockHistory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param double $time
     * @param integer $warehouse_id
     * @param integer $product_id
     * @return ProductStockHistory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($time, $warehouse_id, $product_id)
    {
        if (($model = ProductStockHistory::findOne(['time' => $time, 'warehouse_id' => $warehouse_id, 'product_id' => $product_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
