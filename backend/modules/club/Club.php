<?php

namespace backend\modules\club;

class Club extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\club\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
		//\Yii::$app->view->theme->baseUrl = '@web/themes/admin';
		/*\Yii::$app->view->theme = new \yii\base\Theme(
		[
			'pathMap' => 
			[
				'@backend/views/layouts' => '@backend/modules/club/views/layouts',
			],
			//'baseUrl' => '@web/themes/admin',
		]);*/
    }
}
