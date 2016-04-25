<?php

namespace backend\controllers\master;

use Yii;
use app\models\master\U2Warehouse;
use app\models\master\search\U2Warehouse as U2WarehouseSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * U2WarehouseController implements the CRUD actions for U2Warehouse model.
 */
class U2WarehouseController extends Controller
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
     * Lists all U2Warehouse models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new U2WarehouseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single U2Warehouse model.
     * @param integer $warehouse_id
     * @param integer $user_id
     * @return mixed
     */
    public function actionView($warehouse_id, $user_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($warehouse_id, $user_id),
        ]);
    }

    /**
     * Creates a new U2Warehouse model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new U2Warehouse();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'warehouse_id' => $model->warehouse_id, 'user_id' => $model->user_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing U2Warehouse model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $warehouse_id
     * @param integer $user_id
     * @return mixed
     */
    public function actionUpdate($warehouse_id, $user_id)
    {
        $model = $this->findModel($warehouse_id, $user_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'warehouse_id' => $model->warehouse_id, 'user_id' => $model->user_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing U2Warehouse model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $warehouse_id
     * @param integer $user_id
     * @return mixed
     */
    public function actionDelete($warehouse_id, $user_id)
    {
        $this->findModel($warehouse_id, $user_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the U2Warehouse model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $warehouse_id
     * @param integer $user_id
     * @return U2Warehouse the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($warehouse_id, $user_id)
    {
        if (($model = U2Warehouse::findOne(['warehouse_id' => $warehouse_id, 'user_id' => $user_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
