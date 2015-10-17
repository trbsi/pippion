<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%couple_racing}}".
 *
 * @property integer $ID
 * @property integer $IDuser
 * @property integer $male
 * @property integer $female
 * @property string $couplenumber
 * @property string $year
 *
 * @property BroodRacing[] $broodRacings
 * @property User $iDuser
 * @property Pigeon $female0
 * @property Pigeon $male0
 */
class CoupleBreeding extends CoupleRacing
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%couple_breeding}}';
    }



    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationBroodBreeding()
    {
        return $this->hasMany(BroodBreeding::className(), ['IDcouple' => 'ID']);
    }

}
