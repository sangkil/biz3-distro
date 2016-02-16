<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'common\classes\DbCache',
        ],
        'session' => [
            'class' => 'common\classes\DbSession',
        ],
    ],
];
