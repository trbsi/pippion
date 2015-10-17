<?php

namespace backend\modules\pigeon_image\controllers;

use Yii;
use backend\modules\pigeon_image\models\Album;
use backend\modules\pigeon_image\models\search\AlbumSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\modules\pigeon_image\models\search\ImageSearch;
use backend\modules\pigeon_image\models\Image;
use backend\helpers\ExtraFunctions;
use yii\data\ActiveDataProvider;

/**
 * PigeonImageAlbumController implements the CRUD actions for PigeonImageAlbum model.
 */
class AlbumController extends Controller
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
                        'actions' => ['update', 'delete', 'create', 'upload-files', 'delete-images', 'latest'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view', 'index'],
                        'roles' => ['@', '?']
                    ],
                ]
            ],
           'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
					'delete-images' => ['post'],
                ],
            ],
        ];
    }

	/**
     * return latest albums of all users
     */
    public function actionLatest()
    {
		$query=Album::find()->orderBy('date_created DESC')->with(['lastAlbumPhoto', 'relationIDuser']);
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => 100,
			],
		]);
        return $this->render('latest', [
            'dataProvider' => $dataProvider,
        ]);
    }
	
	/*
	* this is function from jquery-file-upload to upload files
	* $id - IDalbum
	*/
	public function actionUploadFiles($id)
	{
		
		if($model=$this->findModel($id))
		{
			/*
			 * jQuery File Upload Plugin PHP Example 5.14
			 * https://github.com/blueimp/jQuery-File-Upload
			 *
			 * Copyright 2010, Sebastian Tschan
			 * https://blueimp.net
			 *
			 * Licensed under the MIT license:
			 * http://www.opensource.org/licenses/MIT
			 */
			error_reporting(E_ALL | E_STRICT);
			require(Yii::getAlias('@webroot').'/jquery-file-upload/server/php/CustomUploadHandler.php');
			$options = array
			(
				'upload_dir' => Album::returnAlbumDirectory($model->ID, Yii::getAlias('@webroot'), $model->IDuser),  //HAS TO BE SLASH AT THE END       
				'upload_url' => Album::returnAlbumDirectory($model->ID, Yii::getAlias('@web'), $model->IDuser),  //HAS TO BE SLASH AT THE END      
				'accept_file_types' => '/\.(gif|jpe?g|png)$/i' ,
				'max_file_size'=>Image::MAX_IMAGE_SIZE, //5mb 
				'IDalbum' => $id, //added by me
				'image_versions' => array
				(
					'thumbnail' => array
					(
							// Uncomment the following to use a defined directory for the thumbnails
							// instead of a subdirectory based on the version identifier.
							// Make sure that this directory doesn't allow execution of files if you
							// don't pose any restrictions on the type of uploaded files, e.g. by
							// copying the .htaccess file from the files directory for Apache:
							//'upload_dir' => dirname($this->get_server_var('SCRIPT_FILENAME')).'/thumb/',
							//'upload_url' => $this->get_full_url().'/thumb/',
							// Uncomment the following to force the max
							// dimensions and e.g. create square thumbnails:
							'crop' => true,
							'max_width' => 206,
							'max_height' => 206,
					)  
				),             
			);
			$upload_handler = new \CustomUploadHandler($options);
		}
	}

    /**
     * Lists all PigeonImageAlbum models.
     * @return mixed
	 * $club_page indicates that people visited album from club's page (so I can adjust menu in main-menu.php to show only club's menu)
	 * $club_page indicates to search only albums from that club, it is link from club's page gallery
     */
    public function actionIndex($club_page=NULL)
    {
        $searchModel = new AlbumSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $club_page);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'club_page'=>$club_page,
        ]);
    }

    /**
     * Displays a single PigeonImageAlbum model.
     * @param integer $id
     * @return mixed
	 * $club_page indicates that people visited album from club's page (so I can adjust menu in main-menu.php  to show only club's menu)
     */
    public function actionView($id, $club_page=NULL)
    {        
		$searchModel = new ImageSearch();
        $dataProviderImages = $searchModel->search(Yii::$app->request->queryParams, $id); //$id=IDalbum
		$model=$this->findModel($id);
		
		//is this current user's album? Can he edit it?
		if($model->IDuser==Yii::$app->user->getId())
			$canEdit=true;
		else
			$canEdit=false;
		
        return $this->render('view', [
            'model' => $model,
			'dataProviderImages' => $dataProviderImages,
			'canEdit'=>$canEdit,
        ]);
    }

    /**
     * Creates a new PigeonImageAlbum model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Album();
		$model->IDuser=Yii::$app->user->getId();
		$model->date_created=ExtraFunctions::currentTime("ymd-his");
		
        if ($model->load(Yii::$app->request->post()) && $model->save()) 
		{
			$dir_tmp=Album::returnAlbumDirectory($model->ID,Yii::getAlias('@webroot'), $model->IDuser); 
			if(!file_exists($dir_tmp))
			{
				mkdir($dir_tmp, 0755, true);//true - create whole path to that directory
			}
            return $this->redirect('index');
        } 
		else 
		{
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PigeonImageAlbum model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PigeonImageAlbum model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if($model=$this->findModel($id))
		{
			if($model->delete())
				Album::removeFilesAndFolder(Album::returnAlbumDirectory($model->ID,Yii::getAlias('@webroot'), $model->IDuser));
		}
        return $this->redirect(['index']);
    }

    /**
     * Deletes an selected images in specific account
	 * $id - IDalbum
     */
    public function actionDeleteImages($id)
    {
		
		if(isset($_POST["delete_image"]) && is_numeric($id))
		{
			foreach($_POST["delete_image"] as $value) //$value - IDimage (ID in Image)
			{
				if($model=Image::findImageById($value, $id))
				{
					if($model->delete())
					{
						unlink(Album::returnPathToPicture($id, $model->image_file, Yii::getAlias('@webroot'), $model->IDuser));
						unlink(Album::returnPathToThumbnail($id, $model->image_file, Yii::getAlias('@webroot'), $model->IDuser));
					}
				}
			}
			Yii::$app->session->setFlash('success', Yii::t('default', 'Images were deleted'));
			return $this->redirect(['view', 'id'=>$id]);
		}
		return $this->redirect(['view', 'id'=>$id]);
    }

    /**
     * Finds the PigeonImageAlbum model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PigeonImageAlbum the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
		return Album::findAlbumModel($id);
    }
}
