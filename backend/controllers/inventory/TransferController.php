<?php

namespace backend\controllers\inventory;

use Yii;
use backend\models\inventory\Transfer;
use backend\models\inventory\search\Transfer as TransferSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\UserException;
use yii\db\Query;

/**
 * TransferController implements the CRUD actions for Transfer model.
 */
class TransferController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'confirm' => ['post'],
                    'reject' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Transfer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TransferSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Transfer model.
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
     * Creates a new Transfer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Transfer();

        $model->status = Transfer::STATUS_DRAFT;
        $model->date = date('Y-m-d');
        if(\Yii::$app->profile->branch_id !== ''){
           $model->branch_id = \Yii::$app->profile->branch_id;
        }  else {
            throw new NotFoundHttpException('Cabang/Sales Point belum dipilih.');
        }
        
        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->items = Yii::$app->request->post('TransferDtl', []);
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
     * Updates an existing Transfer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->status != Transfer::STATUS_DRAFT) {
            throw new UserException('Tidak bisa diupdate');
        }
        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->items = Yii::$app->request->post('TransferDtl', []);
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

    public function actionConfirm($id)
    {
        $model = $this->findModel($id);
        if ($model->status != Transfer::STATUS_DRAFT) {
            throw new UserException('Tidak bisa direlease');
        }
        $model->scenario = Transfer::SCENARIO_CHANGE_STATUS;
        $model->status = Transfer::STATUS_RELEASED;
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

    public function actionReject($id)
    {
        $model = $this->findModel($id);
        if ($model->status != Transfer::STATUS_RELEASED) {
            throw new UserException('Tidak bisa direlease');
        }
        $model->scenario = Transfer::SCENARIO_CHANGE_STATUS;
        $model->status = Transfer::STATUS_CANCELED;
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
     * Deletes an existing Transfer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->status != Transfer::STATUS_DRAFT) {
            throw new UserException('Tidak bisa didelete');
        }
        $model->delete();

        return $this->redirect(['index']);
    }

    public function actionMaster()
    {
        $result = [];
        Yii::$app->getResponse()->format = 'js';

        $products = [];
        $query_product = (new Query())
            ->select(['p.id', 'p.code', 'p.name', 'price' => 'c.last_purchase_price'])
            ->from(['p' => '{{%product}}'])
            ->leftJoin(['c' => '{{%cogs}}'], '[[p.id]]=[[c.product_id]]');
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

        return 'var masters = ' . json_encode($result) . ';';
    }

    /**
     * Finds the Transfer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Transfer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transfer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
