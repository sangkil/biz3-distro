<?php

namespace backend\controllers\inventory;

use Yii;
use backend\models\inventory\ProductStock;
use backend\models\inventory\search\ProductStock as ProductStockSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductStockController implements the CRUD actions for ProductStock model.
 */
class ProductStockController extends Controller {

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
     * Lists all ProductStock models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ProductStockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort(['defaultOrder' => ['product.edition' => SORT_DESC]]);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all ProductStock Group By Artikel models.
     * @return mixed
     */
    public function actionByArtikel() {
        $searchModel = new ProductStockSearch();
        $dataProvider = $searchModel->artikel_grouped(Yii::$app->request->queryParams);

        return $this->render('by-artikel', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProductStock model.
     * @param integer $warehouse_id
     * @param integer $product_id
     * @return mixed
     */
    public function actionView($warehouse_id, $product_id) {
        return $this->render('view', [
                    'model' => $this->findModel($warehouse_id, $product_id),
        ]);
    }

    /**
     * Creates a new ProductStock model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new ProductStock();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'warehouse_id' => $model->warehouse_id, 'product_id' => $model->product_id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ProductStock model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $warehouse_id
     * @param integer $product_id
     * @return mixed
     */
    public function actionUpdate($warehouse_id, $product_id) {
        $model = $this->findModel($warehouse_id, $product_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'warehouse_id' => $model->warehouse_id, 'product_id' => $model->product_id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ProductStock model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $warehouse_id
     * @param integer $product_id
     * @return mixed
     */
    public function actionDelete($warehouse_id, $product_id) {
        $this->findModel($warehouse_id, $product_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ProductStock model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $warehouse_id
     * @param integer $product_id
     * @return ProductStock the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($warehouse_id, $product_id) {
        if (($model = ProductStock::findOne(['warehouse_id' => $warehouse_id, 'product_id' => $product_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionCsvDownload() {
        $searchModel = new ProductStockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams['params']);
        $dataProvider->pagination = false;
        $filename = 'Product Stock - ' . date('dmY');
        $headerTitle = ['WAREHOUSE', 'KODE', 'NAMA_PRODUCT', 'GROUP', 'CATEGORY', 'QTY', 'NILAI_PRODUK'];

        header('Content-Type: application/excel');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');

        $fp = fopen('php://output', 'w');
        $i = 0;
        $is_first = true;
        foreach ($dataProvider->models as $row) {
            if ($is_first) {
                fputcsv($fp, $headerTitle, chr(9));
                $is_first = false;
            }
            $cogs_na = ($row->cogs != null) ? $row->cogs->cogs : 0;
            fputcsv($fp, [$row->warehouse->name, $row->product->code, $row->product->name, $row->product->group->name, $row->product->category->name, $row->qty, ($cogs_na * $row->qty), $cogs_na], chr(9));
            $i++;
        }
//        fclose($fp);
        return false;
    }

    public function actionCsvByArtikel() {
        $searchModel = new ProductStockSearch();
        $dataProvider = $searchModel->artikel_grouped(Yii::$app->request->queryParams['params']);

        $dataProvider->pagination = false;
        $filename = 'Product Stock - ' . date('dmY');
        $headerTitle = ['WAREHOUSE', 'ARTIKEL', 'QTY'];

        header('Content-Type: application/excel');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');

        $fp = fopen('php://output', 'w');
        $i = 0;
        $is_first = true;
        foreach ($dataProvider->models as $row) {
            if ($is_first) {
                fputcsv($fp, $headerTitle, chr(9));
                $is_first = false;
            }
            $cogs_na = ($row->cogs != null) ? $row->cogs->cogs : 0;
            fputcsv($fp, [$row->whse_name, $row->artikel, $row->jml], chr(9));
            $i++;
        }
//        fclose($fp);
        return false;
    }

}
