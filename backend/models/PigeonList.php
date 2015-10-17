<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%pigeon_list}}".
 *
 * @property integer $ID
 * @property integer $IDuser
 * @property integer $IDpigeon
 * @property integer $IDmother
 * @property integer $IDfather
 * @property integer $IDbrood_racing
 * @property integer $IDbrood_breeding
 *
 * @property Pigeon $iDpigeon
 * @property User $iDuser
 */
class PigeonList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pigeon_list}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDuser', 'IDpigeon'], 'required'],
            [['IDuser', 'IDpigeon', 'IDmother', 'IDfather', 'IDbrood_racing', 'IDbrood_breeding'], 'integer']
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
            'IDpigeon' => Yii::t('default', 'Idpigeon'),
            'IDmother' => Yii::t('default', 'Idmother'),
            'IDfather' => Yii::t('default', 'Idfather'),
            'IDbrood_racing' => Yii::t('default', 'Idbrood Racing'),
            'IDbrood_breeding' => Yii::t('default', 'Idbrood Breeding'),
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
	
	 /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDfather()
    {
		//https://github.com/yiisoft/yii2/issues/2377
        return $this->hasOne(Pigeon::className(), ['ID' => 'IDfather'])->from(['pigeon_IDfather' => Pigeon::tableName()]); 
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDmother()
    {
		//https://github.com/yiisoft/yii2/issues/2377
        return $this->hasOne(Pigeon::className(), ['ID' => 'IDmother'])->from(['pigeon_IDmother' => Pigeon::tableName()]);
    }
	

}
