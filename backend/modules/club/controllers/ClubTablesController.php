<?php

namespace backend\modules\club\controllers;

use Yii;
use backend\modules\club\models\ClubTables;
use backend\modules\club\models\ClubResults;
use backend\modules\club\models\Club;
use backend\modules\club\models\search\ClubTablesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\helpers\ExtraFunctions;
use yii\web\HttpException;

/**
 * ClubTablesController implements the CRUD actions for ClubTables model.
 */
class ClubTablesController extends Controller
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

    /**
     * Lists all ClubTables models.
     * @return mixed
     */
    public function actionIndex($club_page)
    {
		$model = Club::findModel($club_page);
		$pageAdmin = Club::pageAdmin($model);//is this user admin of this page?

		$result_type=isset($_GET["type"]) ? $_GET["type"] : ClubTables::RESULT_TYPE_TEAM;
        $searchModel = new ClubTablesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model, $result_type);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'model'=>$model,
			'pageAdmin'=>$pageAdmin,
			'result_type'=>$result_type,
        ]);
    }

    /**
     * Displays a single ClubTables model.
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
     * Creates a new ClubTables model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($club_page)
    {
        $ClubTables = new ClubTables();
		$model = Club::findModel($club_page);
		$pageAdmin = Club::pageAdmin($model);//is this user admin of this page?
		if($pageAdmin==false)
			throw new HttpException(403, 'You cannot add result here.');

        if ($ClubTables->load(Yii::$app->request->post())) 
		{
			//create directory if doesn't exist
			$tmp_dir=ClubTables::clubTablesUploadDir(Yii::getAlias("@webroot"), $ClubTables->year, $model->ID);
			if(!file_exists($tmp_dir))
			{
				mkdir($tmp_dir, 0755, true);//true - create whole path to that directory
			}
		
			$UPLOAD_DIR=ClubTables::clubTablesUploadDir(Yii::getAlias("@web"), $ClubTables->year, $model->ID);
			
			$uploadedFile=ExtraFunctions::uploadImage(
			$_FILES["ClubTables"]["name"]["pdf_file"],
			$_FILES["ClubTables"]["tmp_name"]["pdf_file"],
			$_FILES["ClubTables"]["size"]["pdf_file"], 
			$_FILES["ClubTables"]["error"]["pdf_file"], 
			$ClubTables, 
			"create", 
			$UPLOAD_DIR, 
			"pdf_file", 
			ClubResults::PDF_FILE_SIZE);
			
			$ClubTables->IDclub=$model->ID;
			$ClubTables->IDuser=Yii::$app->user->getId();
			$ClubTables->pdf_file=$uploadedFile["FILE_NAME"];
			$ClubTables->save();
            return $this->redirect(['index', 'club_page' => $club_page, 'type'=>$ClubTables->result_type]);

        } 
		else 
		{
            return $this->render('create', [
                'ClubTables' => $ClubTables,
				'model'=>$model,
            ]);
        }
    }

    /**
     * Updates an existing ClubTables model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $club_page)
    {
        $ClubTables = $this->findModel($id);
		$model = Club::findModel($club_page);
		$pageAdmin = Club::pageAdmin($model);//is this user admin of this page?
		if($pageAdmin==false)
			throw new NotFoundHttpException('The requested page does not exist.');

        if ($ClubTables->load(Yii::$app->request->post()) ) 
		{
			$ClubTables->save();
            return $this->redirect(['index', 'club_page' => $model->club_link, 'type'=>$ClubTables->result_type]);
        } 
		else 
		{
            return $this->render('update', [
                'model' => $model,
				'ClubTables'=>$ClubTables,
            ]);
        }
    }

    /**
     * Deletes an existing ClubTables model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$model=$this->findModel($id);
        $model->delete();
		unlink(ClubTables::clubTablesUploadDir(Yii::getAlias("@webroot"), $model->year, $model->relationIDclub->ID, $model->pdf_file));

        return $this->redirect(['index', 'club_page'=>$model->relationIDclub->club_link,'type'=>$model->result_type]);
    }

    /**
     * Finds the ClubTables model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ClubTables the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ClubTables::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
