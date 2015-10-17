<?php

namespace backend\modules\club\controllers;

use Yii;
use backend\modules\club\models\ClubResults;
use backend\modules\club\models\Club;
use backend\modules\club\models\search\ClubResultsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\helpers\ExtraFunctions;
use yii\web\HttpException;

/**
 * ClubResultsController implements the CRUD actions for ClubResults model.
 */
class ClubResultsController extends Controller
{
	/*
	* execute this code before everything else
	*/	
	public function beforeAction($action)
	{
		$x= new ExtraFunctions;
		$x->beforeActionTimeLanguage();
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
                        'actions' => ['create', 'update', 'delete'],
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
                'actions' => 
				[
                    'delete' => ['post'],
                ],
            ],
        ];
    }
	
	//!!!!!!!!!!!!!!!!!!!!!!!!!!@param string $page - club_link in mg_club
	
    /**
     * Lists all ClubResults models.
     * @return mixed
     */
    public function actionIndex($club_page)
    {
		$model = Club::findModel($club_page);
		$pageAdmin = Club::pageAdmin($model);//is this user admin of this page?

        $searchModel = new ClubResultsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'model'=>$model,
			'pageAdmin'=>$pageAdmin
        ]);
    }

    /**
     * Displays a single ClubResults model.
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
     * Creates a new ClubResults model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($club_page)
    {
        $ClubResults = new ClubResults();
		$model = Club::findModel($club_page);
		$pageAdmin = Club::pageAdmin($model);//is this user admin of this page?
		if($pageAdmin==false)
			throw new HttpException(403, 'You cannot add result here.');
		
        if ($ClubResults->load(Yii::$app->request->post())) 
		{
			//create directory if doesn't exist
			$tmp_dir=ClubResults::clubResultUploadDir(Yii::getAlias("@webroot"), $ClubResults->year, $model->ID);
			if(!file_exists($tmp_dir))
			{
				mkdir($tmp_dir, 0755, true);//true - create whole path to that directory
			}
		
			$UPLOAD_DIR=ClubResults::clubResultUploadDir(Yii::getAlias("@web"), $ClubResults->year, $model->ID);
			$uploadedFile=ExtraFunctions::uploadImage(
			$_FILES["ClubResults"]["name"]["pdf_file"],
			$_FILES["ClubResults"]["tmp_name"]["pdf_file"],
			$_FILES["ClubResults"]["size"]["pdf_file"], 
			$_FILES["ClubResults"]["error"]["pdf_file"], 
			$ClubResults, 
			"create", 
			$UPLOAD_DIR, 
			"pdf_file", 
			ClubResults::PDF_FILE_SIZE);
			
			$ClubResults->IDclub=$model->ID;
			$ClubResults->IDuser=Yii::$app->user->getId();
			$ClubResults->pdf_file=$uploadedFile["FILE_NAME"];
			$ClubResults->save();
            return $this->redirect(['index', 'club_page' => $club_page, 'ClubResultsSearch[result_type]'=>$ClubResults->result_type]);
        } 
		else {
            return $this->render('create', [
                'model' => $model,
				'ClubResults'=>$ClubResults,
            ]);
        }
    }

    /**
     * Updates an existing ClubResults model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $club_page)
    {
        $ClubResults = $this->findModel($id);
		$model = Club::findModel($club_page);
		$pageAdmin = Club::pageAdmin($model);//is this user admin of this page?
		if($pageAdmin==false)
			throw new NotFoundHttpException('The requested page does not exist.');

        if ($ClubResults->load(Yii::$app->request->post()) && $ClubResults->save()) 
		{
            return $this->redirect(['index', 'club_page' => $ClubResults->relationIDclub->club_link]);
        } 
		else 
		{
            return $this->render('update', [
                'ClubResults' => $ClubResults,
				'model'=>$model,
            ]);
        }
    }

    /**
     * Deletes an existing ClubResults model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$model=$this->findModel($id);
        $model->delete();
		unlink(ClubResults::clubResultUploadDir(Yii::getAlias("@webroot"), $model->year, $model->relationIDclub->ID, $model->pdf_file));

        return $this->redirect(['index', 'club_page'=>$model->relationIDclub->club_link]);
    }

    /**
     * Finds the ClubResults model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ClubResults the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ClubResults::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
