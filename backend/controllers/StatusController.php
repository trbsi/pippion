<?php

namespace backend\controllers;

use Yii;
use backend\models\Status;
use backend\models\search\StatusSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\db\IntegrityException;
use yii\web\HttpException;

use backend\helpers\ExtraFunctions;
use backend\models\Subscription;

/**
 * StatusController implements the CRUD actions for Status model.
 */
class StatusController extends Controller
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
                        'actions' => ['create', 'view', 'update', 'delete', 'index'],
                        'allow' => true,
						'roles' => ['@'],
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
	
	public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Lists all Status models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StatusSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Status model.
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
     * Creates a new Status model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Status();
		$model->IDuser=Yii::$app->user->getId();
        if ($model->load(Yii::$app->request->post())) 
		{
			//SAD JOŠ PROVJERI JEL POLJE 'frompedigree' NOVOG STATUSA POSTAVLJEN KAO 1, TAKO DA MOŽEŠ SVE OSTALE POSTAVIT NA 0
			if($model->frompedigree=='1')
			{
				$model->updateAll(['frompedigree'=>'0'], 'IDuser=:ID', [':ID'=>Yii::$app->user->getId()]);
			}
			if($model->save())
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
     * Updates an existing Status model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$model->IDuser=Yii::$app->user->getId();
        if ($model->load(Yii::$app->request->post())) 
		{
			//SAD JOŠ PROVJERI JEL POLJE 'izrodovnika' TRENUTNOG STATUSA POSTAVLJEN KAO 1, TAKO DA MOŽEŠ SVE OSTALE POSTAVIT NA 0
			if($model->frompedigree=='1')
			{
				$model->updateAll(['frompedigree'=>'0'],'IDuser=:ID', [':ID'=>Yii::$app->user->getId()]);
			}

			if($model->save())
				return $this->redirect(['view', 'id' => $model->ID]);
        } 
		else 
		{
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Status model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		//http://www.yiiframework.com/forum/index.php/topic/58662-integrity-constraint-violation-not-intercepted/
		try 
		{
			$this->findModel($id)->delete();
		} 
		catch (IntegrityException $e) 
		{
			throw new HttpException(403, Yii::t('default','DELETE_STATUS'));
		}
        

        return $this->redirect(['index']);
    }

    /**
     * Finds the Status model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Status the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Status::find()->where(['IDuser'=>Yii::$app->user->getId(), 'ID'=>$id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
