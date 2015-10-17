<?php

namespace api\modules\backend\controllers;

use yii\rest\ActiveController;

use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use dektrium\user\models\User;

/**
 * Country Controller API
 *
 * @author Budi Irawan <deerawan@gmail.com>
 */
class CountryController extends ActiveController
{
    public $modelClass = 'api\modules\backend\models\Country';    
	
	//http://stackoverflow.com/questions/25373276/yii2-rest-api-doesnt-return-expected-results
	public function behaviors()
	{
		$behaviors = parent::behaviors();
		$behaviors['authenticator'] = [
			'class' => HttpBasicAuth::className(),
			'auth' => function ($username, $password) {
				// Return Identity object or null
				return User::findOne([
					'username' => $username,
					'password' => $password
				]);
			},
		];
		return $behaviors;
	}
	
	
	 public function actionIndexx()
    {
        if (\Yii::$app->user->isGuest) {
            echo "aaa";
        }
        echo \Yii::$app->user->getId();
    }
	
	

}


