<?php

namespace backend\controllers\accounting;

use Yii;
use backend\models\accounting\EntriSheet;
use backend\models\accounting\search\EntriSheet as EntriSheetSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\accounting\GlHeader;
use backend\models\accounting\GlDetail;

/**
 * JournalTemplateController implements the CRUD actions for EntriSheet model.
 */
class JournalTemplateController extends Controller {

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
     * Lists all EntriSheet models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new EntriSheetSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EntriSheet model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new EntriSheet model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new EntriSheet();
        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->entriSheetDtls = Yii::$app->request->post('EntriSheetDtl', []);
                if ($model->save()) {
                    $transaction->commit();
                    // \Yii::$app->getSession()->setFlash('success', ' succesfully created');
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    foreach ($model->getErrors() as $dkey => $vald) {
                        if ($vald[0] == 'Related error') {
                            foreach ($model->getRelatedErrors() as $dkey => $valr) {
                                foreach ($valr as $tkey => $valt) {
                                    \Yii::$app->getSession()->setFlash('error', $valt);
                                }
                                break;
                            }
                        } else {
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

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing EntriSheet model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->entriSheetDtls = Yii::$app->request->post('EntriSheetDtl', []);
                if ($model->save()) {
                    $transaction->commit();
                    // \Yii::$app->getSession()->setFlash('success', $model->number . ' succesfully updated');
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    foreach ($model->getErrors() as $dkey => $vald) {
                        if ($vald[0] == 'Related error') {
                            foreach ($model->getRelatedErrors() as $dkey => $valr) {
                                foreach ($valr as $tkey => $valt) {
                                    \Yii::$app->getSession()->setFlash('error', $valt);
                                }
                                break;
                            }
                        } else {
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
     * Deletes an existing EntriSheet model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the EntriSheet model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return EntriSheet the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = EntriSheet::findOne($id)) !== null) {
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

    public function actionTestEntriJournal() {
        $model = new EntriSheet();
        $dPost = Yii::$app->request->post();
        if (!empty($dPost)) {
            $model = EntriSheet::findOne($dPost['EntriSheet']['id']);
            $model->amount = $dPost['EntriSheet']['amount'];

            $newGl = new GlHeader;
            $newGl->reff_type = 0;
            $newGl->reff_id = null;
            $newGl->date = date('Y-m-d');
            $newDtls = [];
            foreach ($model->entriSheetDtls as $ddtl) {
                $ndtl = new \backend\models\accounting\GlDetail();
                $ndtl->coa_id = $ddtl->coa_id;
                $ndtl->header_id = null;
                $ndtl->amount = ($ddtl->dk == $ddtl::DK_CREDIT) ? -1 * $model->amount : $model->amount;
                $newDtls[] = $ndtl;
            }
            $newGl->status = $newGl::STATUS_RELEASED;
            $activePeriode = \backend\models\accounting\AccPeriode::find()->active()->one();
            $newGl->periode_id = $activePeriode->id;
            $newGl->branch_id = 1;
            $newGl->description = $model->name;
            $newGl->glDetails = $newDtls;
            
            if(!$newGl->save()){
//                print_r($newGl->getErrors());
//                print_r($newGl->getRelatedErrors());
                return $this->redirect(['/accounting/general-ledger/view', 'id' => $model->id]);
            }
        }
        return $this->render('test', [
                    'model' => $model,
        ]);
    }

}
