<?php

namespace backend\controllers\accounting;

use Yii;
use backend\models\accounting\GlHeader;
use backend\models\accounting\search\GlHeader as GlHeaderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GeneralLedgerController implements the CRUD actions for GlHeader model.
 */
class GeneralLedgerController extends Controller {

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
     * Lists all GlHeader models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new GlHeaderSearch();
        $dataProvider = $searchModel->searchDtl(Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GlHeader model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new GlHeader model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $dPost = Yii::$app->request->post();
        $model = new GlHeader();
        $model->reff_type = '0';
        $model->status = $model::STATUS_DRAFT;
        $model->GlDate = isset($dPost['GlHeader']['GlDate']) ? $dPost['GlHeader']['GlDate'] : date('d-m-Y');

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->glDetails = Yii::$app->request->post('GlDetail', []);
                $model->addError('id','Something error added');
                if ($model->save()) {
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', $model->number.' succesfully created');
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    foreach ($model->getErrors() as $dkey=>$vald) {
                        if($vald[0]=='Related error'){
                            foreach ($model->getRelatedErrors() as $dkey=>$valr) {
                                foreach ($valr as $tkey => $valt) {
                                    \Yii::$app->getSession()->setFlash('error', $valt);
                                }
                                break;
                            }
                        }else{
                            \Yii::$app->getSession()->setFlash('error', $vald[0]);
                            break;
                        }   
                    }
                    $transaction->rollback();
                }
            } catch (Exception $e) {
                $transaction->rollback();
                throw $e;
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Updates an existing GlHeader model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->glDetails = Yii::$app->request->post('GlDetail', []);
                if ($model->save()) {
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', $model->number.' succesfully updated');
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    foreach ($model->getErrors() as $dkey=>$vald) {
                        if($vald[0]=='Related error'){
                            foreach ($model->getRelatedErrors() as $dkey=>$valr) {
                                foreach ($valr as $tkey => $valt) {
                                    \Yii::$app->getSession()->setFlash('error', $valt);
                                }
                                break;
                            }
                        }else{
                            \Yii::$app->getSession()->setFlash('error', $vald[0]);
                            break;
                        }   
                    }
                    $transaction->rollback();
                }
            } catch (Exception $e) {
                $transaction->rollback();
                throw $e;
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing GlHeader model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the GlHeader model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GlHeader the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = GlHeader::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionListCoa($term = '') {
        $response = Yii::$app->response;
        $response->format = 'json';
        $coaList = \backend\models\accounting\Coa::find()
                ->filterWhere(['like', 'lower([[name]])', strtolower($term)])
                ->orFilterWhere(['like', 'lower([[code]])', strtolower($term)])
                ->codeOrdered()->asArray()->limit(10);
        
        return $coaList->all();
    }

}
