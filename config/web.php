<?php

use app\services\DiscountService;
use app\services\DiscountServiceInterface;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'flushInterval' => 1,
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'profile'],
                    'logVars' => [],
                    'exportInterval' => 1,
                    'fileMode' => 0777
                ]
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                'POST generate' => 'discounts/generate',
                'POST apply' => 'discounts/apply',
//                [
//                    'class' => 'yii\rest\UrlRule',
//                    'controller' => ['user'],
//                    'only' => ['options', 'greet', 'index'],
////                    'except' => ['delete', 'update', 'create', 'index', 'view'],
//                    'patterns' => [
//                        'GET greet' => 'greet',
//                        'OPTIONS' => 'options',
//                        'GET,HEAD' => 'index',
//                        '' => 'options',
//                    ]
//                ],
            ],
        ],
    ],
    'params' => $params,
    'container' => [
        'definitions' => [
            DiscountServiceInterface::class => DiscountService::class
        ],
    ],
];

return $config;
