<?php

namespace backend\models;

use Yii;
use dektrium\user\models\User;
use backend\helpers\ExtraFunctions;
/**
 * This is the model class for table "{{%auction_bid}}".
 *
 * @property integer $ID
 * @property integer $IDauction
 * @property integer $IDbidder
 * @property double $price
 *
 * @property Auction $iDauction
 * @property User $iDbidder
 */
class AuctionBid extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auction_bid}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDauction', 'IDbidder', 'price'], 'required'],
            [['IDauction', 'IDbidder'], 'integer'],
            [['price'], 'number'],
            [['IDbidder'], 'denySeller'],
            [['price'], 'minimumBid'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
            'IDauction' => Yii::t('default', 'Idauction'),
			'IDbidder' => Yii::t('default', 'Bidder'),
			'price' => Yii::t('default', 'Price'),
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
    public function getRelationIDbidder()
    {
        return $this->hasOne(User::className(), ['id' => 'IDbidder']);
    }
	
	
	/*
	* onemogući pokretaču aukcije da bida sam sebi na aukciju
	* @params $attributes=IDbidder
	*/
	public function denySeller($attribute, $params)
	{
		//pronađi pokretača auckije, IDkorisnik
		$auctioneer=Auction::findOne($this->IDauction);
		//ako je pokretač aukcije jedan IDbidderu onda nemoj dozvoliti
		if($this->$attribute==$auctioneer->IDuser)
		{
			$this->addError($attribute, Yii::t('default', 'You cant bid on your own auction'));
			return false;
		}	
		return true;	
	}
	
	/**
	* provjeri jel cijena koju netko želi bidat najveća od prethodne
	* funkciju korisnik u rules()
	* $attribute - price
	*/
	public function minimumBid($attribute, $params)
	{
		$ExtraFunctions = new ExtraFunctions;
		//$attribute mi je trenutna cijena koju je ponuđač ponudio
		
		//uzmi min_bid i start_price
		$auction=Auction::findOne($this->IDauction);
		
		//provjeri jel ovaj bid veći od zadnjeg, no jel veći od zadnjeg za + min_bid
		$lastBid=AuctionBid::find()->where(['IDauction'=>$this->IDauction])->orderBy('ID DESC')->limit(1)->one();
		
		//provjeri jel trenutno vrijeme veće od onog end_time, tj- jel prošlo vrijeme aukcije
		if($ExtraFunctions->currentTime("ymd-his") > $auction->end_time)
		{
			$this->addError($attribute, Yii::t('default', 'Auction has ended'));
			return false;
		}

		//moguće da nije bilo bidova uopće pa ništa neće pokupit, tj. $lastBid će biti NULL
		//ako nije prazan bid, tj. ako je prije bilo bidova
		if($lastBid)
		{
			
			//ako je trenutna ponuda manja od cijene_zadnjeg_bida+mininalnog_bida
			if($this->$attribute < ($lastBid->price+$auction->min_bid))
			{
				$this->addError($attribute, Yii::t('default', 'Your bid should be higher').': '.$lastBid->price.'+'.$auction->min_bid.' '.$auction->relationIDcurrency->currency);
				return false;
			}
		}
		//ako prije nije bilo bidova
		else
		{
			//provjeri jel trenutna ponuda manja od početne_cijene/minimalno_bida
			if($this->$attribute < ($auction->start_price+$auction->min_bid))
			{
				$this->addError($attribute, Yii::t('default', 'Your first bid should be at least').': '.$auction->start_price.'+'.$auction->min_bid.' '.$auction->relationIDcurrency->currency);
				return false;
			}
		}
		
		return true;

	}
	
	/*
	* find latest bid for current auction
	*/
	public static function findLatestBid($IDauction)
	{
		return AuctionBid::find()->where(['IDauction'=>$IDauction])->with(['relationIDauction.relationIDcurrency'])->orderBy('ID DESC')->limit(1)->one();
	}

	
}
