<?php

namespace backend\controllers\inventory;

use Yii;
use backend\models\inventory\StockOpname;
use backend\models\inventory\search\StockOpname as StockOpnameSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;

/**
 * StockOpnameController implements the CRUD actions for StockOpname model.
 */
class StockOpnameController extends Controller {

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
     * Lists all StockOpname models.
     * @return mixed
     */
    public function actionIndex() {
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
    public function actionView($id) {
        $model = $this->findModel($id);
        $query = (new \yii\db\Query())
                ->select(['p.id', 'p.code', 'p.name', 'o_qty' => 'COALESCE(o.qty,0)', 's_qty' => 'COALESCE(s.qty,0)',
                    'selisih' => 'COALESCE(o.qty,0)-COALESCE(s.qty,0)'])
                ->from(['p' => '{{%product}}'])
                ->innerJoin(['s' => '{{%product_stock}}'], '[[s.product_id]]=[[p.id]] and [[s.warehouse_id]]=:whse', [':whse' => $model->warehouse_id])
                ->leftJoin(['o' => '{{%stock_opname_dtl}}'], '[[o.product_id]]=[[p.id]] and [[o.opname_id]]=:opid', [':opid' => $model->id])
                ->orderBy(['abs(COALESCE(o.qty,0)-COALESCE(s.qty,0))' => SORT_DESC, 'COALESCE(o.qty,0)' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pagesize' => 50]
        ]);

        return $this->render('view', [
                    'model' => $model, 'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Creates a new StockOpname model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
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
                        //no change
                        $content = file_get_contents($model->file->tempName);
                        $isfirst = true;
                        $ddd = 0;
                        foreach (explode("\n", $content) as $row) {
                            if ($isfirst) {
                                $isfirst = false;
                                continue;
                            }
                            $sparated_row = (strpos($row, ',')) ? explode(',', $row) : explode(chr(9), $row);
                            if (isset($barcodes[strtolower(trim($sparated_row[0]))]) && null !== trim($sparated_row[1])) {
                                $product_id = $barcodes[strtolower(trim($sparated_row[0]))];
                                $sparated_row = explode(chr(9), $row);

                                //trimming barcode
                                if (isset($barcodes[strtolower(trim($sparated_row[0]))])) {
                                    $product_id = $barcodes[strtolower(trim($sparated_row[0]))];
                                    if (isset($barcodes[strtolower(trim($sparated_row[0]))])) {
                                        $product_id = $barcodes[strtolower(trim($sparated_row[0]))];
                                        $stock[$product_id] = trim($sparated_row[1]);
                                    }
                                    echo trim($sparated_row[0]) . ', ' . $product_id . ', ' . $stock[$product_id];
                                    echo "<br>";
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

                                if ($ddd > 100) {
                                    break;
                                }
                                $ddd++;
                            }
                        }
                    }
//                    $transaction->commit();
//                    return $this->redirect(['view', 'id' => $model->id]);
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

    protected function getBarcodes() {
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
    public function actionUpdate($id) {
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
    public function actionDelete($id) {
        $model = $this->findModel($id);
        if ($model->status != StockOpname::STATUS_DRAFT) {
            throw new NotFoundHttpException('Tidak bisa didelete');
        }
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Confirm an existing StockOpname model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionConfirm($id) {
        $model = $this->findModel($id);
        if ($model->status != StockOpname::STATUS_DRAFT) {
            throw new UserException('Tidak bisa diconfirm');
        }

        $model->status = StockOpname::STATUS_RELEASED;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            //Insert or update opname history
            $opname_sql = 'INSERT INTO stock_opname_check(opname_id, date, product_id, uom_id, qty, created_at, created_by)
    SELECT :opname_id, :ddate, "p"."id", 1 "id_uom", COALESCE(s.qty,0) AS "s_qty" , :created_at, :created_by
    FROM "product" "p" 
    INNER JOIN "product_stock" "s" ON "s"."product_id"="p"."id" and "s"."warehouse_id"=:whse 
    LEFT JOIN "stock_opname_dtl" "o" ON "o"."product_id"="p"."id" and "o"."opname_id"=:opname_id';

            $qexist = (new \yii\db\Query())
                    ->from('stock_opname_check')
                    ->where('opname_id=:opname_id and date=:ddate', [':opname_id' => $model->id, ':ddate' => date('Y-m-d')]);

            if ($qexist->exists()) {
                $del_opname = 'delete from stock_opname_check where opname_id=:opname_id and date=:ddate';
                $del_record = \Yii::$app->db->createCommand($del_opname, [':opname_id' => $model->id, ':ddate' => date('Y-m-d')]);
                $del_record->execute();
            }
            $skrg = new \DateTime();
            $opname_record = \Yii::$app->db->createCommand($opname_sql, [':opname_id' => $model->id, ':ddate' => date('Y-m-d'), ':whse' => $model->warehouse_id, ':created_at' => $skrg->getTimestamp(), ':created_by' => \Yii::$app->user->id]);
            $opname_record->execute();

            // update stock internaly via beforeUpdate
            if ($model->save()) {
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                print_r($model->firstErrors);
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
    public function actionCancel($id) {
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

    public function actionCsvTemplate() {
        $rows = [];
        $rows[] = implode(chr(9), ['Barcode', 'Qty']); // header

        return Yii::$app->getResponse()->sendContentAsFile(implode("\n", $rows), 'opname template.csv', [
                    'mimeType' => 'application/excel'
        ]);
    }

    /**
     * Finds the StockOpname model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StockOpname the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = StockOpname::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
