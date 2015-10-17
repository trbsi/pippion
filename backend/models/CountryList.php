<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%country_list}}".
 *
 * @property integer $ID
 * @property string $country_code
 * @property string $country_name
 *
 * @property Auction[] $auctions
 * @property Breeder[] $breeders
 * @property BuyItNow[] $buyItNows
 * @property FoundPigeons[] $foundPigeons
 */
class CountryList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%country_list}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_code', 'country_name'], 'required'],
            [['country_code'], 'string', 'max' => 2],
            [['country_name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
            'country_code' => Yii::t('default', 'Country Code'),
            'country_name' => Yii::t('default', 'Country Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctions()
    {
        return $this->hasMany(Auction::className(), ['country' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBreeders()
    {
        return $this->hasMany(Breeder::className(), ['country' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuyItNows()
    {
        return $this->hasMany(BuyItNow::className(), ['country' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFoundPigeons()
    {
        return $this->hasMany(FoundPigeons::className(), ['country' => 'ID']);
    }
	
	
	/*
	* get coutries for dropdownlist
	* it generates drop down list
	*/
	public static function dropdownCountryList()
	{
		$data=CountryList::find()->orderBy('country_name ASC')->all();
 
 		return ArrayHelper::map($data,'ID', 'country_name');

	}
	
}
