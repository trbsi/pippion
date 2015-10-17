<?php

namespace backend\models;

use Yii;
use dektrium\user\models\User;
/**
 * This is the model class for table "{{%auction_rating}}".
 *
 * @property integer $ID
 * @property integer $IDauction
 * @property integer $winner_rating
 * @property integer $seller_rating
 * @property string $winner_feedback
 * @property string $seller_feedback
 * @property integer $IDwinner
 * @property integer $IDseller
 *
 * @property Auction $iDauction
 * @property User $iDseller
 * @property User $iDwinner
 */
class AuctionRating extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auction_rating}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDauction', 'winner_rating', 'seller_rating', 'IDwinner', 'IDseller'], 'required'],
            [['IDauction', 'winner_rating', 'seller_rating', 'IDwinner', 'IDseller'], 'integer'],
            [['winner_feedback', 'seller_feedback'], 'string', 'max'=>500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
			'IDauction' => Yii::t('default', 'Auction'),
			'winner_rating' => Yii::t('default', 'Rate winner'), //rating that seller gives to winner (rating of winner)
			'seller_rating' => Yii::t('default', 'Rate seller'), //rating that winner gives to seller (rating of seller)
			'winner_feedback' => Yii::t('default', 'Winner Feedback'),// feedback about winner
			'seller_feedback' => Yii::t('default', 'Seller Feedback'),// feedback about seller
			'IDwinner' => Yii::t('default', 'Winner'),
			'IDseller' => Yii::t('default', 'Seller'),

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
    public function getRelationIDseller()
    {
        return $this->hasOne(User::className(), ['id' => 'IDseller']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDwinner()
    {
        return $this->hasOne(User::className(), ['id' => 'IDwinner']);
    }
	
	
	/*
	* pomaže mi da umjesto ocjene pokažem zvijezdicu u auctionrating/view i auctionrating/index
	*/
	public function ratingStars($rating)
	{
		$star=NULL;
		switch($rating)
		{
			case 1: $star="★☆☆☆☆"; break;
			case 2: $star="★★☆☆☆"; break;
			case 3: $star="★★★☆☆"; break;
			case 4: $star="★★★★☆"; break;
			case 5: $star="★★★★★"; break;
			default: $noRating=Yii::t('default', 'No rating yet');	
		}
		
		if($star==NULL)
			return $noRating;
		else
			return '<div style="font-size:18px; display:inline-block;">'.$star.'</div>';
		
	}
	
	/*
	* return stars for radio buttons
	*/
	public function radioButtonsStars()
	{
		return [1=>'★☆☆☆☆', 2=>'★★☆☆☆', 3=>'★★★☆☆', 4=>'★★★★☆', 5=>'★★★★★'];
	}
	
	
}
