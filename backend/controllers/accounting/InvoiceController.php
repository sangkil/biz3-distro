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
    public function actionIndex() {
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
    public function actionView($id) {
        return $this->render('view', [
             'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Invoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Invoice();
        $model->load(Yii::$app->request->get());
        $model->reff_type = ($model->type == $model::TYPE_SUPPLIER) ? $model::REFF_PURCH : null;
        $model->reff_type = ($model->type == $model::TYPE_CUSTOMER) ? $model::REFF_SALES : $model->reff_type;
        
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

        $model->status = Invoice::STATUS_POSTED;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->save()) {
                //post journal first
                $gl = new \backend\models\accounting\GlHeader();
                $gl->branch_id = 1;
                $aPeriode = \backend\models\accounting\AccPeriode::find()->active()->one();
                if ($aPeriode == null) {
                    throw new NotFoundHttpException('No active periode exist.');
                }
                $gl->periode_id = $aPeriode->id;
                $gl->reff_type = Invoice::REFF_INVOICE;
                $gl->reff_id = $model->id;
                $gl->status = $gl::STATUS_RELEASED;
                $gl->date = date('Y-m-d');
                $newDtls = [];
                
                //160006 entriseet for test
                $dtl_template = \backend\models\accounting\EntriSheet::findOne(160006);
                foreach ($dtl_template->entriSheetDtls as $ddtl) {
                    $ndtl = new \backend\models\accounting\GlDetail();
                    $ndtl->coa_id = $ddtl->coa_id;
                    $ndtl->header_id = null;
                    $ndtl->amount = ($ddtl->dk == $ddtl::DK_CREDIT) ? -1 * $model->value : $model->value;
                    $newDtls[] = $ndtl;
                }
                $gl->description = $dtl_template->name;
                $gl->glDetails = $newDtls;

                if ($gl->save()) {
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    print_r($gl->getErrors());
                    print_r($gl->getRelatedErrors());
                }
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
                        ->asArray()->limit(10)->all();
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

}
