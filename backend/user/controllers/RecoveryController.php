<?php

namespace backend\user\controllers;

use dektrium\user\controllers\RecoveryController as BaseRecoveryController;
use backend\helpers\ExtraFunctions;

class RecoveryController extends BaseRecoveryController
{
	
	//IZMJENA START
	/*
	* Add new theme for login
	* http://www.yiiframework.com/forum/index.php/topic/56890-solved-yii-2-module-theme/
	*/
	public function beforeAction($action)
	{
		$x=new ExtraFunctions;
		$x->beforeActionTimeLanguage();
		return parent::beforeAction($action);
	}
	//IZMJENA END
	
	
    /**
     * Displays page where user can request new recovery message.
     * /user/forgot
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionRequest()
    {
		//IZMJENA START
		// custom initialization code:
		//http://www.yiiframework.com/forum/index.php/topic/56890-solved-yii-2-module-theme/
		 \Yii::$app->view->theme = new \yii\base\Theme([
				'pathMap' => 
				[
					'@backend/views' => '@backend/themes/login/views',
					'@dektrium/user/views'=>'@backend/user/views',
				],
				//'baseUrl' => '@web/login/admin',
			]);
		//IZMJENA END

		return parent::actionRequest();
    }
	
	    /**
     * Displays page where user can reset password.
     * @param  integer $id
     * @param  string  $code
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionReset($id, $code)
    {
		//IZMJENA START
		// custom initialization code:
		//http://www.yiiframework.com/forum/index.php/topic/56890-solved-yii-2-module-theme/
		 \Yii::$app->view->theme = new \yii\base\Theme([
				'pathMap' => 
				[
					'@backend/views' => '@backend/themes/login/views',
					'@dektrium/user/views'=>'@backend/user/views',
				],
				//'baseUrl' => '@web/login/admin',
			]);
		//IZMJENA END

		return parent::actionReset($id, $code);
	}
}
?>