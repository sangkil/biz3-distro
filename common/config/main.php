<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'dee\tools\DbCache',
        ],
        'session' => [
            'class' => 'dee\tools\DbSession',
        ],
        'profile' => [
            'class' => 'dee\tools\State'
        ]
    ],
];
