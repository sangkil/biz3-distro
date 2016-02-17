<?php

namespace backend\controllers\accounting;

use Yii;
use backend\models\accounting\AccPeriode;
use backend\models\accounting\search\AccPeriode as AccPeriodeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AccPeriodeController implements the CRUD actions for AccPeriode model.
 */
class AccPeriodeController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                //'close' => ['post'],
                //'unclose' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all AccPeriode models.
     * @return mixed
     */
    public function actionIndex() {
        $dparms = Yii::$app->request->queryParams;
        $searchModel = new AccPeriodeSearch();

        $searchModel->DateFrom = (isset($dparms['AccPeriode']['DateFrom'])) ? $dparms['AccPeriode']['DateFrom'] : date('01-m-Y');
        $searchModel->DateTo = (isset($dparms['AccPeriode']['DateTo'])) ? $dparms['AccPeriode']['DateTo'] : date('t-12-Y', strtotime(date('Y-01-01')));
        $dataProvider = $searchModel->search($dparms);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AccPeriode model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AccPeriode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new AccPeriode();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AccPeriode model.
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
     * Updates an existing AccPeriode model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionClose($id) {
        $model = $this->findModel($id);
        $dPost = Yii::$app->request->post();
        if ($model->load($dPost)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->status = $model::STATUS_CLOSE;
                if ($model->save()) {
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
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                throw $e;
            }
        }
        return $this->render('close', ['model' => $model]);
    }

    /**
     * Updates an existing AccPeriode model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUnclose($id) {
        $model = $this->findModel($id);
        $dPost = Yii::$app->request->post();
        if ($model->load($dPost)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->status = $model::STATUS_OPEN;
                if ($model->save()) {
                    $transaction->commit();
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
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                throw $e;
            }
        }
        return $this->render('close', ['model' => $model]);
    }

    /**
     * Deletes an existing AccPeriode model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AccPeriode model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AccPeriode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = AccPeriode::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
