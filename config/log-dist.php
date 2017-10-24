<?php
// use yii migrate --migrationPath=@yii/log/migrations/ to migrate log tables

return [
    'traceLevel' => YII_DEBUG ? 3 : 0,
    'targets' => [
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['error', 'warning'],
            'logVars' => ['_GET', '_POST', '_COOKIE', '_SESSION', '_SERVER'],
            'maxFileSize' => 20480,
            'maxLogFiles' => 100,
            'rotateByCopy' => false
        ],
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['info'],
            'except' => [
                'yii\web\Session*',
                'yii\db\Command*',
                'yii\db\Connection*'
            ],
            'logVars' => [],
            'maxFileSize' => 20480,
            'maxLogFiles' => 100,
            'rotateByCopy' => false
        ],
        'auditlog' => [
            'class' => 'yii\log\DbTarget',
            'categories' => [
                'application',
                'user*', // user.login, user.logout, ...
                'admin*', 
            ],
            'logTable' => 'audit_log',
            'logVars' => [],
        ],
    ],
];
