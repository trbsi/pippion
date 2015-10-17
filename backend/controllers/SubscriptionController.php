<?php

namespace backend\controllers;

use Yii;
use backend\helpers\ExtraFunctions;
use backend\models\Subscription;
use backend\models\SubscriptionHistory;
use backend\models\search\SubscriptionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
/**
 * SubscriptionController implements the CRUD actions for Subscription model.
 */
class SubscriptionController extends Controller
{

	/*
	* execute this code before everything else
	*/
	public function beforeAction($action)
	{
		$x = new ExtraFunctions;
		$x->beforeActionTimeLanguage();
		if(Yii::$app->controller->action->id=="ipn")
		{
			 //Unfortunately I have to disable it to send PayPal IPN requests, otherwise it give me "400 Bad Request" because it cannot verify form or something
			 //http://stackoverflow.com/questions/23237377/yii2-curl-bad-request-400
			 //http://www.yiiframework.com/forum/index.php/topic/21146-yii-and-paypal-ipn-400-bad-request-via-ipn-simulator/page__gopid__268890#entry268890
			/**
			 * @var boolean whether to enable CSRF validation for the actions in this controller.
			 * CSRF validation is enabled only when both this property and [[Request::enableCsrfValidation]] are true.
			 */
			$this->enableCsrfValidation = false;
		}
		else
		{
			$x->beforeActionBreederData();
		}
		return parent::beforeAction($action);
	}

	
    public function behaviors()
    {
        return [
		    'access' => [
                'class' => AccessControl::className(),
                'rules' => [
					[
						'actions'=> ['ipn'],
						'allow'=> true,
						'roles' => ['@','?'],
					],
                    [
                        'actions' => ['view', 'update', 'delete', 'create', 'index'],
                        'allow' => true,
						'roles' => ['@'],
						'matchCallback'=>function($rule, $action) //http://www.yiiframework.com/wiki/771/rbac-super-simple-with-admin-and-user/
						{
							return Yii::$app->user->identity->getIsAdmin();
						}
                    ],
					[
						'actions'=>['info', 'success', 'generate-bank-payment'],
						'allow' => true,
						'roles'=>['@'],
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
	
	
	
	/*
	* IPN for PayPal
	*/
	public function actionIpn()
	{
		//https://github.com/paypal/ipn-code-samples/blob/master/paypal_ipn.php
		
		// CONFIG: Enable debug mode. This means we'll log requests into 'ipn.log' in the same directory.
		// Especially useful if you encounter network errors or other intermittent problems with IPN (validation).
		// Set this to 0 once you go live or don't require logging.
		define("DEBUG", 1);
		
		
		define("LOG_FILE", "./ipn.log");
		
		
		// Read POST data
		// reading posted data directly from $_POST causes serialization
		// issues with array data in POST. Reading raw POST data from input stream instead.
		$raw_post_data = file_get_contents('php://input');
		$raw_post_array = explode('&', $raw_post_data);
		$myPost = array();
		foreach ($raw_post_array as $keyval) {
			$keyval = explode ('=', $keyval);
			if (count($keyval) == 2)
				$myPost[$keyval[0]] = urldecode($keyval[1]);
		}
		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';
		if(function_exists('get_magic_quotes_gpc')) {
			$get_magic_quotes_exists = true;
		}
		foreach ($myPost as $key => $value) {
			if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
				$value = urlencode(stripslashes($value));
			} else {
				$value = urlencode($value);
			}
			$req .= "&$key=$value";
		}
		
		// Post IPN data back to PayPal to validate the IPN data is genuine
		// Without this step anyone can fake IPN data
		
		if(Subscription::PAYPAL_SANDBOX == true) {
			$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
		} else {
			$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
		}
		
		$ch = curl_init($paypal_url);
		if ($ch == FALSE) {
			return FALSE;
		}
		
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		
		if(DEBUG == true) {
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
		}
		
		// CONFIG: Optional proxy configuration
		//curl_setopt($ch, CURLOPT_PROXY, $proxy);
		//curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
		
		// Set TCP timeout to 30 seconds
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
		
		// CONFIG: Please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path
		// of the certificate as shown below. Ensure the file is readable by the webserver.
		// This is mandatory for some environments.
		
		//$cert = __DIR__ . "./cacert.pem";
		//curl_setopt($ch, CURLOPT_CAINFO, $cert);
		
		$res = curl_exec($ch);
		if (curl_errno($ch) != 0) // cURL error
			{
			if(DEBUG == true) {	
				error_log(date('[Y-m-d H:i e] '). "Can't connect to PayPal to validate IPN message: " . curl_error($ch) . PHP_EOL, 3, LOG_FILE);
			}
			curl_close($ch);
			exit;
		
		} else {
				// Log the entire HTTP response if debug is switched on.
				if(DEBUG == true) {
					error_log(date('[Y-m-d H:i e] '). "HTTP request of validation request:". curl_getinfo($ch, CURLINFO_HEADER_OUT) ." for IPN payload: $req" . PHP_EOL, 3, LOG_FILE);
					error_log(date('[Y-m-d H:i e] '). "HTTP response of validation request: $res" . PHP_EOL, 3, LOG_FILE);
				}
				curl_close($ch);
		}
		
		// Inspect IPN validation result and act accordingly
		
		// Split response headers and payload, a better way for strcmp
		$tokens = explode("\r\n\r\n", trim($res));
		$res = trim(end($tokens));
		
		if (strcmp ($res, "VERIFIED") == 0) 
		{

			// check whether the payment_status is Completed
			// check that txn_id has not been previously processed
			// check that receiver_email is your PayPal email
			// check that payment_amount/payment_currency are correct
			// process payment and mark item as paid.
		
			// assign posted variables to local variables
			//$item_name = $_POST['item_name'];
			//$item_number = $_POST['item_number'];
			//$payment_status = $_POST['payment_status'];
			//$payment_amount = $_POST['mc_gross'];
			//$payment_currency = $_POST['mc_currency'];
			//$txn_id = $_POST['txn_id'];
			//$receiver_email = $_POST['receiver_email'];
			//$payer_email = $_POST['payer_email'];
			if($_POST['receiver_email']==Yii::$app->params['paypalEmail'])
			{
				$custom=explode(",", $_POST["custom"]); //ID(ID in mg_subscription), amount (of years or months), subscription type ("month" or "year")
				$subscriptionID = $custom[0];
				$amount_to_add = $custom[1];
				$subscription_type = $custom[2];				

				if(
					($custom[2]==Subscription::SUBSCRIPTION_TYPE_MONTH && round($_POST['mc_gross'],0)==(Yii::$app->params['subscriptionFeeMonthly']*$amount_to_add)) 
					|| 
					($custom[2]==Subscription::SUBSCRIPTION_TYPE_YEAR && round($_POST['mc_gross'],0)==(Yii::$app->params['subscriptionFee']*$amount_to_add))
				)
				{
					$status = Subscription::PAYPAL_PAYMENT_PAID;
					$price=round($_POST['mc_gross'],0);	 //mc_gross is total price
					$subs=Subscription::findOne($subscriptionID);

					//EVERYTHING IS FINE, SAVE IT TO DATABASE
					$dates=ExtraFunctions::subscriptionDates($amount_to_add, $subscription_type);
					$today = $dates['today'];
					$todayplus =  $dates['todayplus'];
					//UPDATE mg_subscription, SET START AND END TIME
					$subs->start_date=$today;
					$subs->end_date=$todayplus;
					$subs->status=$status;
					$subs->price=$price;
					$subs->order_id=$_POST['txn_id'];
					$subs->subscription_type = $subscription_type;
            		$subs->amount=$amount_to_add;

					if($subs->save())
					{
						$sh=new SubscriptionHistory;
						$sh->IDsubscription=$subscriptionID ;
						$sh->start_date=$today;
						$sh->end_date=$todayplus;
						$sh->price=$price;
						$sh->status=$status;
						$sh->order_id=$_POST['txn_id'];
						$sh->subscription_type = $subscription_type;
						$sh->amount=$amount_to_add;
						$sh->save();
						
						//SEND EMAIL
						require Yii::getAlias('@common').'/phpmailer/PHPMailerAutoload.php';
						//Create a new PHPMailer instance
						$mail = new \PHPMailer;
						//Set who the message is to be sent from
						$mail->setFrom($_POST['payer_email']);
						//Set who the message is to be sent to
						$mail->addAddress(Yii::$app->params['adminEmail'], 'Subscription Payment Pippion');
						$mail->addAddress(Yii::$app->params['adminEmail2'], 'Subscription Payment Pippion');
						//Set the subject line
						$mail->Subject = "PayPal Payment";
						//Read an HTML message body from an external file, convert referenced images to embedded,
						//convert HTML into a basic plain-text alternative body
						$msgHTML="IDsubscription: ".$subs->ID."<br>".
									"User: (".$subs->IDuser. ") ".$subs->relationIDuser->username.
									"<br>Status: ".$status."<br><br>
									<strong>Buyer info</strong>:<br>
									Price:".$_POST['mc_gross']."<br>".
									"Payment status: ".$_POST['payment_status']."<br>".
									"TXN_ID: ".$_POST['txn_id']."<br>".
									"Payer email: ".$_POST['payer_email']."<br>".
									"Subscription type/amount: ".$subscription_type."/".$amount_to_add
									;
						$mail->msgHTML($msgHTML);
						$mail->send();
					} 
				}
			}
			
			if(DEBUG == true) {
				error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL, 3, LOG_FILE);
			}
		} 
		else if (strcmp ($res, "INVALID") == 0) 
		{
			$custom=explode(",", $_POST["custom"]); //ID(ID in mg_subscription), amount (of years or months), subscription type (Month or Year)
			$subscriptionID = $custom[0];
			$amount_to_add = $custom[1];
			$subscription_type = $custom[2];				
			$subs=Subscription::findOne($subscriptionID);
			require Yii::getAlias('@common').'/phpmailer/PHPMailerAutoload.php';
			//Create a new PHPMailer instance
			$mail = new \PHPMailer;
			//Set who the message is to be sent from
			$mail->setFrom("invalid-payment@pippion.com", 'PayPal Invalid Payment');
			//Set who the message is to be sent to
			$mail->addAddress(Yii::$app->params['adminEmail'], 'Invalid Payment Pippion');
			$mail->addAddress(Yii::$app->params['adminEmail2'], 'Invalid Payment Pippion');
			//Set the subject line
			$mail->Subject = "Invalid Payment";
			//Read an HTML message body from an external file, convert referenced images to embedded,
			//convert HTML into a basic plain-text alternative body
			$msgHTML="IDsubscription: ".$subs->ID."<br>".
						"User: (".$subs->IDuser. ") ".$subs->relationIDuser->username.
						"<br><br>
						<strong>Buyer info</strong>:<br>
						Price:".$_POST['mc_gross']."<br>".
						"Payment status: ".$_POST['payment_status'].
						"TXN_ID: ".$_POST['txn_id'].
						"Payer email: ".$_POST['payer_email'];

			$mail->msgHTML($msgHTML);
			$mail->send();

			// log for manual investigation
			// Add business logic here which deals with invalid IPN messages
			if(DEBUG == true) {
				error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
			}
		}
		
	}
	
	/*
	* show success when user subscribe, pays for subscription
	*/
	/*public function actionSuccess()
	{
		return $this->render('success');
	}*/

	/*
	* check if purchase id in SubscriptionHistory exists
	* order_id must be unique
	*/
	protected function checkOrderId()
	{
		$rand=mt_rand();
		$check=SubscriptionHistory::find()->where(['order_id'=>$rand])->one();
		if(!empty($check))
			return $this->checkOrderId();
		else
			return $rand;
	}
	
	/*
	* generate information about payment via bank for specific user
	* $sub_type - ("month" or "year")
	*/
	public function actionGenerateBankPayment($sub_type)
	{  
		if($sub_type!=Subscription::SUBSCRIPTION_TYPE_YEAR && $sub_type!=Subscription::SUBSCRIPTION_TYPE_MONTH)
			throw new \yii\web\HttpException(404);
		
		$ExtraFunctions = new ExtraFunctions; 
		$Subscription = new Subscription; 
 
		//accessing constant
		$status=Subscription::BANK_PAYMENT_UNPAID;    
		$price=(int)$_POST["quantity"]*(int)$_POST["amount"];
		 
		$dates=ExtraFunctions::subscriptionDates($_POST["quantity"], $sub_type);
		$today = $dates['today'];
		$todayplus =  $dates['todayplus'];
		
		//create unique order_id
		$order_id=$this->checkOrderId();
		
		//find subscription for this user
		$IDsubscription=Subscription::find()->where(['IDuser'=>Yii::$app->user->getId()])->one();
		//set it as unpaid
		//DON'T SET start_date and end_date because you have to clear the payment
		$IDsubscription->status=$status;
		$IDsubscription->order_id="$order_id";
		$IDsubscription->price=$price;
		$IDsubscription->subscription_type=$sub_type;
		$IDsubscription->amount=$_POST["quantity"];
		$IDsubscription->save();
		
		//create new order
		$model = new SubscriptionHistory;
		$model->IDsubscription=$IDsubscription->ID;
		$model->start_date=$today;
		$model->end_date=$todayplus;
		$model->price=$price;
		$model->status=$status;
		$model->order_id="$order_id";//this must be under quotes becuse order_id is varchar and $order_id is int
		$model->subscription_type = $sub_type;
        $model->amount = $_POST["quantity"];
		$model->save();
		
		//send me email about payment
		//SEND EMAIL
		require Yii::getAlias('@common').'/phpmailer/PHPMailerAutoload.php';
		//Create a new PHPMailer instance
		$mail = new \PHPMailer;
		//Set who the message is to be sent from
		$mail->setFrom('bank-payment@pippion.com', 'Bank Payment');
		//Set who the message is to be sent to
		$mail->addAddress(Yii::$app->params['adminEmail'], 'Subscription Payment Pippion');
		$mail->addAddress(Yii::$app->params['adminEmail2'], 'Subscription Payment Pippion');
		//Set the subject line
		$mail->Subject = "Bank Payment";
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body

		$msgHTML="IDsubscription: ".$IDsubscription->ID."<br>".
					"User: (".$IDsubscription->IDuser. ") ".$IDsubscription->relationIDuser->username.
					"<br>Status: ".$status."<br><br>
					<strong>Buyer info</strong>:<br>
					Price:".$price."<br>".
					"Payment status: ".$status."<br>".
					"Order ID: ".$order_id."<br>";
		$mail->msgHTML($msgHTML);
		$mail->send();

		
		return $this->redirect(['info', 'order_id'=>$order_id]);
	}
	
	/*
	* show detailed info about subscriptions and payment
	* @param $order_id is sending actionGenerateBankPayment, it is order_id in mg_subscription_history
	*/
	public function actionInfo($order_id=NULL)
	{
		//set flash if user has ordered via bank payment
		if(isset($order_id))
			Yii::$app->session->setFlash('success', Yii::t('default', 'Subscription success'));

		return $this->render('info');
	}

    /**
     * Lists all Subscription models.
     * @return mixed
	 * $bank_payment - means that user has paid via bank and admin is renewing it's subscription. It is "order_id" in mg_subscription
     */
    public function actionIndex($bank_payment=NULL)
    {
		if(isset($bank_payment))
		{
			$ExtraFunctions=new ExtraFunctions;
			$Subscription=new Subscription;
			$SubscriptionHistory=new SubscriptionHistory;

			$dates=$ExtraFunctions->subscriptionDates(0, "");
			$today = $dates['today'];
			$todayplus =  $dates['todayplus'];
				
			$s=$Subscription::find()->where(['order_id'=>$bank_payment])->one();
			$sh=$SubscriptionHistory::find()->where(['order_id'=>$bank_payment])->one();

			$s->status=$Subscription::BANK_PAYMENT_PAID;
			$s->start_date=$today;
			$s->end_date=$todayplus;
			$s->save();

			$sh->status=$Subscription::BANK_PAYMENT_PAID;
			$sh->start_date=$today;
			$sh->end_date=$todayplus;
			$sh->save();
			
		}
		
        $searchModel = new SubscriptionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Subscription model.
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
     * Creates a new Subscription model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Subscription();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ID]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Subscription model.
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
     * Deletes an existing Subscription model.
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
     * Finds the Subscription model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Subscription the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Subscription::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
