<?php

namespace backend\controllers\accounting;

use Yii;
use backend\models\accounting\Payment;
use backend\models\accounting\search\Payment as PaymentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\UserException;
use backend\models\accounting\Invoice;

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
        return $this->render('view', [
                'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Payment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Payment();

        $model->status = Payment::STATUS_DRAFT;
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

    public function actionInvoiceList($type = null, $vendor = null, $term = '')
    {
        Yii::$app->response->format = 'json';
        if (empty($type) || empty($vendor)) {
            return[];
        }

        $query = (new \yii\db\Query())
            ->select(['iv.id', 'iv.number', 'iv.date', 'paid' => 'sum([[pd.value]])'])
            ->from(['iv' => '{{%invoice}}'])
            ->leftJoin('{{%payment_dtl}} pd', '[[pd.invoice_id]]=[[iv.id]]')
            ->leftJoin('{{%payment}} p', '[[p.id]]=[[pd.payment_id]]')
            ->where(['iv.vendor_id' => $vendor, 'iv.type' => $type, 'p.status' => Payment::STATUS_CLOSE])
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
}
