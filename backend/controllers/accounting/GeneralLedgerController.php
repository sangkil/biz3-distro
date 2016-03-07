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
                    'reverse' => ['post']
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
        $model->status = $model::STATUS_RELEASED;
        $model->date = date('Y-m-d');

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->glDetails = Yii::$app->request->post('GlDetail', []);
                if ($model->save()) {
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', $model->number . ' succesfully created');
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

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Creates a new GlHeader model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateByTemplate() {
        $dPost = Yii::$app->request->post();
        $model = new GlHeader();
        $model->reff_type = '0';
        $model->status = $model::STATUS_RELEASED;
        $model->date = date('Y-m-d');
        $dPost = Yii::$app->request->post();

        if ($model->load($dPost)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                //entri details from templates
                $newDtls = [];
                $templates = Yii::$app->request->post('glTemplate', []);
                foreach ($templates as $value) {
                    $dtl_template = \backend\models\accounting\EntriSheet::findOne($value['id']);
                    foreach ($dtl_template->entriSheetDtls as $ddtl) {
                        $ndtl = new \backend\models\accounting\GlDetail();
                        $ndtl->coa_id = $ddtl->coa_id;
                        $ndtl->header_id = null;
                        $ndtl->amount = ($ddtl->dk == $ddtl::DK_CREDIT) ? -1 * $value['amount'] : $value['amount'];
                        $newDtls[] = $ndtl;
                    }
                }
                $model->glDetails = $newDtls;
                if ($model->save()) {
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', $model->number . ' succesfully created');
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

        return $this->render('create-by-template', ['model' => $model]);
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
                    \Yii::$app->getSession()->setFlash('success', $model->number . ' succesfully updated');
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
     * Deletes an existing GlHeader model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionReverse($id) {
        $oldGl = $this->findModel($id);
        $trans = \Yii::$app->db->beginTransaction();
        try {
            $newGl = new GlHeader;
            $newGl->attributes = $oldGl->attributes;
            $newGl->date = date('Y-m-d');
            $newDtls = [];
            foreach ($oldGl->glDetails as $ddtl) {
                $ndtl = new \backend\models\accounting\GlDetail();
                $ndtl->attributes = $ddtl->attributes;
                $ndtl->header_id = null;
                $ndtl->amount = -1 * $ddtl->amount;
                $newDtls[] = $ndtl;
            }
            $newGl->status = $newGl::STATUS_CANCELED;
            $newGl->number = null;
            $newGl->description = 'Reverse of ' . $oldGl->number;
            $newGl->glDetails = $newDtls;
            
            $newGl->created_at = null;
            $newGl->created_by = null;
            $newGl->updated_at = null;
            $newGl->updated_by = null;

            if ($newGl->save()) {
                $oldGl->status = $oldGl::STATUS_CANCELED;
                $oldGl->description = $oldGl->description . ' canceled by ' . $newGl->number;
                if ($oldGl->save()) {
                    $trans->commit();
                    $this->redirect(['index']);
                }
            } else {
                print_r($newGl->getErrors());
                print_r($newGl->getRelatedErrors());
            }
        } catch (Exception $ex) {
            $trans->rollBack();
        }
        return;
        //$this->redirect(['view','id'=>$id]);
    }

    /**
     * Finds the GlHeader model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GlHeader the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = GlHeader::find()->where('id=:did', [':did' => $id])->with(['glDetails', 'glDetails.coa'])->one()) !== null) {
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

    public function actionJournalTemplates($term = '') {
        $response = Yii::$app->response;
        $response->format = 'json';
        $glTemplates = \backend\models\accounting\EntriSheet::find()
                        ->filterWhere(['like', 'lower([[name]])', strtolower($term)])
                        ->orderBy('name ASC')->limit(10);
        return $glTemplates->all();
    }

}
