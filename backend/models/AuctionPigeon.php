<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%auction_pigeon}}".
 *
 * @property integer $ID
 * @property string $pigeonnumber
 * @property integer $IDcountry
 * @property string $sex
 * @property string $breed
 * @property string $pedigree
 * @property string $type
 *
 * @property Auction[] $auctions
 * @property PigeonCountry $iDcountry
 * @property BuyItNow[] $buyItNows
 */
class AuctionPigeon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auction_pigeon}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pigeonnumber', 'IDcountry', 'sex'], 'required'],
            [['IDcountry'], 'integer'],
            [['pigeonnumber'], 'string', 'max' => 100],
            [['sex'], 'string', 'max' => 1],
            [['breed'], 'string', 'max' => 40],
            [['type'], 'string', 'max' => 15],
            [['pedigree'],  'file', 'extensions' => ['jpeg', 'jpg', 'png', 'gif', 'pdf'], 'maxSize' => Auction::PEDIGREE_SIZE/*4mb*/]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
            'pigeonnumber' => Yii::t('default', 'Broj Goluba'),
            'IDcountry' => Yii::t('default', 'Drzava'),
            'sex' => Yii::t('default', 'Spol'),
            'breed' => Yii::t('default', 'Rasa'),
            'pedigree' => Yii::t('default', 'Rodovnik'),
            'type' => Yii::t('default', 'Type'),
        ];
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuctions()
    {
        return $this->hasMany(Auction::className(), ['IDpigeon' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationPigeonIDcountry()
    {
        return $this->hasOne(PigeonCountry::className(), ['ID' => 'IDcountry']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuyItNows()
    {
        return $this->hasMany(BuyItNow::className(), ['IDpigeon' => 'ID']);
    }
}
