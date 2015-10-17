<?php

namespace backend\models;

use Yii;
use backend\models\PigeonList;
use backend\models\CoupleRacing;
use backend\models\CoupleBreeding;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
/**
 * This is the model class for table "{{%brood_racing}}".
 *
 * @property integer $ID
 * @property string $IDD
 * @property integer $IDuser
 * @property integer $IDcouple
 * @property string $firstegg
 * @property string $hatchingdate
 * @property integer $IDcountry
 * @property string $ringnumber
 * @property string $color
 *
 * @property PigeonCountry $iDcountry
 * @property CoupleRacing $iDcouple
 * @property User $iDuser
 */
class BroodRacing extends \yii\db\ActiveRecord
{
	
	const BroodRacingName = "BroodRacing";
	const BroodBreedingName = "BroodBreeding"; 
	
	public $group_concat_country;//this attribute is necessary in CoupleRacing::broodsOfSpecificCouple() when using alias of table column

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%brood_racing}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDuser', 'IDcouple', 'firstegg', 'hatchingdate', 'IDcountry'], 'required'],
            [['IDuser', 'IDcouple', 'IDcountry'], 'integer'],
            [['firstegg', 'hatchingdate'], 'safe'],
            [['firstegg', 'hatchingdate'], 'date', 'format'=>'yyyy-MM-dd'],
            [['IDD'], 'string', 'max' => 40],
            [['ringnumber'], 'string', 'max' => 20],
            [['color'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
            'IDD' => Yii::t('default', 'Idd'),
            'IDuser' => Yii::t('default', 'Iduser'),
            'IDcouple' => Yii::t('default', 'LEGLO_NATJEC_ATTR_PAR'),
            'firstegg' => Yii::t('default', 'LEGLO_NATJEC_ATTR_PRVO_JAJE'),
            'hatchingdate' => Yii::t('default', 'LEGLO_NATJEC_ATTR_DATUM_LEZENJA'),
            'IDcountry' => Yii::t('default', 'LEGLO_NATJEC_ATTR_DRZAVA'),
            'ringnumber' => Yii::t('default', 'LEGLO_NATJEC_ATTR_BROJ_PRSTENA'),
            'color' => Yii::t('default', 'LEGLO_NATJEC_ATTR_BOJA'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDcountry()
    {
        return $this->hasOne(PigeonCountry::className(), ['ID' => 'IDcountry']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDcoupleRacing()
    {
        return $this->hasOne(CoupleRacing::className(), ['ID' => 'IDcouple']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDuser()
    {
        return $this->hasOne(User::className(), ['id' => 'IDuser']);
    }

	 /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDbrood_racing()
    {
        return $this->hasOne(PigeonList::className(), ['IDbrood_racing' => 'ID']);
    }
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDbrood_breeding()
    {
        return $this->hasOne(PigeonList::className(), ['IDbrood_breeding' => 'ID']);
    }
		
	/**
	* Check if young pigeon is in list of all pigeons. Check will be preformed by taking ID from mg_brood_breeding or mg_brood_racing and check in mg_list_of_pigeons 
	* Column IDbrood_racing or IDbrood_breeding and IDuser
	* $data - loaded model of BroodRacing/BroodBreeding so you can access relationIDbrood_racing/relationIDbrood_breeding
	*/
	public function youngPigeonInList($data,$_MODEL_CHOOSE)
	{
		if($_MODEL_CHOOSE=="BroodRacing")
			$inList=$data->relationIDbrood_racing;
		else
			$inList=$data->relationIDbrood_breeding;

		
		if(!empty($inList))
			return '<img src="'.Yii::getAlias('@web').'/images/good.png" height="20" width="20">';
		else
			return '<img src="'.Yii::getAlias('@web').'/images/error.png" height="20" width="20">';
	}

	
	

}
