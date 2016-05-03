<?php

namespace backend\controllers\master;

use Yii;
use backend\models\master\Product;
use backend\models\master\search\Product as ProductSearch;
use backend\models\master\ProductUom;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
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
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionCsvDownload()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams['params']);
        $dataProvider->pagination = false;

        header('Content-Type: application/excel');
        header('Content-Disposition: attachment; filename="product.csv"');        

        $fp = fopen('php://output', 'w');
        $i =1;
        foreach ($dataProvider->models as $row) {            
            fputcsv($fp, [$i, $row->code,$row->name,$row->group->name, $row->category->name],chr(9));
            //fputcsv($fp, [$i, $row->code, str_replace(';', '-', $row->name),$row->group->name, $row->category->name],';');
            $i++;
        }
        fclose($fp);
        return false;
    }

    /**
     * Displays a single Product model.
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
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();
        $dPost = Yii::$app->request->post();
        if ($model->load($dPost)) {
            $conn = \Yii::$app->db->beginTransaction();
            try {
                $allSaved = true;
                if (!$model->save()) {
                    $allSaved = false;
                } else {

                    foreach (Yii::$app->request->post('prodUom', []) as $row) {
                        $modelUom = new \backend\models\master\ProductUom();
                        $modelUom->product_id = $model->id;
                        $modelUom->uom_id = $row['id_uom'];
                        $modelUom->isi = $row['isi'];
                        if (!$modelUom->save()) {
                            $allSaved = false;
                        }
                    }
                    foreach (Yii::$app->request->post('prodBcode', []) as $row) {
                        $modelChild = new \backend\models\master\ProductChild();
                        $modelChild->product_id = $model->id;
                        $modelChild->barcode = $row['barcode'];
                        if (!$modelChild->save()) {
                            $allSaved = false;
                        }
                    }
                }
                if ($allSaved) {
                    $conn->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } catch (Exception $ex) {
                $conn->rollBack();
            }
        } else {
            return $this->render('create', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $dPost = Yii::$app->request->post();
        if ($model->load($dPost)) {
            $old_proUom = [];
            $old_proChild = [];
            $new_proUom = [];
            $new_proChild = [];

            foreach ($model->productUoms as $proU) {
                $old_proUom[] = $proU->product_id . '-' . $proU->uom_id;
            }
            foreach ($model->productChildren as $proC) {
                $old_proChild[] = $proC->product_id . '-' . $proC->barcode;
            }
            $conn = \Yii::$app->db->beginTransaction();
            try {
                $allSaved = true;
                if (!$model->save()) {
                    $allSaved = false;
                } else {
                    if (isset($dPost['prodUom'])) {
                        foreach ($dPost['prodUom'] as $row) {
                            $new_proUom[] = $combine = $model->id . '-' . $row['id_uom'];
                            $is_new = (!in_array($combine, $old_proUom)) ? true : false;
                            if ($is_new) {
                                $modelUom = new \backend\models\master\ProductUom();
                                $modelUom->product_id = $model->id;
                                $modelUom->uom_id = $row['id_uom'];
                            } else {
                                $modelUom = $this->findProUom($model->id, $row['id_uom']);
                            }
                            $modelUom->isi = $row['isi'];
                            if (!$modelUom->save()) {
                                $allSaved = false;
                            }
                        }
                    }

                    if (isset($dPost['prodBcode'])) {
                        foreach ($dPost['prodBcode'] as $prow) {
                            $new_proChild[] = $combine2 = $model->id . '-' . $prow['barcode'];
                            $is_new = (!in_array($combine2, $old_proChild)) ? true : false;
                            if ($is_new) {
                                $modelChild = new \backend\models\master\ProductChild();
                                $modelChild->product_id = $model->id;
                                echo 'new?';
                            } else {
                                $modelChild = $this->findProChild($model->id, $prow['barcode']);
                                echo 'old..';
                            }
                            $modelChild->barcode = $prow['barcode'];
                            if (!$modelChild->save()) {
                                $allSaved = false;
                                print_r($modelChild->getErrors());
                            }
                        }
                    }

                    foreach ($old_proUom as $orow) {
                        $is_del = (!in_array($orow, $new_proUom)) ? true : false;
                        if ($is_del) {
                            $pisah = explode('-', $orow);
                            $this->findProUom($pisah[0], $pisah[1])->delete();
                        }
                    }

                    foreach ($old_proChild as $prow) {
                        $is_del = (!in_array($prow, $new_proChild)) ? true : false;
                        if ($is_del) {
                            $pisah = explode('-', $prow);
                            $todel = $this->findProChild($pisah[0], $pisah[1]);
                            if (!$todel->delete()) {
                                print_r($todel->getErrors());
                            }
                        }
                    }
                }
                if ($allSaved) {
//                    print_r($new_proChild);
//                    echo '--------------';
//                    print_r($old_proChild);
                    $conn->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } catch (Exception $ex) {
                $conn->rollBack();
            }
        }
        return $this->render('create', [
                'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Product model.
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
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findProUom($product_id, $uom_id)
    {
        if (($model = ProductUom::findOne(['product_id' => $product_id, 'uom_id' => $uom_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findProChild($product_id, $barcode)
    {
        if (($model = \backend\models\master\ProductChild::findOne(['product_id' => $product_id, 'barcode' => $barcode]))
            !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
