<?php

namespace backend\controllers\sales;

use Yii;
use backend\models\sales\Sales;
use backend\models\sales\search\Sales as SalesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\master\Product;
use backend\models\master\Vendor;
use yii\base\UserException;
use backend\models\accounting\Payment;
use common\classes\Helper;
use backend\models\accounting\GlHeader;
use yii\db\Query;

/**
 * SalesController implements the CRUD actions for Sales model.
 */
class SalesController extends Controller
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
     * Lists all Sales models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SalesSearch();
        $searchModel->branch_id = \Yii::$app->profile->branch_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sales model.
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
     * Creates a new Sales model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $profile = Yii::$app->profile;
        if (!isset($profile->branch_id, $profile->warehouse_id)) {
            Yii::$app->getSession()->setFlash('_config_return_url', Yii::$app->getRequest()->getUrl());
            return $this->redirect(['config']);
        }
        $model = new Sales();

        $model->status = Sales::STATUS_RELEASED;
        $model->date = date('Y-m-d');
        $model->vendor_id = Sales::DEFAULT_VENDOR;
        $model->vendor_name = $model->vendor->name;
        $error = false;

        $payments = [];
        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            $model->branch_id = $profile->branch_id;
            try {
                $payments = Helper::createMultiple(Payment::className(), Yii::$app->request->post());
                if (!empty($payments)) {
                    $model->items = Yii::$app->request->post('SalesDtl', []);
                    if ($model->save()) {
                        $movement = $model->createMovement([
                            'warehouse_id' => $profile->warehouse_id,
                        ]);
                        $glHeader = new GlHeader([
                            'status' => GlHeader::STATUS_RELEASED,
                            'reff_type' => GlHeader::REFF_SALES,
                            'reff_id' => $model->id,
                            'date' => date('Y-m-d'),
                            'vendor_id' => $model->vendor_id,
                            'periode_id' => $this->findPeriode(),
                            'branch_id' => Yii::$app->profile->branch_id,
                        ]);

                        if ($movement && $movement->save()) {
                            $invoice = $movement->createInvoice();
                            if ($invoice && $invoice->save()) {
                                /* @var $payment Payment */
                                $success = true;
                                $total = 0;
                                $invoiceTotal = $invoice->value;
                                $paymentData = [
                                    'vendor_id' => $invoice->vendor_id,
                                    'date' => date('Y-m-d'),
                                    'type' => $invoice->type,
                                ];
                                foreach ($payments as $payment) {
                                    $payment->attributes = $paymentData;
                                    $payment->status = Payment::STATUS_RELEASED;

                                    $payItems = $payment->items;
                                    $payItems[0]->invoice_id = $invoice->id;
                                    if ($invoiceTotal - $total >= $payItems[0]->value) {
                                        $total += $payItems[0]->value;
                                    } else {
                                        $payItems[0]->value = $invoiceTotal - $total;
                                        $total = $invoiceTotal;
                                    }
                                    $payment->items = $payItems;
                                }
                                if ($invoice->value >= $total) {
                                    foreach ($payments as $i => $payment) {
                                        if (!$payment->save()) {
                                            $success = false;
                                            $firstErrors = $payment->firstErrors;
                                            $error = "Payment {$i}: " . reset($firstErrors);
                                            break;
                                        }
                                    }
                                } else {
                                    $success = false;
                                    //seharusnya muncul cash back jika berlebih, bukan error
                                    $error = 'Kurang bayar';
                                }

                                //Create Jurnal
                                $coa_sales = [
                                    'penjualan' => 16,
                                    'persediaan' => 32,
                                    'hpp' => 19
                                ];

                                if ($success) {
                                    //GL Header
                                    $gl = new \backend\models\accounting\GlHeader;
                                    $gl->periode_id = $this->findPeriode();
                                    $gl->date = date('Y-m-d');
                                    $gl->status = \backend\models\accounting\GlHeader::STATUS_RELEASED;
                                    $gl->reff_type = \backend\models\accounting\GlHeader::REFF_SALES;
                                    $gl->reff_id = $model->id;
                                    $gl->description = 'Sales POS';
                                    $gl->branch_id = (isset(Yii::$app->profile->branch_id)) ? Yii::$app->profile->branch_id
                                            : -1;

                                    //GL Detail
                                    //Debit Payment
                                    $glDtls = [];
                                    $toPayment = 0;
                                    foreach ($payments as $payment) {
                                        $payItems = $payment->items;
                                        $ndtl = new \backend\models\accounting\GlDetail();
                                        $ndtl->coa_id = $payment->paymentMethod->coa_id;
                                        $ndtl->header_id = null;
                                        $ndtl->amount = $payItems[0]->value - ($payment->paymentMethod->potongan * $payItems[0]->value);
                                        $toPayment += $ndtl->amount;
                                        $glDtls[] = $ndtl;

                                        if ($payment->paymentMethod->potongan > 0) {
                                            $ndtl2 = new \backend\models\accounting\GlDetail();
                                            $ndtl2->coa_id = $payment->paymentMethod->coa_id_potongan;
                                            $ndtl2->header_id = null;
                                            $ndtl2->amount = $payment->paymentMethod->potongan * $payItems[0]->value;
                                            $toPayment += $ndtl2->amount;
                                            $glDtls[] = $ndtl2;
                                        }
                                    }

                                    //Kredit Penjualan
                                    $ndtl = new \backend\models\accounting\GlDetail();
                                    $ndtl->coa_id = $coa_sales['penjualan']; //hardcode id_coa for penjualan
                                    $ndtl->header_id = null;
                                    $ndtl->amount = $toPayment * -1;
                                    $glDtls[] = $ndtl;

                                    /*
                                     * Sum total detail
                                     * Belum dikalikan dengan qty uom isi
                                     */
                                    $tcogs = 0;
                                    foreach ($model->items as $item) {
                                        $tcogs += $item->cogs * $item->qty * $item->productUom->isi;
                                    }

                                    //Debit HPP
                                    $ndtl = new \backend\models\accounting\GlDetail();
                                    $ndtl->coa_id = $coa_sales['hpp']; //hardcode id_coa for hpp
                                    $ndtl->header_id = null;
                                    $ndtl->amount = $tcogs; //isi dengan total cogs
                                    $glDtls[] = $ndtl;

                                    //Kredit Persediaan
                                    $ndtl = new \backend\models\accounting\GlDetail();
                                    $ndtl->coa_id = $coa_sales['persediaan']; //hardcode id_coa for persediaan
                                    $ndtl->header_id = null;
                                    $ndtl->amount = $tcogs * -1; //isi dengan total cogs
                                    $glDtls[] = $ndtl;

                                    $gl->glDetails = $glDtls;
                                    if (!$gl->save()) {
                                        print_r($gl->getErrors());
                                        print_r($gl->getRelatedErrors());
                                        $success = false;
                                    }
                                }

                                if ($success) {
                                    $transaction->commit();
                                    return $this->redirect(['create']);
                                    //return $this->redirect(['view', 'id' => $model->id]);
                                }
                            } else {
                                if ($invoice) {
                                    $firstErrors = $invoice->firstErrors;
                                    $error = "Invoice: " . reset($firstErrors);
                                } else {
                                    $error = 'Cannot create invoice';
                                }
                            }
                        } else {
                            if ($movement) {
                                $firstErrors = $movement->firstErrors;
                                $error = "GI: " . reset($firstErrors);
                            } else {
                                $error = 'Cannot create GI';
                            }
                        }
                    }
                } else {
                    $error = "Payment can not empty";
                }
                if ($error !== false) {
                    $model->addError('related', $error);
                }
            } catch (\Exception $exc) {
                $transaction->rollBack();
                throw $exc;
            }
            $transaction->rollBack();
        }
        return $this->render('create', [
                'model' => $model,
                'payments' => $payments
        ]);
    }

    public function actionConfig()
    {
        $model = new \backend\models\sales\Config();
        $model->attributes = Yii::$app->profile->states();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            Yii::$app->profile->states($model->attributes);
            $url = Yii::$app->getSession()->getFlash('_config_return_url', ['index']);
            return $this->redirect($url);
        }
        return $this->render('config', [
                'model' => $model
        ]);
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
        $query_price = (new Query())
            ->select(['product_id', 'price_category_id', 'price'])
            ->from(['{{%price}}'])
            ->orderBy(['product_id' => SORT_ASC, 'price_category_id' => SORT_ASC]);
        foreach ($query_price->all() as $row) {
            $products[$row['product_id']]['prices'][$row['price_category_id']] = $row['price'];
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

        // customer
        $query_vendor = (new Query())
            ->select(['id', 'code', 'name'])
            ->from('{{%vendor}}')
            ->where(['type' => Vendor::TYPE_CUSTOMER]);

        $result['vendors'] = $query_vendor->all();

        return 'var masters = ' . json_encode($result) . ';';
    }

    /**
     * Updates an existing Sales model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->status != Sales::STATUS_DRAFT) {
            throw new UserException('Tidak bisa diupdate');
        }

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->items = Yii::$app->request->post('SalesDtl', []);
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
     * Deletes an existing Sales model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->status != Sales::STATUS_DRAFT) {
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
                ->with(['prices'])
                ->filterWhere(['like', 'lower([[name]])', strtolower($term)])
                ->orFilterWhere(['like', 'lower([[name]])', strtolower($term)])
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
     * Finds the Sales model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sales the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sales::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findPeriode()
    {
        if (($model = \backend\models\accounting\AccPeriode::find()->active()->one()) !== null) {
            return $model->id;
        } else {
            throw new NotFoundHttpException('Active Periode not exist.');
        }
    }
}
