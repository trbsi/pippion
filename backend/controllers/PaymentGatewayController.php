<?php

namespace backend\controllers;

use Yii;
use backend\models\PaymentGateway;
use backend\helpers\ExtraFunctions;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\HttpException;

/**
 * PaymentGatewayController implements the CRUD actions for PaymentGateway model.
 */
class PaymentGatewayController extends Controller
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
                        'actions' => ['create', 'update', 'delete', 'index'],
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
     * Lists all PaymentGateway models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => PaymentGateway::find()->where(['IDuser'=>Yii::$app->user->getId()]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PaymentGateway model.
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
     * Creates a new PaymentGateway model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PaymentGateway();
		
        if ($model->load(Yii::$app->request->post())) 
		{
			$paypal_account_verification=PaymentGateway::getVerifiedStatusPayPal($model->pay_email);
			if($paypal_account_verification==PaymentGateway::PAYPAL_UNVERIFIED)
			{
				Yii::$app->session->setFlash('danger', Yii::t('default', 'Paypal account unverified')); 
				return $this->render('create', [
					'model' => $model,
				]);
			}
			else if($paypal_account_verification==PaymentGateway::PAYPAL_VERIFIED)
			{
				$model->IDuser=Yii::$app->user->getId();
				$model->save();
				return $this->redirect(['index']);
			}
        } 
		else
		 {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PaymentGateway model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) 
		{
			$paypal_account_verification=PaymentGateway::getVerifiedStatusPayPal($model->pay_email);
			if($paypal_account_verification==PaymentGateway::PAYPAL_UNVERIFIED)
			{
				Yii::$app->session->setFlash('danger', Yii::t('default', 'Paypal account unverified')); 
				return $this->render('create', [
					'model' => $model,
				]);
			}
			else if($paypal_account_verification==PaymentGateway::PAYPAL_VERIFIED)
			{
				$model->save();
				return $this->redirect(['index']);
			}
        } 
		else
		{
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PaymentGateway model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		//check if user has more then this gateway, he must have at least one gateway
		$count=PaymentGateway::find()->where(['IDuser'=>Yii::$app->user->getId()])->count();
        if($count>1)
			$this->findModel($id)->delete();
		else
			throw new HttpException(403, Yii::t('default', 'You cannot delete this gateway'));
			  
        return $this->redirect(['index']);
    }

    /**
     * Finds the PaymentGateway model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PaymentGateway the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PaymentGateway::find()->where(['IDuser'=>Yii::$app->user->getId(), 'ID'=>$id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
