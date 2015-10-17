<?php

namespace backend\user\controllers;

use dektrium\user\controllers\SecurityController as BaseSecurityController;

use dektrium\user\Finder;
use dektrium\user\models\Account;
use dektrium\user\models\LoginForm;
use dektrium\user\models\User;
use dektrium\user\Module;
use dektrium\user\traits\AjaxValidationTrait;
use Yii;
use yii\authclient\AuthAction;
use yii\authclient\ClientInterface;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;


use backend\helpers\ExtraFunctions;
use backend\models\Breeder;

class SecurityController extends BaseSecurityController
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
     * Tries to authenticate user via social network. If user has already used
     * this network's account, he will be logged in. Otherwise, it will try
     * to create new user account.
     *
     * @param ClientInterface $client
     */
    public function authenticate(ClientInterface $client)
    {
        $account = $this->finder->findAccount()->byClient($client)->one();

        if ($account === null) {
            //$account = Account::create($client);
			
			//IZMJENA START
			//don't allow user to create accoun with facebook because name of breeder and username are messed up
			$this->redirect(["/site/auth-connect"]);
			//IZMJENA END
        }

        if ($account->user instanceof User) 
		{
            if ($account->user->isBlocked) 
			{
                Yii::$app->session->setFlash('danger', Yii::t('user', 'Your account has been blocked.'));
                $this->action->successUrl = Url::to(['/user/security/login']);
            } 
			else 
			{
				//IZMJENA START
				$info=Breeder::findUserById($account->user_id);
				ExtraFunctions::setCookie(\Yii::$app->params['username_cookie'], $info->username);
				
				$Breeder=Breeder::findBreederProfile($account->user_id);
				ExtraFunctions::setCookie(\Yii::$app->params['name_of_breeder_cookie'], $Breeder->name_of_breeder);
				//IZMJENA END
				
                Yii::$app->user->login($account->user, $this->module->rememberFor);
                $this->action->successUrl = Yii::$app->getUser()->getReturnUrl();
            }
        } 
		else 
		{
            $this->action->successUrl = $account->getConnectUrl();
        }
    }
			
	/**
     * Displays the login page.
     *
     * @return string|\yii\web\Response
     */
    public function actionLogin()
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
		
      	 if (!\Yii::$app->user->isGuest) {
            $this->goHome();
        }

        $model = \Yii::createObject(LoginForm::className());

        $this->performAjaxValidation($model);

        if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) 
		{
			//IZMJENA START
			$info=Breeder::findUserById(Yii::$app->user->getId());
			ExtraFunctions::setCookie(\Yii::$app->params['username_cookie'], $info->username);
			
			$Breeder=Breeder::findBreederProfile(Yii::$app->user->getId());
			ExtraFunctions::setCookie(\Yii::$app->params['name_of_breeder_cookie'], $Breeder->name_of_breeder);
			//IZMJENA END
            return $this->goBack();
        }

        return $this->render('login', [
            'model'  => $model,
            'module' => $this->module,
        ]);
    }


    /**
     * Logs the user out and then redirects to the homepage.
     * @return Response
     */
    public function actionLogout()
    {
	 	//IZMJENA START
        foreach ($_COOKIE as $c_id => $c_value)
        {
            setcookie($c_id, NULL, 1, "/");
        }
		//IZMJENA END
		
		Yii::$app->getUser()->logout();
        return $this->goHome();
    }

}//END CLASS
?>