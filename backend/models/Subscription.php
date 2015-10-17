<?php

namespace backend\models;

use Yii;
use backend\helpers\ExtraFunctions;
use yii\helpers\Html;
use yii\helpers\Url;
use dektrium\user\models\User;
/**
 * This is the model class for table "{{%subscription}}".
 *
 * @property integer $ID
 * @property integer $IDuser
 * @property string $start_date
 * @property string $end_date
 * @property double $price
 * @property string $status
 * @property string $subscription_type 
 * @property integer $amount 
 *
 * @property User $iDuser
 * @property SubscriptionHistory[] $subscriptionHistories
 */
class Subscription extends \yii\db\ActiveRecord
{
	const PAYPAL_SANDBOX=false;
	const BANK_PAYMENT_UNPAID="bank_payment_unpaid"; // this is for "status" field in mg_subscription_history and mg_subscription
	const BANK_PAYMENT_PAID="bank_payment_paid"; // this is for "status" field in mg_subscription_history and mg_subscription
	const PAYPAL_PAYMENT_UNPAID="paypal_payment_unpaid"; // this is for "status" field in mg_subscription_history and mg_subscription	
	const PAYPAL_PAYMENT_PAID="paypal_payment_paid"; // this is for "status" field in mg_subscription_history and mg_subscription
	const SUBSCRIPTION_TYPE_YEAR="year";
	const SUBSCRIPTION_TYPE_MONTH="month";
	
