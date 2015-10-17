<?php
namespace api\modules\backend\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;

use Yii;
use backend\user\models\LoginForm;
use backend\user\models\RegistrationForm;
use backend\user\models\User;
use backend\models\Breeder;

class UserController extends ActiveController
{
	public $modelClass = "backend\user\models\User";
	
	
/*	public function behaviors()
	{
		$behaviors = parent::behaviors();
		$behaviors['authenticator'] = 
		[
			'class' => CompositeAuth::className(),
			'authMethods' => [
				HttpBasicAuth::className(),
				HttpBearerAuth::className(),
				QueryParamAuth::className(),
			],
			/*'auth'=>function ($username, $password) {
					return User::findOne([
						'username' => $username,
						'password' => $password,
					]);
				}
		];
		return $behaviors;
	}*/
	/*public function beforeAction($action)
	{
		if (parent::beforeAction($action)) 
		{
			if(Yii::$app->user->getIsGuest())
			{echo json_encode(['test'=>'gost']); return false;}
			else
			{echo json_encode(['test'=>'nije gost']); return true;}

		} 
		else
		{
			return false;
		}
	}*/
    public function actionLogin()
    {
        $model = \Yii::createObject(LoginForm::className());

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->login()) {
            echo json_encode(['ID'=>Yii::$app->user->getId(), 'username'=>$model->login]);
        }
		else
			echo json_encode(['error'=>Yii::t('user', 'Invalid login or password')]);

    }

	
	public function actionRegister()
	{
        $model = \Yii::createObject(RegistrationForm::className());
		$model->scenario = 'mobile-registration';//this is important because of this scenario captcha won't be neccesary

        if ($model->load(\Yii::$app->request->post()) && $model->register()) {
			echo json_encode(['ID'=>Yii::$app->user->getId(), 'username'=>$model->username]);
        }
		else
			echo json_encode(['error'=>'Došlo je do pogreške u registraciji. Korisnik ili email postoji ili vam je korisničko ime i lozinka prekratko']);
	}

	public function actionIndex()
	{
	}
	
	public function actionLogout()
    {
        \Yii::$app->user->logout();

        echo json_encode(['test'=>Yii::$app->user->getId()]);
    }

	
	public function actionIndexx()
    {
        if (\Yii::$app->user->isGuest) {
            echo json_encode(['test'=>'gosst']);
        }
		else
			echo json_encode(['test'=>\Yii::$app->user->getId()]); 
    }
	
	/*
	* get breeder's info
	*/
	public function actionGetBreederInfo()
	{
		$info=Breeder::find()->where(['IDuser'=>2])->one();
		echo json_encode([
			'name_of_breeder' => $info->name_of_breeder ,
			'country' => $info->relationCountry->country_name,
			'town' => $info->town,
			'address' => $info->address,
			'tel1' => $info->tel1,
			'tel2' => $info->tel2,
			'mob1' => $info->mob1,
			'mob2' => $info->mob2,
			'email1' => $info->email1,
			'email2' => $info->email2,
			'fax' => $info->fax,
			'website' => $info->website,
			'verified' => $info->verified, //0 or 1

		]);
		
	}
}
?>