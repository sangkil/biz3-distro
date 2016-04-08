<?php

namespace backend\controllers\accounting;

use Yii;
use backend\models\accounting\Payment;
use backend\models\accounting\search\Payment as PaymentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\UserException;
use backend\models\master\Vendor;

/**
 * PaymentController implements the CRUD actions for Payment model.
 */
class PaymentController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'post' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Payment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Payment model.
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
     * Creates a new Payment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($invoice_id = null)
    {
        $model = new Payment();
        $invoice = ($invoice_id != null) ? \backend\models\accounting\Invoice::findOne($invoice_id) : null;

        $model->status = Payment::STATUS_DRAFT;
        $model->date = date('Y-m-d');
        $model->type = Payment::TYPE_OUTGOING;

        if ($invoice != null) {
            $model->vendor_id = $invoice->vendor_id;
            $model->vendor_name = $invoice->vendor->name;

            $pay_item = new \backend\models\accounting\PaymentDtl();
            $pay_item->invoice_id = $invoice_id;
            $pay_item->value = $invoice->sisa;
            $model->items = [$pay_item];
        }

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->items = Yii::$app->request->post('PaymentDtl', []);
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
     * Updates an existing Payment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->status != Payment::STATUS_DRAFT) {
            throw new UserException('Tidak bisa diupdate');
        }

        if ($model->vendor) {
            $model->vendor_name = $model->vendor->name;
        }
        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->items = Yii::$app->request->post('PaymentDtl', []);
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
     * Post an existing Payment model.
     * If posting is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPost($id)
    {
        $model = $this->findModel($id);
        if ($model->status != Payment::STATUS_DRAFT) {
            throw new UserException('Tidak bisa diposting');
        }

        $transc = \Yii::$app->db->beginTransaction();
        try {
            $model->status = Payment::STATUS_RELEASED;
            if ($model->save()) {
                /*
                 * Create journal
                 * PaymentMethod->coa_id (+)
                 * Hutang Dagang (-) --> if invoice->type = Invoice::TYPE_INCOMING
                 */
                $coa_payment = ['hutang dagang' => 12];

                //GL Header
                $gl = new \backend\models\accounting\GlHeader;
                $gl->periode_id = $this->findPeriode();
                $gl->date = date('Y-m-d');
                $gl->status = \backend\models\accounting\GlHeader::STATUS_RELEASED;
                $gl->reff_type = \backend\models\accounting\GlHeader::REFF_INVOICE;
                $gl->reff_id = $model->invoices[0]->id;
                $gl->description = 'Invoice Payment';
                $gl->branch_id = (isset(Yii::$app->profile->branch_id)) ? Yii::$app->profile->branch_id : -1;

                $glDtls = [];
                $toPayment = 0;
                foreach ($model->items as $itemRow) {
                    $toPayment += $itemRow->value;
                }

                //Kredit Penjualan
                $ndtl = new \backend\models\accounting\GlDetail();
                $ndtl->coa_id = $model->paymentMethod->coa_id;
                $ndtl->header_id = null;
                $ndtl->amount = $toPayment;
                $glDtls[] = $ndtl;

                $ndtl2 = new \backend\models\accounting\GlDetail();
                $ndtl2->coa_id = $coa_payment['hutang dagang'];
                $ndtl2->header_id = null;
                $ndtl2->amount = $toPayment * -1;
                $glDtls[] = $ndtl2;

                $gl->glDetails = $glDtls;
                if ($gl->save()) {
                    $transc->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                }else{
                    print_r($gl->getErrors());
                }
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /**
     * Deletes an existing Payment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->status != Payment::STATUS_DRAFT) {
            throw new UserException('Tidak bisa didelete');
        }
        $model->delete();

        return $this->redirect(['index']);
    }

    public function actionVendorList($term = '', $id = null)
    {
        $response = Yii::$app->response;
        $response->format = 'json';
        return Vendor::find()
                ->filterWhere(['like', 'lower([[name]])', strtolower($term)])
                ->orFilterWhere(['like', 'lower([[code]])', strtolower($term)])
                ->andFilterWhere(['id' => $id])
                ->limit(10)->asArray()->all();
    }

    public function actionInvoiceList($type = '', $vendor = '', $term = '')
    {
        Yii::$app->response->format = 'json';

        $queryPD = (new \yii\db\Query())
            ->select(['pd.invoice_id', 'pd.value'])
            ->from(['pd' => '{{%payment_dtl}}'])
            ->innerJoin('{{%payment}} p', '[[p.id]]=[[pd.payment_id]]')
            ->where(['p.status' => Payment::STATUS_CLOSE]);

        $query = (new \yii\db\Query())
            ->select(['iv.id', 'iv.number', 'iv.date', 'iv.value', 'paid' => 'sum([[pd.value]])',
                'iv.vendor_id', 'iv.type'])
            ->from(['iv' => '{{%invoice}}'])
            ->leftJoin(['pd' => $queryPD], '[[pd.invoice_id]]=[[iv.id]]')
            ->andFilterWhere(['iv.vendor_id' => $vendor, 'iv.type' => $type])
            ->andFilterwhere(['like', 'LOWER([[iv.number]])', strtolower($term)])
            ->groupBy(['iv.id']);

        return $query->all();
    }

    /**
     * Finds the Payment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Payment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Payment::findOne($id)) !== null) {
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
