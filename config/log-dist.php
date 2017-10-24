<?php
return [
    'traceLevel' => YII_DEBUG ? 3 : 0,
    'targets' => [
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['error', 'warning'],
            'logVars' => [],
            'maxFileSize' => 20480,
            'maxLogFiles' => 100,
            'rotateByCopy' => false
        ],
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['info'],
//            'categories' => ['app\*', 'console\*'],
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
    ],
];
