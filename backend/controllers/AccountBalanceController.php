<?php

namespace backend\controllers;

use Yii;
use backend\models\AccountBalance;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use backend\models\Auction;
use backend\models\AuctionBid;
use backend\models\Breeder;
use backend\models\PaymentGateway;
use backend\helpers\ExtraFunctions;

use PayPal\Service\AdaptivePaymentsService;
use PayPal\Types\AP\Receiver;
use PayPal\Types\AP\ReceiverList;
use PayPal\Types\AP\PayRequest;
use PayPal\Types\Common\RequestEnvelope;

/**
 * AccountBalanceController implements the CRUD actions for AccountBalance model.
 */
class AccountBalanceController extends Controller
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
                        'actions' => ['balance'],
                        'allow' => true,
						'roles' => ['@'],
                    ],
					[
						'actions'=>['transfer-money-to-seller', 'index'],
						'allow' => true,
						'roles'=>['@'],
						'matchCallback'=>function($rule, $action) ////http://www.yiiframework.com/wiki/771/rbac-super-simple-with-admin-and-user/
						{
							return Yii::$app->user->identity->getIsAdmin();
						}
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

	/* NOT USING THIS BECAUSE OF CHAINED PAYMENTS, NOW USING auction/pigeon-arrived
	* final stage to transfer money to the seller
	* $id - IDauction
	*/
/*	public function actionTransferMoneyToSeller($id)
	{
		//get the latest bid of current auction
		$latestBid=AuctionBid::findLatestBid($id);
		//get Pippion commission
		$commission=Auction::calculateCommission($latestBid->price);
		//price to pay seller
		$toSeller=$latestBid->price-$commission;
		//get seller's paypal account. IDuser is the person who started the auction, seller.
		$seller_paypal_account=PaymentGateway::find()->where(['gateway'=>'paypal', 'IDuser'=>$latestBid->relationIDauction->IDuser])->one();
		//get auction currency
		$currency=$latestBid->relationIDauction->relationIDcurrency->currency;
		
		
		if(Auction::PAYPAL_SANDBOX==true)
			$paypal_url="https://www.sandbox.paypal.com/webscr&cmd=_ap-payment&paykey=";
		else
			$paypal_url="https://www.paypal.com/webscr&cmd=_ap-payment&paykey=";
		/**
		 * SimplePay.php
		 * This file is called after the user clicks on a button during
		 * the Pay process to use PayPal's AdaptivePayments Pay features'. The
		 * user logs in to their PayPal account.
		 * Called by SimplePay.html.php
		 */
		
		/*
		 * Use the Pay API operation to transfer funds from a sender’s PayPal account to one or more receivers’ PayPal accounts. You can use the Pay API operation to make simple payments, chained payments, or parallel payments; these payments can be explicitly approved, preapproved, or implicitly approved.
		
		Use the Pay API operation to transfer funds from a sender's PayPal account to one or more receivers' PayPal accounts. You can use the Pay API operation to make simple payments, chained payments, or parallel payments; these payments can be explicitly approved, preapproved, or implicitly approved. 
		 */
		
		/*
		 * Create your PayRequest message by setting the common fields. If you want more than a simple payment, add fields for the specific kind of request, which include parallel payments, chained payments, implicit payments, and preapproved payments.
		 */
/*		require_once(Yii::getAlias('@common').'/paypal/adaptivepayments-sdk-php/PPBootStrap.php');
		
		$receiver = array();
		/*
		 * A receiver's email address 
		 */

/*		$receiver = new Receiver();
		$receiver->email = $seller_paypal_account->pay_email; 
		/*
		 *  	Amount to be credited to the receiver's account 
		 */
/*		$receiver->amount = $toSeller;
		
		$receiverList = new ReceiverList($receiver);
		
		/*
		 * The action for this request. Possible values are:
		
			PAY – Use this option if you are not using the Pay request in combination with ExecutePayment.
			CREATE – Use this option to set up the payment instructions with SetPaymentOptions and then execute the payment at a later time with the ExecutePayment.
			PAY_PRIMARY – For chained payments only, specify this value to delay payments to the secondary receivers; only the payment to the primary receiver is processed.
		
		 */
		/*
		 * The code for the currency in which the payment is made; you can specify only one currency, regardless of the number of receivers 
		 */
		/*
		 * URL to redirect the sender's browser to after canceling the approval for a payment; it is always required but only used for payments that require approval (explicit payments) 
		 */
		/*
		 * URL to redirect the sender's browser to after the sender has logged into PayPal and approved a payment; it is always required but only used if a payment requires explicit approval 
		 */
/*		 $actionType="PAY";
		 $cancelUrl=\Yii::$app->params['pippion_site']."account-balance/balance";
		 $returnUrl=\Yii::$app->params['pippion_site']."account-balance/balance";
		$payRequest = new PayRequest(new RequestEnvelope("en_US"), $actionType, $cancelUrl, $currency, $receiverList, $returnUrl);
		
		/*
		 * 	 ## Creating service wrapper object
		Creating service wrapper object to make API call and loading
		Configuration::getAcctAndConfig() returns array that contains credential and config parameters
		 */
/*		 $Configuration = new \Configuration;
		$service = new AdaptivePaymentsService($Configuration->getAcctAndConfig());
		try {
			/* wrap API method calls on the service object with a try catch */
/*			$response = $service->Pay($payRequest);
			$payKey = $response->payKey;
			$payPalURL = $paypal_url.$payKey;

			//update AccountBalance that the money was transferred
			$AB=AccountBalance::find()->where(['IDauction'=>$id])->one();
			$AB->money_transferred=1;
			$AB->save();
			
			//send email to seller
			$seller=Breeder::getUserEmailAndUsername($latestBid->relationIDauction->IDuser);
			$send_to_email=$seller['email'];
			$send_to_name=$seller['username'];
			$subject=Yii::t('default', 'Money transferred');
			$message=Yii::t('default', 'Money transferred to seller paypal', ['0'=>$seller['username'], '1'=>\Yii::$app->params['pippion_site']."auction/view?id=$id"]);
			ExtraFunctions::sendEmailToSomeone($send_to_email, $send_to_name, $subject, $message, $loadAutoLoader=true);
			
			\Yii::$app->response->redirect($payPalURL, 301)->send();
			exit;
		} 
		catch(Exception $ex) 
		{
			//require_once '../Common/Error.php';
			echo "There is error";
			exit;
		}
		/* Make the call to PayPal to get the Pay token
		 If the API call succeded, then redirect the buyer to PayPal
		to begin to authorize payment.  If an error occured, show the
		resulting errors 

	}*/
	
    /**
     * Lists all AccountBalance models.
     * @return mixed
     */
    public function actionBalance()
    {
		$tableAccountBalance=AccountBalance::getTableSchema();
		$tableAuctionBid=AuctionBid::getTableSchema();
		
		$query=AccountBalance::find();
		$query->where(['money_transferred'=>1, $tableAccountBalance->name.'.IDuser'=>Yii::$app->user->getId()]);
		$query->joinWith('relationAuctionBid');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=>['defaultOrder'=>['ID'=>SORT_DESC]]
        ]);
		
		$dataProvider->sort->attributes['current_balance'] = [
			'asc' => [$tableAuctionBid->name.'.price' => SORT_ASC],
			'desc' => [$tableAuctionBid->name.'.price' => SORT_DESC],
		];

        return $this->render('balance', [
            'dataProvider' => $dataProvider,
        ]);
    }
	
    /**
     * Lists all AccountBalance models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => AccountBalance::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AccountBalance model.
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
     * Creates a new AccountBalance model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AccountBalance();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ID]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AccountBalance model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
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

    /**
     * Deletes an existing AccountBalance model.
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
     * Finds the AccountBalance model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AccountBalance the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AccountBalance::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
