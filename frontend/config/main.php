<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'user' => 
		[
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => 
		[
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => 
		[
            'errorAction' => 'site/error',
        ],
		'urlManager' => 
		[
             'enablePrettyUrl' => true,
             'showScriptName' => false,
			 //'enableStrictParsing' => true,
            /* 'rules'=>
			 [
                 '<controller:\w+>/<id:\d+>' => '<controller>/view',
                 '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                 '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                 'module/<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',                
                 'site/page/view/<view:\w+>' => 'site/page',  
             ],*/
         ],

    ],
    'params' => $params,
];
