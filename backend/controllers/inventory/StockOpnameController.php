<?php

namespace backend\controllers\inventory;

use Yii;
use backend\models\inventory\StockOpname;
use backend\models\inventory\search\StockOpname as StockOpnameSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * StockOpnameController implements the CRUD actions for StockOpname model.
 */
class StockOpnameController extends Controller
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
     * Lists all StockOpname models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StockOpnameSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StockOpname model.
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
     * Creates a new StockOpname model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StockOpname();

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->status = StockOpname::STATUS_DRAFT;
                $model->file = UploadedFile::getInstance($model, 'file');
                if ($model->save()) {
                    if ($model->file) {
                        $barcodes = $this->getBarcodes();
                        $stock = [];

                        $content = file_get_contents($model->file->tempName);
                        foreach (explode("\n", $content) as $code) {
                            $product_id = $barcodes[strtolower($code)];
                            if (!isset($stock[$product_id])) {
                                $stock[$product_id] = 1;
                            } else {
                                $stock[$product_id] ++;
                            }
                        }
                        $command = \Yii::$app->db->createCommand();
                        $id = $model->id;
                        foreach ($stock as $product_id => $count) {
                            $command->insert('{{%stock_opname_dtl}}', [
                                'opname_id' => $id,
                                'product_id' => $product_id,
                                'uom_id' => 1,
                                'qty' => $count,
                            ])->execute();
                        }
                    }
                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } catch (\Exception $exc) {
                $transaction->rollBack();
                throw $exc;
            }
        }
        return $this->render('create', [
                'model' => $model,
        ]);
    }

    protected function getBarcodes()
    {
        $barcodes = [];
        $query_barcode = (new \yii\db\Query())
            ->select(['barcode' => 'lower(barcode)', 'id' => 'product_id'])
            ->from('{{%product_child}}')
            ->union((new \yii\db\Query())
            ->select(['lower(code)', 'id'])
            ->from('{{%product}}'));
        foreach ($query_barcode->all() as $row) {
            $barcodes[$row['barcode']] = $row['id'];
        }
        return $barcodes;
    }

    /**
     * Updates an existing StockOpname model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing StockOpname model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Confirm an existing StockOpname model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionConfirm($id)
    {
        $model = $this->findModel($id);
        if ($model->status != StockOpname::STATUS_DRAFT) {
            throw new UserException('Tidak bisa diconfirm');
        }

        $model->status = StockOpname::STATUS_RELEASED;
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
     * Confirm an existing StockOpname model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCancel($id)
    {
        $model = $this->findModel($id);
        if ($model->status != StockOpname::STATUS_RELEASED) {
            throw new UserException('Tidak bisa dicancel');
        }

        $model->status = StockOpname::STATUS_CANCELED;
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
     * Finds the StockOpname model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StockOpname the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StockOpname::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
