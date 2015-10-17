<?php

namespace backend\modules\pigeon_image\controllers;

use Yii;
use backend\modules\pigeon_image\models\Like;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\helpers\ExtraFunctions;
use backend\helpers\LinkGenerator;

/**
 * LikeController implements the CRUD actions for Like model.
 */
class LikeController extends Controller
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
                        'actions' => ['create', 'who-liked'],
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
	
	/*
	* return data for bootstrap modal dialog of all people who liked specific picture
	*/
	public function actionWhoLiked()
	{
		if(isset($_POST["IDimage"]))
		{
			$IDimage=(int)$_POST["IDimage"];
			$result=Like::find()->where(['IDimage'=>$IDimage])->all();
			$return=NULL;
			$return.='<div class="who_liked_box">';
			foreach($result as $value)
			{
				$return.='<div class="who_liked_users">';
				$return.='<span style="font-weight:700">'.LinkGenerator::breederLink($value->relationIDuser->username, $value->IDuser, ['class'=>'null']).'</span>';
				$return.='</div>';
			}
	
			$return.='</div>';
			
			echo json_encode(['result'=>'true', 'people'=>$return]);
		}
	}
	
    /**
     * Lists all Like models.
     * @return mixed
     
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Like::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }*/

    /**
     * Displays a single Like model.
     * @param integer $id
     * @return mixed
    
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    } */

    /**
     * Creates a new Like model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		/*if user liked this picture then $_POST["like"] will be 1, it means that it has to unlike it if he click this button. If user didn't like this pic $_POST["like"]=0, it means it should like it when he clicks this button*/
		$IDimage = (int)$_POST["IDimage"];;
		if($_POST["like"]==0)
		{
			$model = new Like();
			$model->IDimage=$IDimage;
			$model->IDuser=Yii::$app->user->getId();
			if ($model->save()) 
			{
				echo json_encode(['result'=>'true', 'like'=>1]); // if like==yes it means user liked picture, if like==no, user unliked it
			} 
			else
			{
				echo json_encode(['result'=>'false']);
			}
		}
		else
		{
			if(Like::deleteAll(['IDimage'=>$IDimage, 'IDuser'=>Yii::$app->user->getId()]))
			{
				echo json_encode(['result'=>'true', 'like'=>0]); // if like==1 it means user liked picture, if like==0, user unliked it			
			}
			else
				echo json_encode(['result'=>'false']);
		}

    }

    /**
     * Updates an existing Like model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
    
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
 */
    /**
     * Deletes an existing Like model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
    
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    } */

    /**
     * Finds the Like model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Like the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Like::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
