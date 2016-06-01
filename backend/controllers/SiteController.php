<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use backend\models\sales\search\Sales as SalesSearch;

/**
 * Site controller
 */
class SiteController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'change-branch' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'page' => [
                'class' => 'yii\web\ViewAction'
            ]
        ];
    }

    public function actionIndex() {
        return $this->actionDashboard();
        //return $this->render('index');
    }

    public function actionLogin() {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionChangeBranch() {
        Yii::$app->getResponse()->format = 'json';
        $branch = Yii::$app->getRequest()->post('branch');
        Yii::$app->profile->branch_id = $branch;
        return true;
    }

    public function actionDashboard() {
        $parms = Yii::$app->request->queryParams;
        $mperiode = \backend\models\accounting\AccPeriode::find()->active()->one();
        if ($mperiode == null) {
            throw new NotFoundHttpException('There is no active accounting periode.');
        }

        $searchModel = new SalesSearch();
        $dataProvider = $searchModel->searchByBranch($parms);

        $searchHutang = new \backend\models\accounting\search\Invoice();
        $searchHutang->type = \backend\models\accounting\Invoice::TYPE_INCOMING;
        $hutangPro = $searchHutang->search(Yii::$app->request->queryParams);
        
        $searchTransfer = new \backend\models\inventory\search\Transfer();
        $transfPro = $searchTransfer->search(Yii::$app->request->queryParams);

        $datavar = ['dataProvider' => $dataProvider,
            'mperiode' => $mperiode->name, 'hutangPro' => $hutangPro,
            'transfPro' => $transfPro
        ];

        return $this->render('dashboard', $datavar);
    }

}
