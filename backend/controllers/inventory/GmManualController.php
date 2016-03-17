<?php

namespace backend\controllers\inventory;

use Yii;
use backend\models\inventory\GoodsMovement;
use backend\models\inventory\search\GoodsMovement as GoodsMovementSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\master\Vendor;
use yii\base\UserException;
use yii\db\Query;

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
        $model = $this->findModel($id);
        return $this->render('view', [
                'model' => $model,
        ]);
    }

    /**
     * Displays a single GoodsMovement model.
     * @param integer $id
     * @return mixed
     */
    public function actionPrint($id)
    {
        $this->layout = 'print';
        return $this->render('cetak', [
                'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new GoodsMovement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type = null)
    {
        $model = new GoodsMovement();
        $model->type = $type;

        $model->date = date('Y-m-d');
        if ($model->load(Yii::$app->request->post())) {
            $model->status = GoodsMovement::STATUS_DRAFT;
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
        if ($model->status != GoodsMovement::STATUS_DRAFT) {
            throw new UserException('Tidak bisa diupdate');
        }
        if ($model->vendor) {
            $model->vendor_name = $model->vendor->name;
        }
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
        $model = $this->findModel($id);
        if ($model->status != GoodsMovement::STATUS_DRAFT) {
            throw new UserException('Tidak bisa didelete');
        }
        $model->delete();

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
        if ($model->status != GoodsMovement::STATUS_DRAFT) {
            throw new UserException('Tidak bisa diconfirm');
        }
        $model->scenario = GoodsMovement::SCENARIO_CHANGE_STATUS;
        $model->status = GoodsMovement::STATUS_RELEASED;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->save()) {
                // update stock internaly via beforeUpdate
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            }
            $transaction->rollBack();
        } catch (\Exception $exc) {
            $transaction->rollBack();
            throw $exc;
        }
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
        if ($model->status != GoodsMovement::STATUS_RELEASED) {
            throw new UserException('Tidak bisa dirollback');
        }
        $model->scenario = GoodsMovement::SCENARIO_CHANGE_STATUS;
        $model->status = GoodsMovement::STATUS_CANCELED;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->save()) {
                // update stock internaly via beforeUpdate
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            }
            $transaction->rollBack();
        } catch (\Exception $exc) {
            $transaction->rollBack();
            throw $exc;
        }
    }

//    public function actionProductList($term = '')
//    {
//        $response = Yii::$app->response;
//        $response->format = 'json';
//        return Product::find()
//                ->with(['cogs'])
//                ->filterWhere(['like', 'lower([[name]])', strtolower($term)])
//                ->orFilterWhere(['like', 'lower([[code]])', strtolower($term)])
//                ->asArray()->limit(10)->all();
//    }

    public function actionMaster($type = null)
    {
        $result = [];
        Yii::$app->response->format = 'js';

        $products = [];
        $query_product = (new Query())
            ->select(['id', 'code', 'name'])
            ->from(['{{%product}}']);
        foreach ($query_product->all() as $row) {
            $products[$row['id']] = $row;
        }

        // product uoms
        $query_uom = (new Query())
            ->select(['p_id' => 'pu.product_id', 'pu.uom_id', 'u.name', 'pu.isi'])
            ->from(['pu' => '{{%product_uom}}'])
            ->innerJoin(['u' => 'uom'], '[[u.id]]=[[pu.uom_id]]')
            ->orderBy(['pu.product_id' => SORT_ASC, 'pu.isi' => SORT_ASC]);
        foreach ($query_uom->all() as $row) {
            $products[$row['p_id']]['uoms'][$row['uom_id']] = [
                'id' => $row['uom_id'],
                'name' => $row['name'],
                'isi' => $row['isi']
            ];
        }

        // product prices
        $query_cost = (new Query())
            ->select(['product_id', 'last_purchase_price', 'cogs'])
            ->from(['{{%cogs}}']);
        foreach ($query_cost->all() as $row) {
            $products[$row['product_id']]['cost'] = $row['last_purchase_price'];
        }

        $result['products'] = $products;

        $barcodes = [];
        $query_barcode = (new Query())
            ->select(['barcode' => 'lower(barcode)', 'id' => 'product_id'])
            ->from('{{%product_child}}')
            ->union((new Query())
            ->select(['lower(code)', 'id'])
            ->from('{{%product}}'));
        foreach ($query_barcode->all() as $row) {
            $barcodes[$row['barcode']] = $row['id'];
        }
        $result['barcodes'] = $barcodes;

        // vendors
        $query_vendor = (new Query())
            ->select(['id', 'code', 'name'])
            ->from('{{%vendor}}');

        ($type !== null) ? $query_vendor->where(['type' => [$type, Vendor::TYPE_INTERN]]) : '';
        $result['vendors'] = $query_vendor->all();

        return 'var masters = ' . json_encode($result) . ';';
    }

//    public function actionVendorList($term = '')
//    {
//        $response = Yii::$app->response;
//        $response->format = 'json';
//        return Vendor::find()
//                ->filterWhere(['like', 'lower([[name]])', strtolower($term)])
//                ->orFilterWhere(['like', 'lower([[code]])', strtolower($term)])
//                ->limit(10)->asArray()->all();
//    }

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
