<?php

namespace backend\controllers;

use Yii;
use backend\models\ResolveIssue;
use backend\models\search\ResolveIssueSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\filters\AccessControl;
use backend\helpers\ExtraFunctions;
use backend\models\AuctionRating;
use backend\models\Breeder;
use backend\models\ResolveIssueReply;
use backend\models\AccountBalance;
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;
/**
 * ResolveIssueController implements the CRUD actions for ResolveIssue model.
 */
class ResolveIssueController extends Controller
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
                        'actions' => ['create', 'view', 'update', 'close'],
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
	* close the case
	*/
	public function actionClose()
	{
		if(isset($_POST["IDresolveIssue"]))
		{
			$IDuser=(int)$_POST["IDuser"];
			$id=(int)$_POST["IDresolveIssue"];
			$issue=ResolveIssue::findOne($id);
			$issue->resolved=1;
			if($issue->save())
			{
				
				$url_to_issue=\Yii::$app->params['pippion_site']."resolve-issue/view?id=$id";
				$subject=Yii::t('default', 'Case closed');

				//if admin closed it contact everyone else
				if(Yii::$app->user->identity->getIsAdmin())
				{
					$breeder=Breeder::getUserEmailAndUsername($issue->relationIDauction->relationAuctionRating->IDseller);
					$message=Yii::t('default', 'Case closed email admin', ['1'=>$url_to_issue]);
					ExtraFunctions::sendEmailToSomeone($breeder["email"], $breeder["username"], $subject, $message, $loadAutoLoader=true);
					
					$breeder=Breeder::getUserEmailAndUsername($issue->relationIDauction->relationAuctionRating->IDwinner);
					$message=Yii::t('default', 'Case closed email admin', ['1'=>$url_to_issue]);
					ExtraFunctions::sendEmailToSomeone($breeder["email"], $breeder["username"], $subject, $message, $loadAutoLoader=false);
					
				}
				else if($IDuser==$issue->relationIDauction->relationAuctionRating->IDseller)
				{
					//send email to winner
					$breeder=Breeder::getUserEmailAndUsername($issue->relationIDauction->relationAuctionRating->IDwinner);
					$message=Yii::t('default', 'Case closed email', ['1'=>$url_to_issue]);
					ExtraFunctions::sendEmailToSomeone($breeder["email"], $breeder["username"], $subject, $message, $loadAutoLoader=true);
				}
				else
				{
					//send email to seller
					$breeder=Breeder::getUserEmailAndUsername($issue->relationIDauction->relationAuctionRating->IDseller);
					$message=Yii::t('default', 'Case closed email', ['1'=>$url_to_issue]);
					ExtraFunctions::sendEmailToSomeone($breeder["email"], $breeder["username"], $subject, $message, $loadAutoLoader=true);
				}
				
				//send email to admin
				ExtraFunctions::sendEmail("Dario T.", "no-reply@pippion.com", $subject, $message, $both=true, $loadAutoLoader=false);

				
				Yii::$app->session->setFlash('success', Yii::t('default', 'Case has been closed'));
				//redirect
				return $this->redirect(['view', 'id' => $id]);
			}
		}
	}

    /**
     * Lists all ResolveIssue models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ResolveIssueSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ResolveIssue model.
     * @param integer $id - ID in ResolveIssue
     * @return mixed
     */
    public function actionView($id)
    {
		//find model, also checks if user can view this or not
		$model=$this->findModel($id);
		//find last reply
		$lastReply=ResolveIssueReply::lastReply($model->ID);

		$dataProvider = new ActiveDataProvider([
			'query' => ResolveIssueReply::find()->where(['IDresolveIssue'=>$id]),
			'pagination' => [
				'pageSize' => 30,
			],
		]);
		
		$ResolveIssueReply = new ResolveIssueReply;
		
        return $this->render('view', [
            'model' => $model,
			'dataProvider'=>$dataProvider,
			'ResolveIssueReply'=>$ResolveIssueReply,
			'lastReply'=>$lastReply
        ]);
    }

    /**
     * Creates a new ResolveIssue model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
	 * $id - ID in Auction
     */
    public function actionCreate($id)
    {
		$IDuser=Yii::$app->user->getId();
		
		//check is user is allowed to create this
		$check=ResolveIssue::isUserAllowedToSeeCase($id);
		
		//check if money was transferred, if it was then you cannot open a case
		$AB=AccountBalance::find()->where(['IDauction'=>$id])->one();
		if($AB->money_transferred==1)
			throw new \yii\web\HttpException(401, Yii::t('default', 'You cannot resolve an issue money transferred'));
		
		//check if a case has been opened, and if it was then redirect it to that case
		$caseopened=ResolveIssue::find()->where(['IDauction'=>$id])->one();
		if(!empty($caseopened))
			return $this->redirect(['view', 'id' => $caseopened->ID]);

        $model = new ResolveIssue();
		$modelResolveIssueReply = new ResolveIssueReply;

        if ($modelResolveIssueReply->load(Yii::$app->request->post())) 
		{
			$model->IDauction=$id;
			$model->date_created=ExtraFunctions::currentTime("ymd-his");
			$model->save();

			$modelResolveIssueReply->IDresolveIssue=$model->ID;
			$modelResolveIssueReply->IDuser=$IDuser;
			$modelResolveIssueReply->date_created=ExtraFunctions::currentTime("ymd-his");
			$modelResolveIssueReply->save();
			
			$url_to_issue=\Yii::$app->params['pippion_site']."resolve-issue/view?id=$model->ID";
			
			//if buyer opens issue send email to seller
			if($check->IDwinner==$IDuser)
			{
				$breeder=Breeder::getUserEmailAndUsername($check->IDseller);
				$subject=Yii::t('default', 'Buyer opened a case');
				$message=Yii::t('default', 'Buyer opened a case message', ['0'=>$breeder["username"], '1'=>$url_to_issue]);
				ExtraFunctions::sendEmailToSomeone($breeder["email"], $breeder["username"], $subject, $message, $loadAutoLoader=true);
			}
			//if seller opened issue send email to buyer
			else
			{
				$breeder=Breeder::getUserEmailAndUsername($check->IDwinner);
				$subject=Yii::t('default', 'Seller opened a case');
				$message=Yii::t('default', 'Seller opened a case message', ['0'=>$breeder["username"], '1'=>$url_to_issue]);
				ExtraFunctions::sendEmailToSomeone($breeder["email"], $breeder["username"], $subject, $message, $loadAutoLoader=true);
			}
			
			//send email to me
			ExtraFunctions::sendEmail($breeder["username"], $breeder["email"], $subject, $message, $both=true, $loadAutoLoader=false);
			
            return $this->redirect(['view', 'id' => $model->ID]);
        } 
		else 
		{
            return $this->render('create', [
                'model' => $model,
				'modelResolveIssueReply' => $modelResolveIssueReply,
				'id' => $id,
            ]);
        }
    }

    /**
     * Updates an existing ResolveIssue model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id - ID in mg_resolve_issue
     * @return mixed
     */
    public function actionUpdate($id)
    {
		//someone added new reply
		$ResolveIssueReply = new ResolveIssueReply;
		
		//get issue
		$issue=ResolveIssue::findOne($id);
		
		//if issue is resolved you cannot send messages anymore
		if($issue->resolved==1)
			return $this->redirect(['view', 'id' => $id]);
			
        if ($ResolveIssueReply->load(Yii::$app->request->post())) 
		{
			$IDuser=Yii::$app->user->getId();
			$ResolveIssueReply->IDresolveIssue=$id;
			$ResolveIssueReply->IDuser=$IDuser;
			$ResolveIssueReply->date_created=ExtraFunctions::currentTime("ymd-his");
			$ResolveIssueReply->save();
			
			//prepare for sending email
			$url_to_issue=\Yii::$app->params['pippion_site']."resolve-issue/view?id=$id";
			$subject=Yii::t('default', 'There was a reply on a case');

			//if user replies on issue is admin
			if(Yii::$app->user->identity->getIsAdmin())
			{
				//send email to buyer
				$breeder=Breeder::getUserEmailAndUsername($issue->relationIDauction->relationAuctionRating->IDwinner);
				$message=Yii::t('default', 'Activitiy on resolution centar', ['0'=>$breeder["username"], '1'=>$url_to_issue]);
				ExtraFunctions::sendEmailToSomeone($breeder["email"], $breeder["username"], $subject, $message, $loadAutoLoader=true);
				//send email to seller
				$breeder=Breeder::getUserEmailAndUsername($issue->relationIDauction->relationAuctionRating->IDseller);
				$message=Yii::t('default', 'Activitiy on resolution centar', ['0'=>$breeder["username"], '1'=>$url_to_issue]);
				ExtraFunctions::sendEmailToSomeone($breeder["email"], $breeder["username"], $subject, $message, $loadAutoLoader=false);
			}
			else if($IDuser==$issue->relationIDauction->relationAuctionRating->IDseller)
			{
				//send email to winner
				$breeder=Breeder::getUserEmailAndUsername($issue->relationIDauction->relationAuctionRating->IDwinner);
				$message=Yii::t('default', 'Activitiy on resolution centar', ['0'=>$breeder["username"], '1'=>$url_to_issue]);
				ExtraFunctions::sendEmailToSomeone($breeder["email"], $breeder["username"], $subject, $message, $loadAutoLoader=true);
			}
			else
			{
				//send email to seller
				$breeder=Breeder::getUserEmailAndUsername($issue->relationIDauction->relationAuctionRating->IDseller);
				$message=Yii::t('default', 'Activitiy on resolution centar', ['0'=>$breeder["username"], '1'=>$url_to_issue]);
				ExtraFunctions::sendEmailToSomeone($breeder["email"], $breeder["username"], $subject, $message, $loadAutoLoader=true);
			}
			

			//send email to me
			ExtraFunctions::sendEmail($breeder["username"], $breeder["email"], $subject, $message, $both=true, $loadAutoLoader=false);

			
            return $this->redirect(['view', 'id' => $id]);
        } 
		else 
		{
           return $this->redirect(['view', 'id' => $id]);
        }
    }

    /**
     * Deletes an existing ResolveIssue model.
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
     * Finds the ResolveIssue model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ResolveIssue the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
		$model = ResolveIssue::findOne($id);
		$check=ResolveIssue::isUserAllowedToSeeCase($model->IDauction);
		
        if ($model !== null) 
		{
            return $model;
        }
		 else 
		 {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
