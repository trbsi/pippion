<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),    
    'bootstrap' => ['log'],
    'modules' => 
	[
        'backend' => [
            'basePath' => '@app/modules/backend',
            'class' => 'api\modules\backend\Module'
        ],
		
 		'user' => 
		[
            'class' => 'dektrium\user\Module',
            'enableConfirmation' => false,
			//'enableEmailReconfirmation'=>true,
            //'confirmWithin' => 21600,
			'rememberFor'=>31536000,
            'cost' => 12,
            'admins' => ['admin'],
			'controllerMap' => 
			[
                'recovery' => 'backend\user\controllers\RecoveryController',
                'registration' => 'backend\user\controllers\RegistrationController',
                'security' => 'backend\user\controllers\SecurityController',
            ],
			'modelMap'=>
			[

				'LoginForm'=>'backend\user\models\LoginForm',
				'RegistrationForm'=>'backend\user\models\RegistrationForm',
				'User'=>'backend\user\models\User',

			],
        ],	
    ],
	
    'components' => [     
	     'request' => [
            'class' => '\yii\web\Request',
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],     
       /* 'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
        ],*/
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            //'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => 
			[
				//http://stackoverflow.com/questions/27167458/cant-use-tockens-and-extrapattern-together-for-rest-services-in-yii2
				[
                    'class' => 'yii\rest\UrlRule', 
                    'controller' =>'backend/user',
					'extraPatterns' => [
						'POST login' => 'login',
						'GET indexx'=>'indexx',
						'GET logout'=>'logout',
						'GET get-breeder-info'=>'get-breeder-info',
						'POST register'=>'register',
					],
				],
				[
                    'class' => 'yii\rest\UrlRule', 
                    'controller' =>'backend/country',
					'extraPatterns' => [
					'GET indexx'=>'indexx',
					],
				],
                    /*'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ]*/

            ],        
        ]
    ],
    'params' => $params,
];



