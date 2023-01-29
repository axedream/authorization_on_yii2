<?php

$params = require __DIR__ . '/params.php';
if (file_exists(__DIR__ . '/../../db.php')) {
    $db = require(__DIR__ . '/../../db.php');
} else {
    $db = require __DIR__ . '/db.php';
}

if (file_exists(__DIR__ . '/../../db2.php')) {
    $db2 = require(__DIR__ . '/../../db2.php');
} else {
    $db2 = require __DIR__ . '/db.php';
}

if (file_exists(__DIR__ . '/../../clickhouse.php')) {
    $clickhouse = require(__DIR__ . '/../../clickhouse.php');
} else {
    $clickhouse = FALSE;
}

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
    ],
    'components' => [
        'user' => null,
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        /*
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\User',
            //'enableAutoLogin' => true,
        ],
        */
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'db2' => $db2,
        'clickhouse' => $clickhouse,
    ],
    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
