<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
//        'cache' => [
//            'class' => 'dee\tools\DbCache',
//        ],
        'session' => [
            'class' => 'yii\web\DbSession',
        ],
        'profile' => [
            'class' => 'dee\tools\State'
        ],
        'formatter'=>[
            'class'=>'common\classes\Formatter'
        ]
    ],
];
