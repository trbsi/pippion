<?php

namespace backend\controllers;

use Yii;
use backend\models\Auction;
use backend\models\AuctionBid;
use backend\models\AccountBalance;
use backend\models\Breeder;
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
use PayPal\IPN\PPIPNMessage;

/**
 * IpnController implements the CRUD actions for Auction model.
 * this is controller for IPN for paypal and other payment gateways
 */
class IpnController extends Controller
{
	public $enableCsrfValidation = false;
	
	/*
	* execute this code before everything else
	*/
	/*public function beforeAction($action)
	{
		$this->enableCsrfValidation=false;		
		return parent::beforeAction($action);
	}*/



    public function behaviors()
    {
        return 
		[
 		
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['ipn-paypal-auction', 'delay-payments-paypal-auction'],
                        'roles' => ['?']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['return-url-paypal-auction'],
                        'roles' => ['@']
                    ],
                ]
            ],

        ];
    }
	
	/*
	* IPN for PayPal when buyer sends money to "seller" (to our account), but this is chained adaptive payments
	*/
	public function actionDelayPaymentsPaypalAuction()
	{
		/**
		 * This is a sample implementation of an IPN listener
		 * that uses the SDK's PPIPNMessage class to process IPNs
		 * 
		 * This sample simply validates the incoming IPN message
		 * and logs IPN variables. In a real application, you will
		 * validate the IPN and initiate some action based on the 
		 * incoming IPN variables.
		 http://enjoysmile.com/paypal-adaptive-payments-and-ipn-part-two/
		 IPN: transaction[1].pending_reason => NONE 
		IPN: payment_request_date => Wed Jul 29 09:46:51 PDT 2015 
		IPN: return_url => http://pippion.test.thetta.com.hr/ipn/return-url-paypal-auction?IDauction=97 
		IPN: fees_payer => EACHRECEIVER 
		IPN: ipn_notification_url => http://pippion.test.thetta.com.hr/ipn/delay-payments-paypal-auction?IDauction=97 
		IPN: transaction[1].paymentType => SERVICE 
		IPN: sender_email => personal4@pippion.com 
		IPN: verify_sign => AbkfZDcFzQRqPNS3L-m.Oi-R7QjoAEkTcY1MX--8aPQqVOZzGD1NyfFL 
		IPN: transaction[1].amount => USD 90.00 
		IPN: test_ipn => 1 
		IPN: transaction[0].id_for_sender_txn => 9B415965KH814252S 
		IPN: transaction[0].receiver => admin@pippion.com 
		IPN: cancel_url => http://pippion.test.thetta.com.hr/auction/view?id=97 
		IPN: transaction[1].is_primary_receiver => false 
		IPN: transaction[0].is_primary_receiver => true 
		IPN: pay_key => AP-6HN05122JB112523E 
		IPN: action_type => PAY_PRIMARY 
		IPN: transaction[0].id => 92844088722881538 
		IPN: memo => Pippion Auction: ID 97.mt_rand()
		IPN: transaction[0].status => Completed 
		IPN: transaction[0].paymentType => SERVICE 
		IPN: transaction[1].receiver => unverified@pippion.com 
		IPN: transaction[0].status_for_sender_txn => Completed 
		IPN: transaction[0].pending_reason => NONE 
		IPN: transaction_type => Adaptive Payment PAY 
		IPN: transaction[0].amount => USD 100.00 
		IPN: status => INCOMPLETE 
		IPN: log_default_shipping_address_in_transaction => false 
		IPN: charset => Shift_JIS 
		IPN: notify_version => UNVERSIONED 
		IPN: reverse_all_parallel_payments_on_error => false
		 */
		require_once(Yii::getAlias('@common').'/paypal/adaptivepayments-sdk-php/autoload.php');
		
		// first param takes ipn data to be validated. if null, raw POST data is read from input stream
		$Configuration = new \Configuration;
		$ipnMessage = new PPIPNMessage(null, $Configuration->getConfig());
		$ipn=$ipnMessage->getRawData();

		foreach($ipn as $key => $value) 
		{
			error_log("IPN: $key => $value");
		}

		if($ipnMessage->validate()) 
		{
			$IDauction=$_GET["IDauction"]; //sent as /ipn/delay-payments-paypal-auction?IDauction=$IDauction
			$AB=AccountBalance::find()->where(['IDauction'=>$IDauction])->one();
			/*because this IPN is called 2 times:
				1. when user sends money to our account
				2. when user confirms taht  pigeon arrived and sends money to sellers' account
			IPN is not neccessary second time, so check if txn_id and payKey have been set, because they are set 1. time
			!!!!!!!BUT FROM NOW: primary receiver is 3rd party seller, I'm secondary, payment is done immediately and IPN is called just once. PayPal asked to to it like that !!!!!!!!!!*/
			if($AB->txn_id==Auction::NO_TXN_ID && $AB->payKey==Auction::NO_TXN_ID)
			{
			
				//find latest bid
				$auction_bid=AuctionBid::findLatestBid($IDauction);
				$payment_currency=$auction_bid->relationIDauction->relationIDcurrency->currency;
	
				//update AccountBalance 
				$AB->txn_id=$ipn["memo"];
				$AB->payKey=$ipn["pay_key"];
				//$AB->pigeon_arrived=1;
				$AB->money_transferred=1;				
				$AB->save();
				
				$Buyer=Breeder::getUserEmailAndUsername($auction_bid->IDbidder);
				$Seller=Breeder::getUserEmailAndUsername($auction_bid->relationIDauction->IDuser);
				
				//buyer username
				$buyer_username=$Buyer['username'];
				//seller username
				$seller_username=$Seller['username'];
				//price with currency
				$price_currency=$auction_bid->price." ".$payment_currency;
				//auction URL
				$auction_url=\Yii::$app->params['pippion_site']."auction/view?id=$IDauction";
				//account balance url
				$account_balance_url=\Yii::$app->params['pippion_site']."account-balance/balance";
				
				//send email to me, so I know that there was a payment
				$message="Kupac je platio za goluba. Novac je sjeo na račun. Aukcija ID: <a href='$auction_url'>$auction_url</a>";
				ExtraFunctions::sendEmail("Dario T", "no-reply@pippion.com", "[Auction] Buyer paid", $message, $both=true);
		
				//send email to the seller
				$send_to_email=$Seller['email']; 
				$subject=Yii::t('default', 'Money transferred'); 
				$message=Yii::t('default', 'Money transferred message to seller', 
				['0'=>$buyer_username, '1'=>$price_currency, '2'=>$auction_url, '3'=>"PayPal", '4'=>$account_balance_url]);
				ExtraFunctions::sendEmailToSomeone($send_to_email, $seller_username, $subject, $message, false);
				
				//send email to the buyer
				$send_to_email=$Buyer['email']; 
				$subject=Yii::t('default', 'Money transferred'); 
				$message=Yii::t('default', 'Money transferred message to buyer', 
				['0'=>$buyer_username, '1'=>$price_currency, '2'=>$seller_username, '3'=>$auction_url, '4'=>"PayPal",]);
				ExtraFunctions::sendEmailToSomeone($send_to_email, $buyer_username, $subject, $message, false);
			}

			//error_log("Success: Got valid IPN data");		
		}
		else 
		{
			error_log("Error: Got invalid IPN data");	
		}
	}
	
	/*
	NOT USING SINCE STARTED TO USE CHAINED PAYMENTS
	* IPN for PayPal when buyer sends money to "seller" (to our account)
	*/
	/*
	public function actionIpnPaypalAuction()
	{
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
		
		if(Auction::PAYPAL_SANDBOX == true) {
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
			$payment_status = $_POST['payment_status'];
			$payment_amount = round($_POST['mc_gross'],0);
			$payment_currency = $_POST['mc_currency'];
			$txn_id = $_POST['txn_id'];
			$receiver_email = $_POST['receiver_email'];
			//$payer_email = $_POST['payer_email'];
			
			//$_POST["custom"]; //X,Y => X=ID from Auction | Y=IDuser, user who started auction and user who you send money
			$e=explode(",",$_POST["custom"]);
			$IDauction=$e[0];
			$IDuser=$e[1];

			$txn_id_check=AccountBalance::checkForTxnId($txn_id);
			$auction_bid=AuctionBid::findLatestBid($IDauction);
			
			if($payment_status=="Completed" 
			&& $txn_id_check==0 
			&& $receiver_email==\Yii::$app->params['paypalEmail'] 
			&& $auction_bid->price==$payment_amount
			&& $auction_bid->relationIDauction->relationIDcurrency->currency==$payment_currency)
			{
				//update AccountBalance 
				$AB=AccountBalance::find()->where(['IDauction'=>$IDauction])->one();
				$AB->txn_id=$txn_id;
				$AB->save();
				
				$Buyer=Breeder::getUserEmailAndUsername($auction_bid->IDbidder);
				$Seller=Breeder::getUserEmailAndUsername($auction_bid->relationIDauction->IDuser);
				
				//buyer username
				$buyer_username=$Buyer['username'];
				//seller username
				$seller_username=$Seller['username'];
				//price with currency
				$price_currency=$auction_bid->price." ".$payment_currency;
				//auction URL
				$auction_url=\Yii::$app->params['pippion_site']."auction/view?id=$IDauction";
				
				//send email to me, so I know that there was a payment
				$message="Kupac je platio za goluba. Novac je sjeo na račun. Aukcija ID: <a href='$auction_url'>$auction_url</a>";
				ExtraFunctions::sendEmail("Dario T", "no-reply@pippion.com", "[Auction] Buyer paid", $message, $both=true);

				//send email to the seller
				$send_to_email=$Seller['email']; 
				$subject=Yii::t('default', 'Money transferred'); 
				$message=Yii::t('default', 'Money transferred message to seller', ['0'=>$buyer_username, '1'=>$price_currency, '2'=>$auction_url]);
				ExtraFunctions::sendEmailToSomeone($send_to_email, $seller_username, $subject, $message, false);
				
				//send email to the buyer
				$send_to_email=$Buyer['email']; 
				$subject=Yii::t('default', 'Money transferred'); 
				$message=Yii::t('default', 'Money transferred message to buyer', ['0'=>$buyer_username, '1'=>$price_currency, '2'=>$seller_username,  '3'=>$auction_url]);
				ExtraFunctions::sendEmailToSomeone($send_to_email, $buyer_username, $subject, $message, false);
				
			}
			
			if(DEBUG == true) 
			{
				error_log(date('[Y-m-d H:i e] '). "Verified IPN: $req ". PHP_EOL, 3, LOG_FILE);
			}
		} 
		else if (strcmp ($res, "INVALID") == 0) 
		{
			// log for manual investigation
			// Add business logic here which deals with invalid IPN messages
			if(DEBUG == true) {
				error_log(date('[Y-m-d H:i e] '). "Invalid IPN: $req" . PHP_EOL, 3, LOG_FILE);
			}
		}
		
	}*/
	
	/*
	* Thank you page when user pays for a pigeon
	*/
	public function actionReturnUrlPaypalAuction($IDauction)
	{
		return $this->render('/auction/thankyou', ['IDauction'=>$IDauction]);
	}	
	
}//END CLASS
?>