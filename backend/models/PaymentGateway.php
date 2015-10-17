<?php

namespace backend\models;

use Yii;
use yii\helpers\Url;

//found in samples/GetVerifiedStatus.php
use PayPal\Service\AdaptiveAccountsService;
use PayPal\Types\AA\AccountIdentifierType;
use PayPal\Types\AA\GetVerifiedStatusRequest;

/**
 * This is the model class for table "{{%payment_gateway}}".
 *
 * @property integer $ID
 * @property integer $IDuser
 * @property string $gateway
 * @property string $pay_email
 *
 * @property User $iDuser
 */
class PaymentGateway extends \yii\db\ActiveRecord
{
	const PAYPAL_VERIFIED="VERIFIED";
	const PAYPAL_UNVERIFIED="UNVERIFIED";
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payment_gateway}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDuser', 'gateway', 'pay_email'], 'required'],
            [['IDuser'], 'integer'],
            [['gateway'], 'string', 'max' => 20],
            [['pay_email'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
            'IDuser' => Yii::t('default', 'Iduser'),
            'gateway' => Yii::t('default', 'Gateway'),
            'pay_email' => Yii::t('default', 'Payment gateway email'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDuser()
    {
        return $this->hasOne(User::className(), ['id' => 'IDuser']);
    }

    /**
     * @inheritdoc
     * @return \backend\models\activequery\PaymentGatewayQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \backend\models\activequery\PaymentGatewayQuery(get_called_class());
    }

	/*
	* does user has any payment gateway connected with his account, check when he tries to bid on auction or create one
	*/
	public static function userPaymentGateway()
	{
		$r=PaymentGateway::find()->where(['IDuser'=>Yii::$app->user->getId()])->count();
		if($r==0)
		{
			\Yii::$app->response->redirect(Url::to(['/payment-gateway/create', 'addgatewaymsg'=>'true']), 301)->send();
			exit;
		}
	}
	
	/*
	* list of payment gateways 
	*/
	public static function availableGateways()
	{
		return
		[
			'paypal'=>'<img src="'.Yii::getAlias('@web').'/images/gateways/paypal.png">',
		];
	}
	
	/*
	* filter payment gateways that user already added to the database you he cannot add it until he deletes it
	**/
	public static function filterGateways()
	{
		//search gateways user has added
		$r=PaymentGateway::find()->where(['IDuser'=>Yii::$app->user->getId()])->all();
		//all gateways
		$gateways=self::availableGateways();
		foreach($r as $key=>$value)
		{
			unset($gateways[$value->gateway]);
		}
		
		return $gateways;
	}
	
	/**
	* return picture of specific gateway
	* $key - key that marks gateway like "paypal"
	*/
	public static function returnGatewayPic($key)
	{
		$array=self::availableGateways();
		return $array[$key];
	}
	
	/*
	* PayPal getVerifiedStatus
	* https://developer.paypal.com/docs/classic/api/adaptive-accounts/GetVerifiedStatus_API_Operation/
	* $emailAddress - email address to check for verification
	*/
	public static function getVerifiedStatusPayPal($emailAddress)
	{
		require_once(Yii::getAlias('@common').'/paypal/adaptiveaccounts-sdk-php/autoload.php');
		
		/********************************************
		 # GetVerifiedStatus API
		The GetVerifiedStatus API operation lets you determine whether the specified PayPal account's status is verified or unverified.
		This sample code uses AdaptiveAccounts PHP SDK to make API call.
		
		GetVerifiedStatus.php
		Calls GetVerifiedStatus API of AdaptiveAccounts webservices.
		Called by GetVerifiedStatus.html.php
		********************************************/
		$getVerifiedStatus = new GetVerifiedStatusRequest();
		
		// (Optional - must be present if the emailAddress field above
		// is not) The identifier of the PayPal account holder. If
		// present, must be one (and only one) of these account
		// identifier types: 1. emailAddress 2. mobilePhoneNumber 3.
		// accountId
		$accountIdentifier=new AccountIdentifierType();
		
		// (Required)Email address associated with the PayPal account:
		// one of the unique identifiers of the account.
		$accountIdentifier->emailAddress = $emailAddress;
		$getVerifiedStatus->accountIdentifier=$accountIdentifier;
		// (Required) The first name of the PayPal account holder.
		// Required if matchCriteria is NAME. 
		$getVerifiedStatus->firstName = $_POST["FIRSTNAME"];
						 
		// (Required) The last name of the PayPal account holder.
		// Required if matchCriteria is NAME.
		$getVerifiedStatus->lastName = $_POST["LASTNAME"];
		$getVerifiedStatus->matchCriteria = "NAME"; //$_REQUEST['matchCriteria'];
		
		// ## Creating service wrapper object
		// Creating service wrapper object to make API call
		// Configuration::getAcctAndConfig() returns array that contains credential and config parameters
		$Config=new \Configuration;
		$service  = new AdaptiveAccountsService($Config->getAcctAndConfig());
		try {
			// ## Making API call
			// invoke the appropriate method corresponding to API in service
			// wrapper object
			$response = $service->GetVerifiedStatus($getVerifiedStatus);
		} catch(Exception $ex) {
			//require_once 'Common/Error.php';
			exit;
		} 
		
		// ## Accessing response parameters
		// You can access the response parameters as shown below
		$ack = strtoupper($response->responseEnvelope->ack);
	/*	if($ack != "SUCCESS"){
			echo "<b>Error </b>";
			echo "<pre>";
			print_r($response);
			echo "</pre>";		
		} else {
			echo "<pre>";
			print_r($response);
			echo "</pre>";
			echo "<table>";
			echo "<tr><td>Ack :</td><td><div id='Ack'>$ack</div> </td></tr>";
			echo "</table>";		
		}*/
		//this is error (cannot determinate status). Error messages are different
		if($ack != "SUCCESS")
			return PaymentGateway::PAYPAL_UNVERIFIED;
		else
			return strtoupper($response->accountStatus);
			

	}
}
