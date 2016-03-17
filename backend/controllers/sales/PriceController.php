<?php

namespace backend\controllers\sales;

use Yii;
use backend\models\sales\Price;
use backend\models\sales\search\Price as PriceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PriceController implements the CRUD actions for Price model.
 */
class PriceController extends Controller
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
     * Lists all Price models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PriceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Price model.
     * @param integer $product_id
     * @param integer $price_category_id
     * @return mixed
     */
    public function actionView($product_id, $price_category_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($product_id, $price_category_id),
        ]);
    }

    /**
     * Creates a new Price model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Price();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'product_id' => $model->product_id, 'price_category_id' => $model->price_category_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Price model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $product_id
     * @param integer $price_category_id
     * @return mixed
     */
    public function actionUpdate($product_id, $price_category_id)
    {
        $model = $this->findModel($product_id, $price_category_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'product_id' => $model->product_id, 'price_category_id' => $model->price_category_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Price model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $product_id
     * @param integer $price_category_id
     * @return mixed
     */
    public function actionDelete($product_id, $price_category_id)
    {
        $this->findModel($product_id, $price_category_id)->delete();

        return $this->redirect(['index']);
    }

        /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionCsvDownload()
    {
        $searchModel = new PriceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams['params']);

        $dataProvider->pagination = false;

        header('Content-Type: application/excel');
        header('Content-Disposition: attachment; filename="product_price.csv"');

        $fp = fopen('php://output', 'w');
        $i =1;
        foreach ($dataProvider->models as $row) {
            fputcsv($fp, [$i, $row->product->code,$row->product->name,$row->price],chr(9));
            $i++;
        }
        fclose($fp);
        return false;
    }

    /**
     * Finds the Price model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $product_id
     * @param integer $price_category_id
     * @return Price the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($product_id, $price_category_id)
    {
        if (($model = Price::findOne(['product_id' => $product_id, 'price_category_id' => $price_category_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
