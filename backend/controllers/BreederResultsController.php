<?php

namespace backend\controllers;

use Yii;
use backend\models\BreederResults;
use backend\models\search\BreederResultsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\helpers\ExtraFunctions;
/**
 * BreederResultsController implements the CRUD actions for BreederResults model.
 */
class BreederResultsController extends Controller
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
                        'allow' => true,
                        'actions' => ['index', 'update', 'delete', 'view', 'create'],
                        'roles' => ['@']
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
     * Lists all BreederResults models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BreederResultsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$searchModel->load(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BreederResults model.
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
     * Creates a new BreederResults model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BreederResults();
		$model->IDuser=Yii::$app->user->getId();
        if ($model->load(Yii::$app->request->post()) && $model->save()) 
		{
            return $this->redirect(['view', 'id' => $model->ID]);
        } 
		else 
		{
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing BreederResults model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$model->IDuser=Yii::$app->user->getId();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing BreederResults model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		try 
		{
			 $this->findModel($id)->delete();
		} 
		catch (IntegrityException $e) 
		{
			throw new HttpException(403, Yii::t('default','DELETE_UZGAJIVAC_REZULTATI'));
		}

       

        return $this->redirect(['index']);
    }

    /**
     * Finds the BreederResults model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BreederResults the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
		//user can see only his results
        if (($model = BreederResults::find()->where(['IDuser'=>Yii::$app->user->getId(), 'ID'=>$id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
