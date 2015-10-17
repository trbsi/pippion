<?php

namespace backend\modules\club\controllers;

use Yii;
use backend\modules\club\models\Club;
use backend\modules\club\models\ClubAdmin;
use backend\modules\club\models\ClubVisits;
use backend\modules\club\models\ClubMembers;
use backend\modules\club\models\search\ClubSearch;
use backend\modules\club\models\search\ClubMembersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\helpers\ExtraFunctions;
use backend\modules\pigeon_image\models\search\ImageSearch;
use backend\modules\pigeon_image\models\Album;


/**
 * ClubController implements the CRUD actions for Club model.
 */
class ClubController extends Controller
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
                        'actions' => ['create', 'update', 'add-member', 'delete-member', 'update-member'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view', 'index', 'weather', 'about', 'results', 'members', 'gallery'],
                        'roles' => ['@', '?']
                    ],
                ]
            ],

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => 
				[
                    'delete' => ['post'],
                    'delete-member' => ['post'],
                ],
            ],
        ];
    }


	/*----------------------------------------CLUB MEMBERS---------------------------------------------------*/
	/*
	* return model for ClubMembers
	*/
	protected function findMemberModel($id)
    {
        if (($model = ClubMembers::find()->where(["ID"=>$id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	/*
	* Update specific member
	*/
	public function actionUpdateMember($id, $club_page)
	{
        $model = Club::findModel($club_page);
		$pageAdmin = Club::pageAdmin($model);//is this user admin of this page?
		if($pageAdmin==false)
			throw new NotFoundHttpException('The requested page does not exist.');

		$model = $this->findMemberModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) 
		{
            return $this->redirect(['members', 'club_page' => $club_page]);
        } else {
            return $this->render('members/update-member', [
                'model' => $model,
            ]);
        }	
	}
	
	/**
     * Deletes an existing ClubMembers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteMember($id)
    {
		$model=$this->findMemberModel($id);
        $model->delete();

        return $this->redirect(['members', 'club_page'=>$model->relationIDclub->club_link]);
    }
	
	/*
	* add new club member
	* @param string $club_page - club_link in mg_club
	*/
	public function actionAddMember($club_page)
	{
		$ClubMembers = new ClubMembers;
        $model = Club::findModel($club_page);
		$pageAdmin = Club::pageAdmin($model);//is this user admin of this page?
		if($pageAdmin==false)
			throw new NotFoundHttpException('The requested page does not exist.');
		
		if (Yii::$app->request->post()) 
		{ 
			$post=Yii::$app->request->post();
			foreach($post["ClubMembers"] as $key=>$value)
			{
				$ClubMembers = new ClubMembers;
				$ClubMembers->attributes=$value;
				$ClubMembers->IDclub=$model->ID;
				$ClubMembers->save();
			}
			 return $this->redirect(['members', 'club_page' => $model->club_link]);			
		}

        return $this->render('members/add-member', [
			'model'=>$model,
			'addMemberModel'=> $ClubMembers,
        ]);
	}
	
	/*
	* Club members	
	* @param string $club_page - club_link in mg_club
	*/
	public function actionMembers($club_page)
	{
        $model = Club::findModel($club_page);
		$pageAdmin = Club::pageAdmin($model);//is this user admin of this page?

		$searchModel = new ClubMembersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $model);
		

        return $this->render('members/members', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'pageAdmin'=>$pageAdmin,
			'model'=>$model,
        ]);
	}
	/*----------------------------------------CLUB MEMBERS---------------------------------------------------*/
	

	/*
	* show weather widget
	* @param string $club_page - club_link in mg_club
	*/
	public function actionWeather($club_page)
	{
        return $this->render('weather', ['model' => Club::findModel($club_page)]);
	}
	
    /**
     * Lists all Club models.
     * @return mixed
	 * @param string $club_page - club_link in mg_club
     */
    public function actionIndex($club_page)
    {
        $searchModel = new ClubSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Club model.
     * @param integer $id
     * @return mixed
	 * @param string $club_page - club_link in mg_club
     */
    public function actionView($club_page)
    {
        $clubModel = Club::findModel($club_page);
		$ClubVisits = new ClubVisits;
		$ClubVisits->IDclub=$clubModel->ID;
		$ClubVisits->ip=$_SERVER['REMOTE_ADDR'];
		$ClubVisits->save();
		
		//count how many visits does it have
		$Visits=ClubVisits::find()->where(['IDclub'=>$clubModel->ID])->groupBy('ip')->count();
		
		 return $this->render('view', [
		 	'edit'=>false,
			'model' => Club::findModel($club_page),
			'Visits'=>$Visits
		]);
    }

    /**
     * Creates a new Club model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
	 * @param string $club_page - club_link in mg_club
     */
    public function actionCreate($club_page)
    {
        $model = new Club();
		
        if ($model->load(Yii::$app->request->post())) 
		{
			$model->club_link=str_replace(" ", "", strtolower($model->club)); //remove white space
			$model->club_link=preg_replace("/[^A-Za-z0-9 ]/", '', $model->club_link); //http://stackoverflow.com/questions/659025/how-to-remove-non-alphanumeric-characters
			if($model->save())
			{
				$ClubAdmin=new ClubAdmin;
				$ClubAdmin->IDclub=$model->ID;
				$ClubAdmin->IDuser=Yii::$app->user->getId();
				$ClubAdmin->save();
			}
			
            return $this->redirect(['view', 'club_page' => $model->club_link]);
        } 
		else 
		{
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Club model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $club_page - club_link in mg_club
     * @return mixed
     */
    public function actionUpdate($club_page)
    {
        $model = Club::findModel($club_page);
		//is this user admin of this page? if not throw exception
		$pageAdmin = Club::pageAdmin($model);
		if($pageAdmin==false)
			throw new NotFoundHttpException('The requested page does not exist.');

        if ($model->load(Yii::$app->request->post()) && $model->save()) 
		{
            return $this->redirect(['view', 'club_page' => $club_page]);
        } 
		else
		 {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Club model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
   /* public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }*/

}
