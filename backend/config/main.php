<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
        ]
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'response' => [
            'formatters' => [
                'js' => [
                    'class' => 'yii\web\HtmlResponseFormatter',
                    'contentType' => 'text/javascript'
                ]
            ]
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@mdm/admin/views' => '@backend/views/admin',
                ]
            ]
        ],
    ],
    'on beforeAction' => function () {
        $dmesg = \backend\models\master\UserNotification::find();
        $dmesg->where('user_id=:duser AND start_at<=:saiki AND finish_at>=:saiki', [':duser' => \Yii::$app->user->id, ':saiki' => time()]);
        $umesg = [];
        foreach ($dmesg->all() as $mesg) {
            $umesg[] = $mesg->message;
//            echo $mesg->start_at.'<='.time().'<='.$mesg->finish_at;
//            echo '<br>';
        }
        if(!empty($umesg)){
            \Yii::$app->session->setFlash('warning', $umesg);
        }
    },
    'params' => $params,
];
