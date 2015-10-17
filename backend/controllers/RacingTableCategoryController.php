<?php

namespace backend\controllers;

use Yii;
use backend\models\RacingTableCategory;
use backend\models\search\RacingTableCategorySearch;
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
 * RacingTableCategoryController implements the CRUD actions for RacingTableCategory model.
 */
class RacingTableCategoryController extends Controller
{
	
	/*
	* execute this code before everything else
	*/
	public function beforeAction($action)
	{

		$x = new ExtraFunctions;
		$x->beforeActionTimeLanguage();
		$x->beforeActionBreederData();
		
		/*$s = new Subscription;
		$s->hasSubEnded();*/
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
                        'actions' => ['index', 'update', 'delete', 'create'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['@'],
						'matchCallback' => function ($rule, $action) 
						{
                      		 return Yii::$app->user->identity->getisAdmin();
                   		}
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
     * Lists all RacingTableCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RacingTableCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RacingTableCategory model.
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
     * Creates a new RacingTableCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RacingTableCategory();

        if ($model->load(Yii::$app->request->post())) 
		{
            $model->IDuser=Yii::$app->user->getId();
			if($model->save())
				return $this->redirect(['index']);
        }
		 else 
		 {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing RacingTableCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) 
		{
			Yii::$app->session->setFlash('success', Yii::t('default', 'Action was successful'));
            return $this->redirect(['index']);
        } 
		else 
		{
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing RacingTableCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		try
		{
			$this->findModel($id)->delete();
			Yii::$app->session->setFlash('success', Yii::t('default','Action was successful'));
		}
		catch(IntegrityException $e)
		{
			throw new HttpException(400, Yii::t('default', 'Action was now successful'));
		}
		
        return $this->redirect(['index']);
    }

    /**
     * Finds the RacingTableCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RacingTableCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RacingTableCategory::find()->where(['ID'=>$id, 'IDuser'=>Yii::$app->user->getId()])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
