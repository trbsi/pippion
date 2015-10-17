<?php

namespace backend\controllers;

use Yii;
use backend\models\PigeonData;
use backend\models\search\PigeonDataSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\Url;

use backend\helpers\ExtraFunctions;
use backend\models\Subscription;

use yii\db\IntegrityException;
use yii\web\HttpException;

/**
 * PigeonDataController implements the CRUD actions for PigeonData model.
 */
class PigeonDataController extends Controller
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
     * Lists all PigeonData models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PigeonDataSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PigeonData model.
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
     * Creates a new PigeonData model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PigeonData();

        if ($model->load(Yii::$app->request->post())) 
		{
			$model->IDuser=Yii::$app->user->getId();
			$model->IDpigeon=($_POST["malefemale"]=="male") ? $_POST["Father_ID"] : $_POST["Mother_ID"];
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
     * Updates an existing PigeonData model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		

        if ($model->load(Yii::$app->request->post())) 
		{
			$model->IDuser=Yii::$app->user->getId();
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
     * Deletes an existing PigeonData model.
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
		catch(IntegrityException $e)
		{
			throw new HttpException(400, Yii::t('default', 'DELETE_PODACI_O_GOLUBU'));
		}

        

        return $this->redirect(['index']);
    }

    /**
     * Finds the PigeonData model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PigeonData the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PigeonData::find()->where(['ID'=>$id, 'IDuser'=>Yii::$app->user->getId()])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
