<?php

namespace backend\models;

use Yii;
use backend\helpers\ExtraFunctions;
/**
 * This is the model class for table "{{%pigeon_data}}".
 *
 * @property integer $ID
 * @property integer $IDuser
 * @property integer $IDpigeon
 * @property string $pigeondata
 * @property string $year
 * @property string $date_created
 *
 * @property Pigeon $iDpigeon
 * @property User $iDuser
 */
class PigeonData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pigeon_data}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDuser', 'IDpigeon', 'pigeondata', 'year'], 'required'],
            [['IDuser', 'IDpigeon'], 'integer'],
            [['pigeondata'], 'string'],
            [['date_created'], 'date', 'format'=>'yyyy-MM-dd HH:mm:ss'],
            [['year', 'date_created'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
            'IDuser' => Yii::t('default', 'Iduser'),
            'IDpigeon' => Yii::t('default', 'PODACI_GOLUB_ATTR_GOLUB'),
            'pigeondata' => Yii::t('default', 'PODACI_GOLUB_ATTR_PODATAK'),
            'year' => Yii::t('default', 'PODACI_GOLUB_ATTR_GODINA'),
            'date_created' => Yii::t('default', 'PODACI_GOLUB_ATTR_DATE_CREATED'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDpigeon()
    {
        return $this->hasOne(Pigeon::className(), ['ID' => 'IDpigeon']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDuser()
    {
        return $this->hasOne(User::className(), ['id' => 'IDuser']);
    }
}
