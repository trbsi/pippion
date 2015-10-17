<?php

namespace backend\models;

use Yii;
use backend\models\BroodRacing;
/**
 * This is the model class for table "{{%brood_breeding}}".
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
 * @property CoupleBreeding $iDcouple
 * @property User $iDuser
 */
class BroodBreeding extends BroodRacing
{
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%brood_breeding}}';
    }
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDcoupleBreeding()
    {
        return $this->hasOne(CoupleBreeding::className(), ['ID' => 'IDcouple']);
    }

}
