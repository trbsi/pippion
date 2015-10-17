<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%pigeon_country}}".
 *
 * @property integer $ID
 * @property string $country
 *
 * @property AuctionPigeon[] $auctionPigeons
 * @property BroodBreeding[] $broodBreedings
 * @property BroodRacing[] $broodRacings
 * @property FoundPigeons[] $foundPigeons
 * @property Pigeon[] $pigeons
 */
class PigeonCountry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pigeon_country}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country'], 'required'],
            [['country'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
            'country' => Yii::t('default', 'Country'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctionPigeons()
    {
        return $this->hasMany(AuctionPigeon::className(), ['IDcountry' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBroodBreedings()
    {
        return $this->hasMany(BroodBreeding::className(), ['IDcountry' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBroodRacings()
    {
        return $this->hasMany(BroodRacing::className(), ['IDcountry' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFoundPigeons()
    {
        return $this->hasMany(FoundPigeons::className(), ['IDcountry' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPigeons()
    {
        return $this->hasMany(Pigeon::className(), ['IDcountry' => 'ID']);
    }
}
