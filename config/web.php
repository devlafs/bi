<?php

use kartik\mpdf\Pdf;

$params = require __DIR__ . '/params.php';
require(__DIR__ . '/config-local.php');
$mainDb = require __DIR__ . '/mainDb.php';
$beeDb = require __DIR__ . '/beeDb.php';
$userDb = require __DIR__ . '/userDb.php';

$config = [
    'id' => 'bi-bpone',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'pt-BR',
    'timeZone' => 'America/Sao_Paulo',
    'aliases' =>
    [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@console' =>  dirname(dirname(__DIR__)) . '/console',
    ],
    'modules' =>
    [
        'user' =>
        [
            'class' => 'app\modules\user\Module',
        ],
        'gridview' =>
        [
            'class' => '\kartik\grid\Module'
        ],
    ],
    'components' =>
    [
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
//                    'basePath' => '@app/messages',
//                    'sourceLanguage' => 'pt-BR',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
        'ldap' => [
            'class' => 'kosoukhov\ldap\Connector',
            'useCache' => true,
            'host' => '179.185.109.77',
            'port' => '389',
            'baseDN' => 'DC=odilonsantos,DC=local',
//            'userDN' => '@....corp.net',
//            'groupDN' => '',
            //Input your AD login/pass on dev or sys login/pass on test/prod servers
            'sysUserLogin' => 'BeeBP1@odilonsantos.local',
            'sysUserPassword' => 'fN9K36XJRxNC',
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'defaultTimeZone' => 'America/Sao_Paulo',
        ],
        'pdf' => [
            'class' => Pdf::classname(),
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_DOWNLOAD,
        ],
        'permissaoGeral' =>
        [
            'class' => 'app\components\PermissaoGeral',
        ],
        'permissaoConsulta' =>
        [
            'class' => 'app\components\PermissaoConsulta',
        ],
        'permissaoPainel' =>
        [
            'class' => 'app\components\PermissaoPainel',
        ],
        'assetManager' =>
        [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => []
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => []
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => []
                ]
            ]
        ],
        'request' =>
        [
            'cookieValidationKey' => 'mlKdROYmMhYA_57c3jx2D3SkysNb7Vm4',
        ],
        'cache' =>
        [
            'class' => 'yii\caching\DbCache',
            'cacheTable' => 'bpbi_cache'
        ],
        'user' =>
        [
            'loginUrl' => '/user/login',
            'class' => 'app\modules\user\components\User',
            'identityClass' => 'app\models\AdminUsuario',
            'enableAutoLogin' => false,
            'enableSession' => true,
            'identityCookie' =>
            [
                'name' => '_bpbiUser'
            ]
        ],
        'session' =>
        [
            'class' => 'yii\web\Session',
            'cookieParams' => ['httponly' => true, 'lifetime' => 3600 * 4],
            'timeout' => 3600 * 4,
            'useCookies' => true,
        ],
        'errorHandler' =>
        [
            'errorAction' => 'site/error',
        ],
        'mailer' =>
        [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' =>
            [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtplw.com.br',
                'username' => 'bpone',
                'password' => 'YP1@01lOe1d2QD',
                'port' => '587',
                'encryption' => 'tls',
            ],
        ],
        'log' =>
        [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' =>
            [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $mainDb,
        'dbBee' => $beeDb,
        'userDb' => $userDb,
        'urlManager' =>
        [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' =>
            [
                'consulta/visualizar/<id:\d+>' => 'consulta/visualizar',
                'consulta/alterar/<id:\d+>/<sqlMode:\w+>' => 'consulta/alterar',
                'relatorio-data/visualizar/<id:\d+>' => 'relatorio-data/visualizar',
                'relatorio-data/alterar/<id:\d+>/<sqlMode:\w+>' => 'relatorio-data/alterar',
                'share/v/<token:\w+>' => 'share/v',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ]
        ],
    ],
    'params' => $params,
];

if (false) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
                'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
                'class' => 'yii\gii\Module',
                'allowedIPs' => ['*'],
    ];
}

return $config;
