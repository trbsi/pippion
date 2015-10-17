<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
	'defaultRoute' => 'breeder/profile',
	'language' => 'en',
	'name'=>'Pippion',

	'modules' => 
	[
		'club' => 
		[
            'class' => 'backend\modules\club\Club',
        ],
        'pigeon-image' => 
		[
            'class' => 'backend\modules\pigeon_image\PigeonImage',
        ],
		'messages' => 
		[
            'class' => 'backend\modules\messages\Messages',
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
    'components' => 
	[
		//override yii2-user view files
		'view'=>
		[
			'theme'=>
			[
				'pathMap'=>
				[
					'@dektrium/user/views'=>'@backend/user/views',
				],
			],
		],
		//https://github.com/yiisoft/yii2/issues/974 		
		//this can be helpful -> http://www.yiiframework.com/wiki/628/override-eliminate-bootstrap-css-js-for-yii-2-0-widgets/
		//don't use yii2's bootstrap css file
		'assetManager' => 
		[
			'bundles' => 
			[
				'yii\bootstrap\BootstrapAsset' => 
				[
					 'css' => [],
					 'js'=>[]
				],
				'yii\web\JqueryAsset'=>
				[
					'js'=>[],
				],
				//http://stackoverflow.com/questions/26734385/yii2-disable-bootstrap-js-jquery-and-css
				'yii\bootstrap\BootstrapPluginAsset'=>
				[
					'js'=>[],
				],
			],
		],  
		//http://www.yiiframework.com/doc-2.0/guide-tutorial-i18n.html
		'i18n' => 
		[
			'translations' => 
			[
				'default*' => 
				[
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => '@backend/messages',
					//'sourceLanguage' => 'hr',
					/*'fileMap' => 
					[
						'default' => 'default.php',
						'app/error' => 'error.php',
					],*/
				],
			],
		],
        'user' => 
		[
           //'identityClass' => 'common\models\User',
           	//'enableAutoLogin' => true,
			'loginUrl'=>['site/what-is-pippion'], //https://github.com/dektrium/yii2-user/issues/289
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
             'rules'=>
			 [
			 	//ovdje je pravilo kako url izgleda kad se utipka u browser => ovdje je na što yii treba otići
				// pigeon/1042 => ide na pigeon/view s tim 1042
                
				//club rules
				'club/<club_page:\w+>' => 'club/club/view',//access homepage of club
				'club/results/<club_page:\w+>'=>'/club/club-results/index',//access club's results
                'club/<controller:[\w\-]+>/<action:[\w\-]+>/<club_page:\w+>' => 'club/<controller>/<action>', //access other parts of club page (\w+-+\w+ or .* to match controller with dashes)
				 
				 //default rules
			 	'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>', 
             ],
         ],
		 
		 'authClientCollection' => 
		 [
            'class' => \yii\authclient\Collection::className(),
            'clients' => 
			[
               /* 'google' => 
				[
                   'class' => 'dektrium\user\clients\Google',
                   'clientId' => '778847425052-gugn2a3vr4ghip3edr4m3b4it8hnchbo.apps.googleusercontent.com',
                   'clientSecret' => 'uaq8OPZi_8NJsq_HROJczFV1',
				   'viewOptions' => ['popupWidth' => 900, 'popupHeight' => 550,],   
                ],*/
                'facebook' => 
				[
                    'class' => 'dektrium\user\clients\Facebook',
                    'clientId' => '375971292568983',
                    'clientSecret' => 'fce840fc13c189fcce884b7c530db98e',
					'viewOptions' => ['popupWidth' => 900, 'popupHeight' => 550,],
                ],
               /* 'twitter' => [
                   'class' => 'yii\authclient\clients\Twitter',
                   'consumerKey' => 'twitter_consumer_key',
                   'consumerSecret' => 'twitter_consumer_secret',
               ],*/
            ],
        ],
    ],
    'params' => $params,
];
