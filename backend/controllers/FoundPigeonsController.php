<?php

namespace backend\controllers;

use Yii;
use backend\models\FoundPigeons;
use backend\models\search\FoundPigeonsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\Url;

use yii\db\IntegrityException;
use yii\web\HttpException;

use backend\helpers\ExtraFunctions;
use backend\helpers\Mysqli;
use backend\models\Subscription;

/**
 * FoundPigeonsController implements the CRUD actions for FoundPigeons model.
 */
class FoundPigeonsController extends Controller
{
	
	
	/*
	* execute this code before everything else
	*/
	public function beforeAction($action)
	{

		$x = new ExtraFunctions;
		$x->beforeActionTimeLanguage();
		$x->beforeActionBreederData();

		return parent::beforeAction($action);
	}



    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['update', 'delete', 'create', 'index'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['public', 'view'],
                        'roles' => ['@', '?']
                    ],
                ]
            ],

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all FoundPigeons models.
     * @return mixed
     */
    public function actionPublic()
    {
        $searchModel = new FoundPigeonsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'public');
		$title=Yii::t('default', 'Lost and found pigeons worldwide');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'title'=>$title,
        ]);
    }

    /**
     * Lists all FoundPigeons models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FoundPigeonsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$title=Yii::t('default', 'List of all pigeons youve found');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'title'=>$title,
        ]);
    }

    /**
     * Displays a single FoundPigeons model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findViewModel($id),
        ]);
    }


	
    /**
     * Creates a new FoundPigeons model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FoundPigeons();
		$ExtraFunctions = new ExtraFunctions;
		
        if ($model->load(Yii::$app->request->post())) 
		{
			$uploadImage["uploadOk"]=1;//because of $uploadImage["uploadOk"]==0, if that array is not set this will be
			if(isset($_FILES["image_file"]["name"]) && !empty($_FILES["image_file"]["name"]))
			{
				$uploadImage = $ExtraFunctions->uploadImage($_FILES["image_file"]["name"], $_FILES["image_file"]["tmp_name"], $_FILES["image_file"]["size"], $_FILES["image_file"]["error"], $model, 'create', FoundPigeons::UPLOAD_DIR, 'image_file', FoundPigeons::IMAGE_SIZE);
				$FILE_NAME=$uploadImage["FILE_NAME"];

			}
			else
				$FILE_NAME=ExtraFunctions::NO_PICTURE;
			
			if($uploadImage["uploadOk"]==0)
			{
				return $this->render('update', [
					'model' => $model,
				]);
			}
			else
			{
				$model->IDuser=Yii::$app->user->getId();
				$model->image_file=$FILE_NAME;
				$model->date_created=$ExtraFunctions->currentTime("ymd-his");
				$model->save();
				return $this->redirect(['view', 'id' => $model->ID]);
			}
        } 
		else 
		{
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FoundPigeons model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) 
		{
			$ExtraFunctions = new ExtraFunctions;
			
			$oldModel=$this->findModel($id);
			$uploadImage["uploadOk"]=1;//because of $uploadImage["uploadOk"]==0, if that array is not set this will be
			$FILE_NAME=$oldModel->image_file;
			if(isset($_FILES["image_file"]["name"]) && !empty($_FILES["image_file"]["name"]))
			{
				$uploadImage = $ExtraFunctions->uploadImage($_FILES["image_file"]["name"], $_FILES["image_file"]["tmp_name"], $_FILES["image_file"]["size"], $_FILES["image_file"]["error"], $oldModel, 'update', FoundPigeons::UPLOAD_DIR, 'image_file', FoundPigeons::IMAGE_SIZE);	//need to send $oldModel because in this function I'm getting old image name so I can delete it
				
				$FILE_NAME=$uploadImage["FILE_NAME"];
			}
			
			if($uploadImage["uploadOk"]==0)
			{
				return $this->render('update', [
					'model' => $model,
				]);
			}
			else
			{
				$model->image_file=$FILE_NAME;
				$model->save();
				return $this->redirect(['view', 'id' => $model->ID]);
			}
        } 
		else 
		{
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing FoundPigeons model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		try
		{
			$model=$this->findModel($id);
			if($model->delete())
			{
				if($model->image_file!=ExtraFunctions::NO_PICTURE)
					unlink($_SERVER['DOCUMENT_ROOT'].FoundPigeons::UPLOAD_DIR.$model->image_file);
			}
			Yii::$app->session->setFlash('success', Yii::t('default','Action was successful'));
		}
		catch(IntegrityException $e)
		{
			throw new HttpException(400, Yii::t('default', 'Action was now successful'));
		}

        return $this->redirect(['index']);
    }

    /**
     * Finds the FoundPigeons model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FoundPigeons the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FoundPigeons::find()->where(['ID'=>$id, 'IDuser'=>Yii::$app->user->getId()])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findViewModel($id)
    {
        if (($model = FoundPigeons::find()->where(['ID'=>$id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