	//this users has unlimited subscription. Array of user's ID
	//dalmasi(1), admin
	protected $unlimitedSubs=[1];
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%subscription}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['IDuser', 'start_date', 'end_date', 'price', 'status'], 'required'],
            [['IDuser', 'amount'], 'integer'],
            [['start_date', 'end_date'], 'safe'],
            [['price'], 'number'],
            [['status'], 'string', 'max' => 30],
            [['order_id'], 'string', 'max' => 100],
            [['subscription_type'], 'string', 'max' => 10]
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
            'start_date' => Yii::t('default', 'Start Date'),
            'end_date' => Yii::t('default', 'End Date'),
            'price' => Yii::t('default', 'Price'),
            'status' => Yii::t('default', 'Status'),
			'order_id' => Yii::t('default', 'Order ID'), 
			'subscription_type' => Yii::t('default', 'Subscription Type'),
            'amount' => Yii::t('default', 'Amount'), //amount of  months or years
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDuser()
    {
        return $this->hasOne(User::className(), ['id' => 'IDuser']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptionHistories()
    {
        return $this->hasMany(SubscriptionHistory::className(), ['IDsubscription' => 'ID']);
    }
	
	/*
	* Show information about subscription of user. How many time is left till end of subscription and link to information about sucription
	*/
	public function subscriptionInfo()
	{
		$ExtraFunctions = new ExtraFunctions;
		$x=Subscription::find()->where(["IDuser"=>Yii::$app->user->getId()])->one();
		$difference=$ExtraFunctions->dateDifference($ExtraFunctions->currentTime('ymd-his'), $x->end_date, "ymd-hm");

		$return=Yii::t('default', 'Subscription time left')."<br>".$difference;
		
		return 
			'<div class="bold">'.$return.'</div>
			<div class="m-t-10">'.Html::a(Yii::t('default', 'Why do I need to subscribe?'), ['/subscription/info'], ['style'=>'text-decoration:underline;', 'class'=>'badge badge-inverse']).'</div>'
			;
			
	}

	/*
	* generate buy now or subscription button
	* $type="month" or "year" subscription
	*/
	public function subscribeButton($subscription_type)
	{
		
		$ExtraFunctions = new ExtraFunctions;
		//check if user has subscription and if it is still active
		$x=Subscription::find()->where(["IDuser"=>Yii::$app->user->getId()])->one();
		
		//if difference is more than 1 year it means the kinnda have unlimited access
		//!!!!!! THE SAME AS IN  lessThanMonthSubs() !!!!!!!!
		$difference=$ExtraFunctions->dateDifference($ExtraFunctions->currentTime('ymd-his'), $x->end_date, "sign-m");
		$difference2=$ExtraFunctions->dateDifference($ExtraFunctions->currentTime('ymd-his'), $x->end_date, "sign");

		if(Subscription::PAYPAL_SANDBOX==true) 
		{
			$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
		} 
		else 
		{
			$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
		}

		//!!!!!!!!!!!!!!!!DON'T FORGET TO ENABLE IPN IN YOUR PAYPAL ACCOUNT TO http://www.pippion.com/subscription/ipn!!!!!!!!!!!!!
		//if user has left month or less of subscription let him know
		if(($difference=="+ 0" || $difference2=="-") && !in_array(Yii::$app->user->getId(),$this->unlimitedSubs))
		{
			$addSub=NULL;
			$addSub.='<div class="alert alert-danger m-t-20">'.Yii::t('default', 'Less than month left').'</div>';
			if($subscription_type==Subscription::SUBSCRIPTION_TYPE_MONTH)
			{
				$numberOf=Yii::t('default', 'Number of months');
				$amount=Yii::$app->params['subscriptionFeeMonthly'];
			}
			else
			{
				$numberOf=Yii::t('default', 'Number of years');
				$amount=Yii::$app->params['subscriptionFee'];
			}

			$addSub.=
			'
			<script>
			$(document).ready(function(e) {
				$(".paypal_form_'.$subscription_type.'").submit(function()
				{
					var amount_to_add=$(\'.quantity_'.$subscription_type.'\').val();
					var custom_paypal_field=$(\'input[name="custom"]\');
					var str = custom_paypal_field.val();
					var res = str.split(",");
					custom_paypal_field.val(res[0]+","+amount_to_add+",'.$subscription_type.'");
				});
			});
			</script>
			';
			//"custom" field is: ID (in mg_subscription), amount of months/years, type (month or year)
			$addSub.=
			'
			'.Html::beginForm($paypal_url,  'post', ["class"=>"m-t-20 text-center paypal_form_".$subscription_type.""] ).'
			<input type="number" name="quantity" min="1" class="form-control quantity_'.$subscription_type.'" placeholder="'.$numberOf.'" required="required">
			<!-- Identify your business so that you can collect the payments. -->
			<input type="hidden" name="business" value="'.Yii::$app->params['paypalEmail'].'">
			
			<!-- Specify a Buy Now button. -->
			<input type="hidden" name="cmd" value="_xclick">
			
			<!-- Specify details about the item that buyers will purchase. -->
			<input type="hidden" name="item_name" value="Pippion.com Subscription">
			<input type="hidden" name="amount" value="'.$amount.'">
			<input type="hidden" name="currency_code" value="USD">
			<input type="hidden" name="custom" value="'.$x->ID.'"> 
			<input type="hidden" name="notify_url" value="'.\Yii::$app->params['paypalIPN'].'"> 
						
			<!-- Display the payment button. -->
			<br>
			<input type="image" name="submit" border="0"
			src="https://www.paypalobjects.com/webstatic/mktg/logo/AM_mc_vs_dc_ae.jpg"
			alt="PayPal - The safer, easier way to pay online" style="border:none;" class="img-responsive center-block">
			<img alt="" border="0" width="1" height="1"
			src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
			
			<!-- Display the bank payment button. -->	
			<input class="btn btn-block btn-info" formaction="'.Url::to(["/subscription/generate-bank-payment", 'sub_type'=>$subscription_type]).'" value="'.Yii::t('default','Generate bank payment').'" type="submit">
			'.Html::endForm().'
			';
		}
		else			
			$addSub=NULL;

		return $addSub;

	}
	
	/**
	* check if subscription has ended so you can restrict access to specific section in beforeAction() function within Controllers
	*/
	public function hasSubEnded()
	{
		$ExtraFunctions = new ExtraFunctions;
		//check if user has subscription and if it is still active
		$x=Subscription::find()->where(["IDuser"=>Yii::$app->user->getId()])->one();
		
		//if difference is more than 1 year it means that user has unlimited access
		$difference=$ExtraFunctions->dateDifference($ExtraFunctions->currentTime('ymd-his'), $x->end_date, "sign");

		//if sign is minus(-) return false cause subscription has ended and they should renew it
		//redirect to subscription page
		if($difference=='-')
			return Yii::$app->getResponse()->redirect('/subscription/info');
	}
	
	/*
	* Check if less than month is left
	* It will color box on profile page (breeder/profile) to red color to indicate user to renew subscription
	*/
	public function lessThanMonthSubs()
	{
		$ExtraFunctions = new ExtraFunctions;
		$x=Subscription::find()->where(["IDuser"=>Yii::$app->user->getId()])->one();
		//if difference is more than 1 year it means that user has unlimited access
		//!!!!!! THE SAME AS IN  subscribeButton() !!!!!!!!
		$difference=$ExtraFunctions->dateDifference($ExtraFunctions->currentTime('ymd-his'), $x->end_date, "sign-m");
		$difference2=$ExtraFunctions->dateDifference($ExtraFunctions->currentTime('ymd-his'), $x->end_date, "sign");
		
		if(($difference=="+ 0" || $difference2=="-") && !in_array(Yii::$app->user->getId(),$this->unlimitedSubs) )
			$return="red";
		else
			$return="green";
			
		return $return;
	}
	
	/*
	* return information about user's subscription and payment via bank account
	*/
	public function bankAccountSubscription()
	{
		$ExtraFunctions = new ExtraFunctions;
		
		//get here database names to use in realtions
		$mg_subscription = Subscription::getTableSchema();
		$mg_subscription_history = SubscriptionHistory::getTableSchema();
 
 		//little bit of relations
		//http://www.bsourcecode.com/yiiframework2/select-query-joins/#joinWith
		$lastSubscription = SubscriptionHistory::find()->joinWith('relationIDsubscription')->where([$mg_subscription->name.'.IDuser'=>Yii::$app->user->getId(), $mg_subscription_history->name.'.status'=>self::BANK_PAYMENT_UNPAID])->orderBy('ID DESC')->limit(1)->one();

		if(!empty($lastSubscription))
		{
			$return=NULL;
			if($lastSubscription->subscription_type==Subscription::SUBSCRIPTION_TYPE_MONTH)
				$month_year=Yii::t('default', 'Months');
			else if($lastSubscription->subscription_type==Subscription::SUBSCRIPTION_TYPE_YEAR)
				$month_year=Yii::t('default', 'Years');

			$return.='<a href="http://www.pbz.hr/" target="_blank"><img src="http://www.fer.unizg.hr/images/50016213/pbz_2.png" width="227" height="76" /></a><br><br>';
			$return.="<h4>$lastSubscription->amount $month_year</h4>";
			$return.= "<strong>".Yii::t('default', 'Start date').":</strong> ".$ExtraFunctions->formatDate($lastSubscription->start_date)."<br>";
			//echo "<strong>".Yii::t('default', 'End date').":</strong> ".$ExtraFunctions->formatDate($lastSubscription->end_date)."<br>";
			$return.= "<strong>".Yii::t('default', 'Price').":</strong> ".$lastSubscription->price."$<br>";
			$return.= "<strong>".Yii::t('default', 'Order ID').":</strong> ".$lastSubscription->order_id."<br>";
			$return.= "<strong>".Yii::t('default', 'Description of payment').":</strong> <h4>$lastSubscription->order_id-$lastSubscription->ID-".$lastSubscription->relationIDsubscription->IDuser."</h4>";
			
			$return.="<br><u>".Yii::t('default', 'Our bank account info')."</u><br>";
			$return.="<strong>".Yii::t('default', 'Name and surname').":</strong> "."Dario Trbovic<br>";
			$return.="<strong>".Yii::t('default', 'Address').":</strong> "."Pridvorje, Brace Radica 25, 31418 Drenje, Croatia<br>";
			$return.="<strong>IBAN:</strong> HR49 2340 0093 1037 7973 9<br>";
			$return.="<strong>SWIFT code of PBZ bank:</strong> PBZGHR2X<br><br>";
			$return.="<h2>".Yii::t("default", "We will contact you as soon as we process the payment")."</h2>";
			
			return $return;

		}
		else
			return false;

	}
	
}
