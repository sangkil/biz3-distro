<?php

namespace backend\controllers\inventory;

use Yii;
use backend\models\inventory\GoodsMovement;
use backend\models\inventory\search\GoodsMovement as GoodsMovementSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\master\Product;

/**
 * GmManualController implements the CRUD actions for GoodsMovement model.
 */
class GmManualController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'confirm' => ['post'],
                    'rollback' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all GoodsMovement models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GoodsMovementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $query = $dataProvider->query;
        $query->with('warehouse');

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GoodsMovement model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
                'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new GoodsMovement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GoodsMovement();

        $model->status = GoodsMovement::STATUS_DRAFT;
        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->items = Yii::$app->request->post('GoodsMovementDtl', []);
                if ($model->save()) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } catch (\Exception $exc) {
                $transaction->rollBack();
                throw $exc;
            }
            $transaction->rollBack();
        }
        return $this->render('create', [
                'model' => $model,
        ]);
    }

    /**
     * Updates an existing GoodsMovement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->items = Yii::$app->request->post('GoodsMovementDtl', []);
                if ($model->save()) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } catch (\Exception $exc) {
                $transaction->rollBack();
                throw $exc;
            }
            $transaction->rollBack();
        }
        return $this->render('update', [
                'model' => $model,
        ]);
    }

    /**
     * Deletes an existing GoodsMovement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing GoodsMovement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionConfirm($id)
    {
        $model = $this->findModel($id);

        $model->status = GoodsMovement::STATUS_APPLIED;
        $model->save();
        return $this->render('view', [
                'model' => $model,
        ]);
    }

    /**
     * Deletes an existing GoodsMovement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionRollback($id)
    {
        $model = $this->findModel($id);

        $model->status = GoodsMovement::STATUS_DRAFT;
        $model->save();
        return $this->render('view', [
                'model' => $model,
        ]);
    }

    public function actionListProduct($term = '', \yii\web\Response $response)
    {
        $response->format = 'json';
        return Product::find()->filterWhere(['like', 'name', $term])
                ->asArray()->limit(10)->all();
    }

    /**
     * Finds the GoodsMovement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GoodsMovement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GoodsMovement::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
