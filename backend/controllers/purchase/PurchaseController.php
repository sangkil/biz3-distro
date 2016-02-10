<?php

namespace backend\controllers\purchase;

use Yii;
use backend\models\purchase\Purchase;
use backend\models\purchase\search\Purchase as PurchaseSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\master\Product;
use backend\models\master\Vendor;
use yii\base\UserException;

/**
 * PurchaseController implements the CRUD actions for Purchase model.
 */
class PurchaseController extends Controller
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
     * Lists all Purchase models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PurchaseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Purchase model.
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
     * Creates a new Purchase model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Purchase();

        $model->status = Purchase::STATUS_DRAFT;
        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->items = Yii::$app->request->post('PurchaseDtl', []);
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
     * Updates an existing Purchase model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->status != Purchase::STATUS_DRAFT) {
            throw new UserException('Tidak bisa diupdate');
        }

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->items = Yii::$app->request->post('PurchaseDtl', []);
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
     * Deletes an existing Purchase model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->status != Purchase::STATUS_DRAFT) {
            throw new UserException('Tidak bisa didelete');
        }
        $model->delete();

        return $this->redirect(['index']);
    }

    public function actionProductList($term = '')
    {
        $response = Yii::$app->response;
        $response->format = 'json';
        return Product::find()
                ->filterWhere(['like', 'lower([[name]])', strtolower($term)])
                ->orFilterWhere(['like', 'lower([[code]])', strtolower($term)])
                ->limit(10)->asArray()->all();
    }

    public function actionVendorList($term = '')
    {
        $response = Yii::$app->response;
        $response->format = 'json';
        return Vendor::find()
                ->filterWhere(['like', 'lower([[name]])', strtolower($term)])
                ->orFilterWhere(['like', 'lower([[code]])', strtolower($term)])
                ->limit(10)->asArray()->all();
    }

    /**
     * Finds the Purchase model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Purchase the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Purchase::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
