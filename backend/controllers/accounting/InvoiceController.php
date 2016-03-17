<?php

namespace backend\controllers\accounting;

use Yii;
use backend\models\accounting\Invoice;
use backend\models\accounting\search\Invoice as InvoiceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\master\Product;
use backend\models\master\Vendor;
use yii\base\UserException;
use yii\db\Query;

/**
 * InvoiceFromGmController implements the CRUD actions for Invoice model.
 */
class InvoiceController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'post' => ['post'],
                    'revert' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Invoice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Invoice model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model_journal = new \backend\models\accounting\GlHeader;
        $model = $this->findModel($id);

//        $newDtls = [];
//        $ndtl = new \backend\models\accounting\GlDetail();
//        $ndtl->coa_id = 9;
//        $ndtl->header_id = null;
//        $ndtl->amount = $model->value;
//        $newDtls[] = $ndtl;
//
//        $ndtl = new \backend\models\accounting\GlDetail();
//        $ndtl->coa_id = 12;
//        $ndtl->header_id = null;
//        $ndtl->amount = $model->value *-1;
//        $newDtls[] = $ndtl;
//
//        $model_journal->glDetails = $newDtls;

        return $this->render('view', [
                'model' => $model,
                'model_journal' => (!empty($model->journals)) ? $model->journals[0] : $model_journal
        ]);
    }

    /**
     * Creates a new Invoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Invoice();
        $model->load(Yii::$app->request->get());
        $model->reff_type = ($model->type == $model::TYPE_INCOMING) ? $model::REFF_PURCH : null;
        $model->reff_type = ($model->type == $model::TYPE_OUTGOING) ? $model::REFF_SALES : $model->reff_type;

        $model->status = Invoice::STATUS_DRAFT;
        $model->date = date('Y-m-d');
        $model->due_date = date('Y-m-d', time() + 30 * 24 * 3600);
        $model->value = 0;

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->items = Yii::$app->request->post('InvoiceDtl', []);
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
     * Updates an existing Invoice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->status != Invoice::STATUS_DRAFT) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->items = Yii::$app->request->post('InvoiceDtl', []);
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
     * Deletes an existing Invoice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->status != Invoice::STATUS_DRAFT) {
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
    public function actionPost($id)
    {
        $model = $this->findModel($id);
        $glDtl = Yii::$app->request->post('GlDetail', []);

        $model->status = Invoice::STATUS_POSTED;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->save()) {
                //post journal first
                $gl = new \backend\models\accounting\GlHeader();
                $gl->branch_id = 1;
                $aPeriode = \backend\models\accounting\AccPeriode::find()->active()->one();
                if ($aPeriode == null) {
                    throw new NotFoundHttpException('No active periode exist for now.');
                }
                $gl->periode_id = $aPeriode->id;
                $gl->reff_type = $gl::REFF_INVOICE;
                $gl->reff_id = $model->id;
                $gl->status = $gl::STATUS_RELEASED;
                $gl->date = date('Y-m-d');
                $newDtls = [];

                $glDtl = Yii::$app->request->post('GlDetail', []);
                foreach ($glDtl as $ddtl) {
                    $ndtl = new \backend\models\accounting\GlDetail();
                    $ndtl->coa_id = $ddtl['coa_id'];
                    $ndtl->header_id = null;
                    $ndtl->amount = ($ddtl['debit'] !== null || $ddtl['debit'] !== '') ? $ddtl['debit'] : 0;
                    $ndtl->amount = ($ndtl->amount == 0) ? -1 * $ddtl['credit'] : $ndtl->amount;
                    $newDtls[] = $ndtl;
                }

                $gl->description = $model->description;
                $gl->glDetails = $newDtls;

                if ($gl->save()) {
                    $transaction->commit();
                } else {
//                    print_r($gl->getErrors());
//                    print_r($gl->getRelatedErrors());
                    foreach ($gl->getErrors() as $dkey => $vald) {
                        if ($vald[0] == 'Related error') {
                            foreach ($gl->getRelatedErrors() as $dkey => $valr) {
                                foreach ($valr as $tkey => $valt) {
                                    \Yii::$app->getSession()->setFlash('error', $valt);
                                }
                                break;
                            }
                        } else {
                            \Yii::$app->getSession()->setFlash('error', $vald[0]);
                            break;
                        }
                    }
                    $transaction->rollback();
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
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
    public function actionRevert($id)
    {
        $model = $this->findModel($id);

        $model->status = Invoice::STATUS_DRAFT;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->save()) {
                //post journal first
                if ($journal_cancel = true) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
            $transaction->rollBack();
        } catch (\Exception $exc) {
            $transaction->rollBack();
            throw $exc;
        }
    }

    public function actionProductList($term = '')
    {
        $response = Yii::$app->response;
        $response->format = 'json';
        return Product::find()
                ->filterWhere(['like', 'lower([[name]])', strtolower($term)])
                ->orFilterWhere(['like', 'lower([[code]])', strtolower($term)])
                ->asArray()->limit(100)->all();
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
     * Finds the Invoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Invoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Invoice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionListCoa($term = '')
    {
        $response = Yii::$app->response;
        $response->format = 'json';
        $coaList = \backend\models\accounting\Coa::find()
                ->filterWhere(['like', 'lower([[name]])', strtolower($term)])
                ->orFilterWhere(['like', 'lower([[code]])', strtolower($term)])
                ->codeOrdered()->asArray()->limit(10);

        return $coaList->all();
    }

    public function actionMaster()
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
            ->from('{{%vendor}}')
            //->where(['type' => Vendor::TYPE_CUSTOMER])
            ;

        $result['vendors'] = $query_vendor->all();

        return 'var masters = ' . json_encode($result) . ';';
    }
}
