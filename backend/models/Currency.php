<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "{{%currency}}".
 *
 * @property integer $ID
 * @property string $currency
 * @property string $country
 * @property integer $paypal - if it is currency that paypal accepts
 *
 * @property Auction[] $auctions
 * @property BuyItNow[] $buyItNows
 */
class Currency extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%currency}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['currency', 'country'], 'required'],
            [['paypal'], 'integer'],
            [['currency'], 'string', 'max' => 10],
            [['country'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
            'currency' => Yii::t('default', 'Currency'),
            'country' => Yii::t('default', 'Country'),
			'paypal' => Yii::t('default', 'if it is currency that paypal accepts'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctions()
    {
        return $this->hasMany(Auction::className(), ['currency' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuyItNows()
    {
        return $this->hasMany(BuyItNow::className(), ['IDcurrency' => 'ID']);
    }
	
	/*
	* get currencies for dropdownlist
	*/
	public static function dropdownCurrency()
	{
		$result=Currency::find()->orderBy('country ASC')->all();
 
		return ArrayHelper::map($result, 'ID', function($result)
			{
				return $result->country." [".$result->currency."]";
			}
		);

	}
	
	/*
	* get currencies for dropdownlist that supports paypal
	* $value - what value to return, some dropdowns may required ID like in auction/create, some dropdowns like in deposit require currency code
	*/
	public static function dropdownPayPalCurrency($value="ID")
	{
		$result=Currency::find()->where('paypal=1')->orderBy('country ASC')->all();
 
		return ArrayHelper::map($result, $value, function($result)
			{
				return $result->country." [".$result->currency."]";
			}
		);

	}
	
}
