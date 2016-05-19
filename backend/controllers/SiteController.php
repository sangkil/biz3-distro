<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

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
        return $this->render('index');
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
        $mperiode = \backend\models\accounting\AccPeriode::find()->active()->one();
        $sal = \backend\models\accounting\GlDetail::find();
        $sal->select(['sum(amount)']);
        $sal->joinWith(['header']);
        $sal->groupBy(['gl_header.branch_id']);
        $sal->where('gl_header.branch_id=:dbranch AND coa_id = 16', [':dbranch' => \Yii::$app->profile->branch_id]);

        $whse = \backend\models\master\Warehouse::find()->select('id')->assigned()->column();

        $gr = \backend\models\inventory\GoodsMovement::find();
        $gr->select(['warehouse.name as whse_name', 'count(goods_movement.id) as jml']);
        $gr->with(['warehouse']);
        $gr->joinWith(['warehouse']);
        $gr->where('status < :release', [':release' => \backend\models\inventory\GoodsMovement::STATUS_RELEASED]);
        $gr->andFilterWhere(['in', 'goods_movement.warehouse_id', $whse]);
        $gr->groupBy(['warehouse.name']);
        $oreceipt = $gr->all();
        $mreceipt = '';
        foreach ($oreceipt as $key => $value) {
            $split = explode(' ', $value->whse_name);
            $mreceipt .= $split[1] . ': ' . $value->jml . '; ';
        }

        $msales = abs($sal->scalar());
        $mreceipt = $mreceipt;
        $mtransfer = 0;
        $datavar = ['mperiode' => $mperiode->name, 'msales' => $msales, 'mreceipt' => $mreceipt, 'mtransfer' => $mtransfer];
        return $this->render('dashboard', $datavar);
    }

}
