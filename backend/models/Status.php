<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
/**
 * This is the model class for table "{{%status}}".
 *
 * @property integer $ID
 * @property integer $IDuser
 * @property string $status
 * @property integer $frompedigree
 *
 * @property Pigeon[] $pigeons
 * @property User $iDuser
 */
class Status extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%status}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDuser', 'status', 'frompedigree'], 'required'],
            [['IDuser', 'frompedigree'], 'integer'],
            [['status'], 'string', 'max' => 50]
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
			'status' => Yii::t('default', 'STATUS_STATUS'),
			'frompedigree' => Yii::t('default', 'STATUS_IZ_RODOVNIKA'),//->to je golub kojeg ti fizički ne posjeduješ ali se pojavljuje kada praviš rodovnik (ili je nečiji otac ili majka) pa da se ne pojavljuje u popisu golubova tvojih jer tog goluba nemaš, daš mu taj status i neće se prikazivati, ali će se prikazivati u rodovniku
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPigeons()
    {
        return $this->hasMany(Pigeon::className(), ['IDstatus' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDuser()
    {
        return $this->hasOne(User::className(), ['id' => 'IDuser']);
    }
	
	/*
	* Get yes/no value for dropdownlist for attribute "frompedigree"
	*/
	public function dropDownListYesNo()
	{
		$array=[
			['key'=>0, 'value'=>Yii::t('default', 'No')], 
			['key'=>1, 'value'=>Yii::t('default', 'Yes')]
		];
		return ArrayHelper::map($array, 'key', 'value');
	}
}
