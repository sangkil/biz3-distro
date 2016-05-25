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
class InvoiceController extends Controller {

    public function behaviors() {
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
    public function actionIndex($type = null) {
        $searchModel = new InvoiceSearch();
        $searchModel->type = $type;

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
    public function actionView($id) {
        //$model_journal = new \backend\models\accounting\GlHeader;
        $model = $this->findModel($id);
        $newDtls = [];

        return $this->render('view', [
                    'model' => $model
        ]);
    }

    /**
     * Creates a new Invoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Invoice();
        $dgets = Yii::$app->request->get();
        $model->load($dgets);
        $model->reff_type = ($model->type == $model::TYPE_INCOMING) ? $model::REFF_PURCH : null;
        $model->reff_type = ($model->type == $model::TYPE_OUTGOING) ? $model::REFF_SALES : $model->reff_type;

        $model->status = Invoice::STATUS_DRAFT;
        $model->date = date('Y-m-d');
        $model->due_date = date('Y-m-d', time() + 30 * 24 * 3600);
        $model->value = 0;

        if (isset($dgets['goodsMovement']['id'])) {
            $gmv = \backend\models\inventory\GoodsMovement::find()
                    ->where(['=', 'id', $dgets['goodsMovement']['id']])
                    ->with(['vendor'])
                    ->one();
            $model->reff_type = $model::REFF_GOODS_MOVEMENT;
            $model->reff_id = $gmv->id;
            $model->vendor_id = $gmv->vendor_id;
            $model->vendor_name = $gmv->vendor->name;
            $model->description = 'GR Invoice';
            $gmItems = [];
            $subtotal = 0;
            foreach ($gmv->items as $rvalue) {
                $ditem = new \backend\models\accounting\InvoiceDtl();
                $ditem->item_id = $rvalue->product_id;
                $ditem->qty = $rvalue->qty;
                $ditem->item_value = $rvalue->cogs;
                $subtotal += ($rvalue->qty * $rvalue->cogs);
                $gmItems[] = $ditem;
            }
            $model->value = $subtotal;
            $model->items = $gmItems;
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
    public function actionUpdate($id) {
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
    public function actionDelete($id) {
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
    public function actionPost($id) {
        $model = $this->findModel($id);
        $model->status = Invoice::STATUS_RELEASED;
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

                $esheet = \backend\models\accounting\EntriSheet::find()->where('code=:dcode', [':dcode' => 'ES002'])->one();
                $gl->description = $esheet->name;

                /*
                 * Detail Journal
                 */
                $newDtls = [];

                $ndtl = new \backend\models\accounting\GlDetail();
                $ndtl->coa_id = $esheet->d_coa_id;
                $ndtl->header_id = null;
                $ndtl->amount = $model->value;
                $newDtls[] = $ndtl;

                $ndtl1 = new \backend\models\accounting\GlDetail();
                $ndtl1->coa_id = $esheet->k_coa_id;
                $ndtl1->header_id = null;
                $ndtl1->amount = $model->value * -1;
                $newDtls[] = $ndtl1;

                $gl->glDetails = $newDtls;

                if ($gl->save()) {
                    $transaction->commit();
                } else {
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
            }else{
                print_r($model->getErrors());
            }
        } catch (\Exception $exc) {
            $transaction->rollBack();
            echo $exc->getMessage();
        }
        
        return $this->render('view', [
                    'model' => $model
        ]);
    }

    /**
     * Deletes an existing GoodsMovement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionRevert($id) {
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

    public function actionProductList($term = '') {
        $response = Yii::$app->response;
        $response->format = 'json';
        return Product::find()
                        ->filterWhere(['like', 'lower([[name]])', strtolower($term)])
                        ->orFilterWhere(['like', 'lower([[code]])', strtolower($term)])
                        ->asArray()->limit(100)->all();
    }

    public function actionVendorList($term = '') {
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
    protected function findModel($id) {
        if (($model = Invoice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionListCoa($term = '') {
        $response = Yii::$app->response;
        $response->format = 'json';
        $coaList = \backend\models\accounting\Coa::find()
                        ->filterWhere(['like', 'lower([[name]])', strtolower($term)])
                        ->orFilterWhere(['like', 'lower([[code]])', strtolower($term)])
                        ->codeOrdered()->asArray()->limit(10);

        return $coaList->all();
    }

    public function actionMaster($type = null) {
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

}
