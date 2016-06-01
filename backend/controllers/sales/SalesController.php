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
use backend\models\inventory\GoodsMovement;
use backend\models\accounting\Invoice;
use yii\db\Query;

/**
 * SalesController implements the CRUD actions for Sales model.
 */
class SalesController extends Controller {

    public function behaviors() {
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
    public function actionIndex() {
        $searchModel = new SalesSearch();
        $searchModel->branch_id = Yii::$app->profile->branch_id;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDaily() {
        $searchModel = new SalesSearch();
        $searchModel->branch_id = Yii::$app->profile->branch_id;
        $searchModel->Date = date('m');

        $dataProvider = $searchModel->searchDaily(Yii::$app->request->queryParams);
        $parms = Yii::$app->request->queryParams;
        $bln = [];
        for ($i = 1; $i < 13; $i++) {
            $time = mktime(0, 0, 0, $i);
            $bln[date('n', $time)] = date('M Y', $time);
        }

        return $this->render('daily', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'dmonth'=>$parms['Sales']['Date'],
                    'bln'=>$bln
        ]);
    }

    /**
     * Displays a single Sales model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    public function actionCetak($id) {
        $this->layout = 'print';
        return $this->render('cetak', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Sales model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $profile = Yii::$app->profile;
        if (!isset($profile->branch_id, $profile->warehouse_id) || $profile->branch_id == '') {
            Yii::$app->getSession()->setFlash('_config_return_url', Yii::$app->getRequest()->getUrl());
            return $this->redirect(['config']);
        }

        $model = new Sales();

        $model->status = Sales::STATUS_RELEASED;
        $model->date = date('Y-m-d');
        $model->branch_id = Yii::$app->profile->branch_id;
        $model->vendor_id = Sales::DEFAULT_VENDOR;
        $model->vendor_name = $model->vendor->name;
        $error = false;
        $whse = $this->findWarehouse(Yii::$app->profile->warehouse_id);

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
                            'description' => 'Sales Retail'
                        ]);
                        $glHeader = new GlHeader([
                            'status' => GlHeader::STATUS_RELEASED,
                            'reff_type' => GlHeader::REFF_SALES,
                            'reff_id' => $model->id,
                            'date' => date('Y-m-d'),
                            'branch_id' => $model->branch_id,
                            'periode_id' => $this->findPeriode(),
                            'description' => "Sales POS [{$model->number}]",
                        ]);
                        //Create Jurnal
                        $coa_sales = [
                            'penjualan' => 16,
                            'persediaan' => 32,
                            'hpp' => 19,
                            'diskon' => 18
                        ];

                        $tcogs = 0;
                        $penjualan = 0;
                        $diskon = 0;
                        foreach ($model->items as $item) {
                            $tcogs += $item->cogs * $item->qty * $item->productUom->isi;
                            $penjualan += $item->price * $item->qty * $item->productUom->isi;
                            $diskon += 0.01 * $item->discount * $item->qty * $item->productUom->isi * $item->price;
                        }
                        $glDetails = [];

                        // Hpp(D) Vs Persediaan(K)
                        $glDetails[] = [
                            'coa_id' => $coa_sales['hpp'],
                            'amount' => $tcogs,
                        ];
                        $glDetails[] = [
                            'coa_id' => $coa_sales['persediaan'],
                            'amount' => -$tcogs,
                        ];

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

                                $totalPaid = 0;
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

                                    // payment
                                    $bayar = $payItems[0]->value * (1 - $payment->paymentMethod->potongan);
                                    $glDetails[] = [
                                        'coa_id' => $payment->paymentMethod->coa_id,
                                        'amount' => $bayar
                                    ];

                                    // potongan payment method
                                    $potongan_cc = 0;
                                    if ($payment->paymentMethod->potongan > 0) {
                                        $potongan_cc = $payItems[0]->value * $payment->paymentMethod->potongan;
                                        $glDetails[] = [
                                            'coa_id' => $payment->paymentMethod->coa_id_potongan,
                                            'amount' => $potongan_cc
                                        ];
                                    }

                                    $totalPaid += $bayar + $potongan_cc;
                                    $payment->items = $payItems;
                                }

                                // Penjualan(K) Vs [Payment(D) + Diskon(D)]                                
                                if ($diskon != 0) {
                                    $glDetails[] = [
                                        'coa_id' => $coa_sales['diskon'],
                                        'amount' => $diskon,
                                    ];
                                }
                                $glDetails[] = [
                                    'coa_id' => $coa_sales['penjualan'],
                                    'amount' => -1 * $penjualan,
                                ];
                                if ($invoice->value >= $total) {
                                    foreach ($payments as $i => $payment) {
                                        if (!$payment->save()) {
                                            $success = false;
                                            $firstErrors = $payment->firstErrors;
                                            $error = "Payment {$i}: " . reset($firstErrors);
                                            break;
                                        }
                                    }
                                    if ($success) {
                                        $glHeader->glDetails = $glDetails;
                                        if (!$glHeader->save()) {
                                            $success = false;
                                            $firstErrors = $glHeader->firstErrors;
                                            $error = "Journal: " . reset($firstErrors);
                                        }
                                    }
                                } else {
                                    $success = false;
                                    //seharusnya muncul cash back jika berlebih, bukan error
                                    $error = 'Kurang bayar';
                                }

                                if ($success) {
                                    $transaction->commit();
                                    //return $this->redirect(['create']);
                                    return $this->redirect(['view', 'id' => $model->id]);
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
                    'payments' => $payments,
                    'warehouse' => $whse->name
        ]);
    }

    public function actionConfig() {
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

    public function actionMaster() {
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
    public function actionUpdate($id) {
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
    public function actionDelete($id) {
        $model = $this->findModel($id);
        if ($model->status == Sales::STATUS_DRAFT) {
            $model->delete();
            return $this->redirect(['index']);
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // gl
            $gl = GlHeader::findOne(['reff_type' => GlHeader::REFF_SALES, 'reff_id' => $id]);

            // movement
            $movement = ($gl != null) ? GoodsMovement::findOne(['reff_type' => GoodsMovement::REFF_SALES, 'reff_id' => $id]) : null;

            // invoice from movement
            $invoice = ($movement != null) ? Invoice::findOne(['reff_type' => Invoice::REFF_GOODS_MOVEMENT, 'reff_id' => $movement->id]) : null;

            // payment invoive
            $payments = ($invoice != null) ? $invoice->payments : [];
            foreach ($payments as $payment) {
                if (!$payment->delete()) {
                    throw new UserException('Cannot delete payment');
                }
            }

            if (count($payments) > 0 && $invoice->delete() && $movement->delete() && $gl->reserve() && $model->delete()) {
                //do nothing
                $transaction->commit();
                return $this->redirect(['index']);
            } else {
                throw new UserException('Something error');
            }
        } catch (\Exception $exc) {
            $transaction->rollBack();
            throw $exc;
        }
    }

    public function actionProductList($term = '') {
        $response = Yii::$app->response;
        $response->format = 'json';
        return Product::find()
                        ->with(['prices'])
                        ->filterWhere(['like', 'lower([[name]])', strtolower($term)])
                        ->orFilterWhere(['like', 'lower([[name]])', strtolower($term)])
                        ->limit(10)->asArray()->all();
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
     * Finds the Sales model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sales the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Sales::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findWarehouse($id) {
        if (($model = \backend\models\master\Warehouse::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findPeriode() {
        if (($model = \backend\models\accounting\AccPeriode::find()->active()->one()) !== null) {
            return $model->id;
        } else {
            throw new NotFoundHttpException('Active Periode not exist.');
        }
    }

}
