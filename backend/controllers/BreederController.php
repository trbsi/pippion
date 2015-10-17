<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use backend\helpers\ExtraFunctions;
use backend\models\search\BreederSearch;
use backend\models\Breeder;
use backend\models\AdminNots;
use backend\models\LastVisit;
use backend\models\Pigeon;
use backend\models\BreederImage;
use yii\data\ActiveDataProvider;
/**
 * BreederController implements the CRUD actions for Breeder model.
 */
class BreederController extends Controller
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

    public function behaviors()
    {
        return [
		    'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['view', 'update', 'profile', 'index', 'upload-profile-pic'],
                        'allow' => true,
						'roles' => ['@'],
                    ],
					[
						'actions'=>['delete', 'create'],
						'allow' => true,
						'roles'=>['@'],
						'matchCallback'=>function($rule, $action) ////http://www.yiiframework.com/wiki/771/rbac-super-simple-with-admin-and-user/
						{
							return Yii::$app->user->identity->getIsAdmin();
						}
					],
                ],
            ],

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }


	/*
	* upload profile pictures
	*/
	public function actionUploadProfilePic()
	{
		$model = new BreederImage;
		if(isset($_FILES["breeder_image_file"]))
		{
			$directory_path_absolute=BreederImage::returnProfilePicDirectory(Yii::getAlias('@webroot'),Yii::$app->user->getId());
			$directory_path_relative=BreederImage::returnProfilePicDirectory(Yii::getAlias('@web'),Yii::$app->user->getId());
			if (!file_exists($directory_path_absolute)) 
			{
				mkdir($directory_path_absolute, 0777, true);
			}
			
			$image=ExtraFunctions::uploadImage
			($_FILES["breeder_image_file"]["name"],
			$_FILES["breeder_image_file"]["tmp_name"],
			$_FILES["breeder_image_file"]["size"], 
			$_FILES["breeder_image_file"]["error"], 
			$model, 
			"create", 
			$directory_path_relative, 
			"breeder_image_file", 
			Pigeon::MAX_IMAGE_SIZE_PIGEON_EYE_IMAGE);
			
			if($image["uploadOk"]==1)
			{
				BreederImage::updateAll(['is_profile' => 0], ['IDuser'=>Yii::$app->user->getId()]);
				$model->is_profile=1;
				$model->breeder_image_file=$image["FILE_NAME"];
				$model->IDuser=Yii::$app->user->getId();
				$model->save();
				return $this->redirect("profile");			
			}
		}
		
		return $this->render('profile-pic', ['model'=>$model]);
	}
	
	/*
	* frontpage of pippion, breeder's profile
	*/
	public function actionProfile()
	{
		//WHEN WAS THE LAST VISIT OF USER
		$lastVisitModel = new LastVisit;
		$lastvisit=$lastVisitModel->find()->where(['IDuser'=>Yii::$app->user->getId()])->one();
		if(!empty($lastvisit))
		{
			$lastvisit->last_visit=ExtraFunctions::currentTime("ymd-his");
			$lastvisit->save();
		}
		else
		{
			$lastVisitModel->IDuser=Yii::$app->user->getId();
			$lastVisitModel->last_visit=ExtraFunctions::currentTime("ymd-his");
			$lastVisitModel->save();
		}
		
		//find if user has any profile picture
		$profile_picture=BreederImage::findUserProfilePicture(Yii::$app->user->getId());
		
		/*$adminNotsDataProvider=new ActiveDataProvider( 
		[
			//WITHOUT ->all()
			'query'=>AdminNots::find()->where('date_t BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW()')->orderBy('date_t DESC, ID DESC'),
			'pagination'=> 
			[
				'pageSize'=>3,
			],
		]);*/

		return $this->render('profile', [
			'model'=>$this->findProfileModel(), 
			//'adminNotsDataProvider'=>$adminNotsDataProvider,
			'profile_picture'=>$profile_picture,
			]);
	}

    /**
     * Lists all Breeder models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BreederSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Breeder model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		//find if user has any profile picture
		$profile_picture=BreederImage::findUserProfilePicture(Yii::$app->user->getId());
		
        return $this->render('view', [
            'model' => $this->findViewModel($id),
			'profile_picture'=>$profile_picture,
        ]);
    }

    /**
     * Creates a new Breeder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Breeder();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->IDuser]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Breeder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) 
		{
			//set cookie, breeder enetered data, used in ExtraFunctions::beforeActionBreederData to check if breeder filled his profile
			ExtraFunctions::setCookie(\Yii::$app->params['breeder_data_cookie'], 1);
			//set cookie for breeder name
			ExtraFunctions::setCookie(\Yii::$app->params['name_of_breeder_cookie'], $model->name_of_breeder);
			
            return $this->redirect(['view', 'id' => $model->IDuser]);
        } 
		else 
		{
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Breeder model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Breeder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Breeder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
		//UZMI ID IZ MG_UZGAJIVAC SAMO ONAJ KOJI PRIPADA LOGIRANOM KORISNIKU, AKO NIJE ADMIN
		//MOŽE UPDATE SAMO SVOJE PODATKE
		if(!Yii::$app->user->identity->getIsAdmin())
			$model=Breeder::find()->where(['IDuser'=>Yii::$app->user->getId()])->one();
		else
			$model = Breeder::findOne($id);

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
    /**
     * Finds the Breeder model based on its primary key value. but just for actionView
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Breeder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findViewModel($id)
    {
		$model=Breeder::find()->where(['IDuser'=>$id])->joinWith(['relationCountry', 'relationIDuser'])->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
    /**
     * Finds the Breeder model based on its primary key value. but just for actionView
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Breeder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findProfileModel()
    {
		$model=Breeder::find()->where(['IDuser'=>Yii::$app->user->getId()])->with(['relationCountry', 'relationIDuser'])->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	

}
