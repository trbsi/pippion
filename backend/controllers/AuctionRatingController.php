<?php

namespace backend\controllers;

use Yii;
use dektrium\user\models\User;
use backend\models\AuctionRating;
use backend\models\search\AuctionRatingSearch;
use backend\models\Pigeon;
use backend\models\Auction;
use backend\models\AuctionPigeon;
use backend\models\AuctionImage;
use backend\models\AuctionBid;
use backend\models\search\AuctionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;

use yii\db\IntegrityException;
use yii\web\HttpException;

use backend\helpers\ExtraFunctions;
use backend\helpers\Mysqli;
use backend\models\Subscription;

/**
 * AuctionRatingController implements the CRUD actions for AuctionRating model.
 */
class AuctionRatingController extends Controller
{
	
	/*
	* execute this code before everything else
	*/
	public function beforeAction($action)
	{
		
		$x = new ExtraFunctions;
		$x->beforeActionTimeLanguage();
		$x->beforeActionBreederData();
		$x->isBreederVerified(Yii::t('default', 'Auctions'));
		
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
                        'actions' => ['index', 'update', 'view'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete', 'create', 'view'],
                        'roles' => ['@'],
						'matchCallback' => function ($rule, $action) 
						{
                       		return Yii::$app->user->identity->getIsAdmin();
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
     * Lists all AuctionRating models.
     * @return mixed
	 * $user - is ID of user which rating we want to show
     */
    public function actionIndex($user=NULL)
    {
		if($user!=NULL)
		{
			$user=(int)$user;
			$result=User::findOne($user);
			$title=Yii::t('default', 'Seller/buyer ratings').' | '.$result->username;
			$visible_owner=false;
		}
		else
		{
			if(isset($_GET["rating_show_only"]))
			{
				if($_GET["rating_show_only"]=="both")
				{
					$_SESSION["rating_show_only"]="both";
				}
				else if($_GET["rating_show_only"]=="winner")
				{
					$_SESSION["rating_show_only"]="winner";
				}
				else if($_GET["rating_show_only"]=="seller")
				{
					$_SESSION["rating_show_only"]="seller";
				}
				else
				{
					$_SESSION["rating_show_only"]="both";
				}
			}
			else
				$_SESSION["rating_show_only"]="both";
				
			$title=Yii::t('default', 'My ratings');
			$visible_owner=true;	
		}

        $searchModel = new AuctionRatingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $user);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'title'=>$title,
			'visible_owner'=>$visible_owner,
        ]);
    }

    /**
     * Displays a single AuctionRating model.
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
     * Creates a new AuctionRating model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuctionRating();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ID]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AuctionRating model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		 $model = $this->findUpdateModel($id);
		 
		if($model->IDwinner==Yii::$app->user->getId())
		{
			$title=Yii::t('default', 'Rate seller').' - '.$model->relationIDseller->username;
			$title_h4=Yii::t('default', 'Rate seller').' &#10137; '.$model->relationIDseller->username;
		}
			
		if($model->IDseller==Yii::$app->user->getId())
		{
			$title=Yii::t('default', 'Rate winner').' - '.$model->relationIDwinner->username;
			$title_h4=Yii::t('default', 'Rate winner').' &#10137; '.$model->relationIDwinner->username;
		}

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->IDauction]);
        } else {
            return $this->render('update', [
                'model' => $model,
				'title'=>$title,
				'title_h4'=>$title_h4,
            ]);
        }
    }

	public function findUpdateModel($id)
	{
		$model=AuctionRating::find()->where(['IDauction'=>$id])->one();
		if($model===null)
			throw new HttpException(404,Yii::t('default', 'The requested page does not exist') );
		//prvo je li IDseller jednak IDu logiranog korisnika
		if($model->IDseller==Yii::$app->user->getId())
		{
			//ako je, provjeri jel ostavio feedback Winneru
			if($model->winner_rating!=0)
				throw new HttpException(404,Yii::t('default', 'You have already left feedback') );

		}
		//prvo je li IDwinner jednak IDu logiranog korisnika
		else if($model->IDwinner==Yii::$app->user->getId())
		{
			//ako je, provjeri jel ostavio feedback selleru
			if($model->seller_rating!=0)
				throw new HttpException(404,Yii::t('default', 'You have already left feedback') );

		}
		//na kraju provjeri jel netko drugi osim winnera i sellera pokušava pristupiti tom dijelu
		else
			throw new HttpException(404,Yii::t('default', 'You cant access this page') );
		//ako su seller ili winner već ocijenili
		return $model;
	}

    /**
     * Deletes an existing AuctionRating model.
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
     * Finds the AuctionRating model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AuctionRating the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AuctionRating::find()->where(['IDauction'=>$id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
