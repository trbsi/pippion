<?php

namespace backend\controllers;

use backend\models\PigeonInsider;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\helpers\ExtraFunctions;
class PigeonInsiderController extends \yii\web\Controller
{
	
	/*
	* execute this code before everything else
	*/
	public function beforeAction($action)
	{

		$x= new ExtraFunctions;
		$x->beforeActionTimeLanguage();
		$x->beforeActionBreederData();
		return parent::beforeAction($action);
	}

	
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => 
			[
                'class' => AccessControl::className(),
                'rules' => 
				[
                    [
                        'actions' => ['create'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    public function actionCreate()
    {
		$model=new PigeonInsider();

		
		if(isset($_POST["pigeon_insider_submit"]))
		{
			$model->attributes=$_POST['PigeonInsider'];
			$tabularData=$model->createPigeonInsider($model);
		}
		else
			$tabularData=false;
			
		if(Yii::$app->user->isGuest)
		{
			return $this->renderPartial('create_live',['model'=>$model, 'tabularData'=>$tabularData]);
		}
		else
		{
			return $this->render('create',['model'=>$model, 'tabularData'=>$tabularData]);
		}

    }

}
