<?php

namespace backend\user\controllers;

use dektrium\user\controllers\RegistrationController as BaseRegistrationController;
use backend\user\models\RegistrationForm;
use backend\helpers\ExtraFunctions;
use Yii;

class RegistrationController extends BaseRegistrationController
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
     * Displays the registration page.
     * After successful registration if enableConfirmation is enabled shows info message otherwise redirects to home page.
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionRegister()
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

        if (!$this->module->enableRegistration) {
            throw new NotFoundHttpException;
        }

        $model = \Yii::createObject(RegistrationForm::className());

        $this->performAjaxValidation($model);

        if ($model->load(\Yii::$app->request->post()) && $model->register()) 
		{
          	return $this->render('/message', [
                'title'  => Yii::t('user', 'Your account has been created'),
                'module' => $this->module,
            ]);
        }

        return $this->render('register', [
            'model'  => $model,
            'module' => $this->module,
        ]);
		
    }

}
?>