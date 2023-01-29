<?php

$params = require __DIR__ . '/params.php';

if (file_exists(__DIR__ . '/../../db.php')) {
    $db = require(__DIR__ . '/../../db.php');
} else {
    $db = require __DIR__ . '/db.php';
}

$config = [
    'id' => 'basic',
    'language' => 'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'timeZone' => 'Europe/Moscow',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'session' => [
            'class' => 'yii\web\DbSession',
            'timeout'=>10*365*24*60*60,
            'writeCallback' => function () {
                return ['user_id' => Yii::$app->user->id];
            },
        ],
        'assetManager' => [
            'bundles' => [
                'kartik\form\ActiveFormAsset' => [
                    'bsDependencyEnabled' => false // do not load bootstrap assets for a specific asset bundle
                ],
            ],
        ],
        'formatter' => [
            'defaultTimeZone'=>'Europe/Moscow',
            'timeZone' => 'Europe/Moscow',
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'd MMMM yyyy',
        ],
        'request' => [
            'cookieValidationKey' => 'ibdjQ10rANz9_rz7POdewriyweufsdQWEqsdQWdQWdQW',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'authTimeout'=> 60 * 60 * 24 * 200,
            'loginUrl' => ['user/login'],
            'identityCookie' => ['name' => '_identity-auth', 'httpOnly' => true],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/mail',
            //'useFileTransport' => false, //посылать почту
            'useFileTransport' => true, //не посылать почту
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
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'site/index',
                'user/update/<id:\d+>' => 'user/update',
                'user/out_in_arhive/<id:\d+>' => 'user/out_in_arhive',
                'role/update/<id:\d+>' => 'role/update',
                'bin_big/edit_row/<id:\d+>' => 'bin_big/edit_row',
                'reqout_account/get_source_big_acc_list_ajax/<type:\d+>' => 'reqout_account/get_source_big_acc_list_ajax',
                'reqout_account_command/get_source_big_acc_list_ajax/<type:\d+>' => 'reqout_account_command/get_source_big_acc_list_ajax',
                //'<controller:[\w\-]+>/<action:[\w\-]+>' => 'site/index'
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module'
    ];
}

return $config;
