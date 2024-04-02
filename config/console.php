<?php

$params = require __DIR__ . '/params.php';
require(__DIR__ . '/config-local.php');
$mainDb = require __DIR__ . '/mainDb.php';
$beeDb = require __DIR__ . '/beeDb.php';
$userDb = require __DIR__ . '/userDb.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
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
        'db' => $mainDb,
        'dbBee' => $beeDb,
        'userDb' => $userDb,
    ],
    'params' => $params,
];

return $config;
