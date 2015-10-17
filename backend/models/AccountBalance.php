<?php

namespace backend\models;

use Yii;
use dektrium\user\models\User;
use backend\models\Auction;
use backend\models\AuctionBid;

/**
 * This is the model class for table "{{%account_balance}}".
 *
 * @property integer $ID
 * @property integer $IDuser
 * @property integer $IDauction
 * @property integer $money_transferred
 * @property integer $pigeon_arrived
 * @property string $txn_id
 * @property string $payKey
 *
 * @property Auction $iDauction
 * @property User $iDuser
 */
class AccountBalance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%account_balance}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDuser', 'IDauction', 'txn_id', 'payKey'], 'required'],
            [['IDuser', 'IDauction', 'money_transferred', 'pigeon_arrived'], 'integer'],
            [['txn_id'], 'string', 'max' => 50],
            [['payKey'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
            'IDuser' => Yii::t('default', 'Seller'), //the user you send money to, the seller
            'IDauction' => Yii::t('default', 'Auction'),
            'money_transferred' => Yii::t('default', 'Money transferred label'), //Has money been transfered to seller\'s account
            'pigeon_arrived' => Yii::t('default', 'Pigeon arrived'), //When user confirms that pigeon arrived at his address
            'txn_id' => Yii::t('default', 'Txn ID'), // Unique ID generated from PayPal, when buyer sends money to us. But it is shown to sellerin account-balance/balance  so I can use it to connect transaction of seller and buyer. I can easily detect money that buyer sent to me in my PayPal account, but I can detect who is real seller by asking from his that ID and act if neccessary
            'payKey' => Yii::t('default', 'Pay Key'), //generate by PayPal API so when I do chained payment that is the key to use to transfer money from my account to seller's
        ];
    }



    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDauction()
    {
        return $this->hasOne(Auction::className(), ['ID' => 'IDauction']);
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
	 * ADDED BY ME
     */
    public function getRelationAuctionBid()
    {
        return $this->hasOne(AuctionBid::className(), ['IDauction' => 'IDauction']);
    }


    /**
     * @inheritdoc
     * @return \backend\models\activequery\AccountBalanceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \backend\models\activequery\AccountBalanceQuery(get_called_class());
    }
	

	/*
	* check if txn_id exists in databse
	*/
	public static function checkForTxnId($txn_id)
	{
		return AccountBalance::find()->where(['txn_id'=>$txn_id])->count();	
	}
	
	/*
	* calculate current balance for the user
	* $returnOnly - "final" - return the final price only, "full" - return the whole calculation
	* $IDauction - ID in Auction
	*/
	public static function currentBalance($IDauction, $returnOnly)
	{
		$latestBid=AuctionBid::findLatestBid($IDauction);
		$commission=Auction::calculateCommission($latestBid->price);
		
		if($returnOnly=="full")
		{
			$currency=$latestBid->relationIDauction->relationIDcurrency->currency;
			return "[$currency] ".$latestBid->price.' - '.$commission.' = '.($latestBid->price-$commission);
		}
		else if($returnOnly=="final")
			return ($latestBid->price-$commission);
	}
	
	/*
	* calculate total balance for loggedin user
	*/
	public static function totalBalance()
	{
		$IDuser=Yii::$app->user->getId();
		//find all where money is transferred
		$query=AccountBalance::find()
		->with(["relationIDauction.relationIDcurrency"])
		->where(['money_transferred'=>1, 'IDuser'=>$IDuser])
		->all();
		
		$total=[];
		foreach($query as $key=>$value)
		{
			$currency=$value->relationIDauction->relationIDcurrency->currency;
			$price=self::currentBalance($value->IDauction, "final");

			//if array with specific currency as key is empty, set it to 0;
			//create array for specific currency so you can caluclate price for that currency
			if(empty($total[$currency]))
				$total[$currency]=0;
				
			$total[$currency]=$total[$currency]+$price;	
		}
		
		foreach($total as $key=>$value)
		{
			echo '
				<div class="widget-stats">
					<div class="wrapper transparent"> 
						<span class="item-title">'.$key.'</span> 
						<span class="item-count animate-number semi-bold" data-value="'.$value.'" data-animation-duration="700">'.$value.'</span> 
					</div>
				</div>';
				
		}

	}
}
