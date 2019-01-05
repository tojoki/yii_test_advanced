<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
            'password'=>'momo107'
        ],
        'cache' => [
//            'class' => 'yii\caching\FileCache',
            'class' => 'yii\redis\Cache',
//            'keyPrefix'=>'tojoki'
        ],
    ],
];
