<?php

namespace backend\controllers\master;

use Yii;
use app\models\master\U2Branch;
use app\models\master\search\U2Branch as U2BranchSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * U2BranchController implements the CRUD actions for U2Branch model.
 */
class U2BranchController extends Controller
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
     * Lists all U2Branch models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new U2BranchSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single U2Branch model.
     * @param integer $branch_id
     * @param integer $user_id
     * @return mixed
     */
    public function actionView($branch_id, $user_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($branch_id, $user_id),
        ]);
    }

    /**
     * Creates a new U2Branch model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new U2Branch();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'branch_id' => $model->branch_id, 'user_id' => $model->user_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing U2Branch model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $branch_id
     * @param integer $user_id
     * @return mixed
     */
    public function actionUpdate($branch_id, $user_id)
    {
        $model = $this->findModel($branch_id, $user_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'branch_id' => $model->branch_id, 'user_id' => $model->user_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing U2Branch model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $branch_id
     * @param integer $user_id
     * @return mixed
     */
    public function actionDelete($branch_id, $user_id)
    {
        $this->findModel($branch_id, $user_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the U2Branch model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $branch_id
     * @param integer $user_id
     * @return U2Branch the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($branch_id, $user_id)
    {
        if (($model = U2Branch::findOne(['branch_id' => $branch_id, 'user_id' => $user_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
