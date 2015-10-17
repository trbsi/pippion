<?php

namespace backend\modules\pigeon_image\controllers;

use Yii;
use backend\modules\pigeon_image\models\Like;
use backend\modules\pigeon_image\models\Image;
use backend\modules\pigeon_image\models\Album;
use backend\modules\pigeon_image\models\Comment;
use backend\modules\pigeon_image\models\search\ImageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use backend\helpers\ExtraFunctions;

/**
 * ImageController implements the CRUD actions for PigeonImage model.
 */
class ImageController extends Controller
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
                        'actions' => ['latest','update'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['image-lightbox'],
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
     * return latest images of all users
     */
    public function actionLatest()
    {
		$query=Image::find()->orderBy('date_created DESC')->with(['relationIDuser', 'relationIDalbum']);
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
	* return html for requested image (image + comment box + comments + likes)
	* called from colorbox
	*/
	public function actionImageLightbox($IDalbum, $IDimage)
	{
		$pictureModel=Image::find()->where(['ID'=>$IDimage, 'IDalbum'=>$IDalbum])->one();
		$commentModel=Comment::find()->where(['IDimage'=>$IDimage])->orderBy('date_created DESC');
		$numberOfLikes=Like::find()->where(['IDimage'=>$IDimage])->count();
		$didILikedIt=Like::find()->where(['IDimage'=>$IDimage, 'IDuser'=>Yii::$app->user->getId()])->count();
		$commentDataProvider = new ActiveDataProvider([
			'query' => $commentModel,
			'pagination' => 
			[
				'pageSize' => 500,
			],
		]);
		
		//render ajax to render partial but include all javascript files of yii
		return $this->renderAjax('image-lightbox', [
            'pictureModel' => $pictureModel,
			'commentDataProvider' => $commentDataProvider,
			'numberOfLikes'=>$numberOfLikes,
			'didILikedIt'=>$didILikedIt,
        ]);
	}
    
	/**
     * Lists all PigeonImage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ImageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PigeonImage model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PigeonImage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PigeonImage();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ID]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PigeonImage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($IDalbum)
    {
		$query = Image::find()->where(['IDalbum'=>$IDalbum, 'IDuser'=>Yii::$app->user->getId()]);
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=>['attributes' => ['date_created'], 'defaultOrder'=>['date_created'=>SORT_DESC]],
			'pagination'=>
			[
				'pageSize'=>100
			],
        ]);
		
		
		if(isset($_POST["description"]))
		{
			foreach($_POST["description"] as $key=>$value)
			{
				$model=Image::findOne((int)$_POST["IDimage"][$key]);
				if($model)
				{
					$model->description=$value;
					$model->save();
				}
			}
			Yii::$app->session->setFlash('info', Yii::t('default', 'Descriptions have been added'));
		}
       /* if ($model->load(Yii::$app->request->post()) && $model->save()) 
		{
            return $this->redirect(['view', 'id' => $model->ID]);
        } */
		return $this->render('update', [
			'dataProvider' => $dataProvider,
			'IDalbum' => $IDalbum,
		]);
    }

    /**
     * Deletes an existing PigeonImage model.
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
     * Finds the PigeonImage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PigeonImage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PigeonImage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
