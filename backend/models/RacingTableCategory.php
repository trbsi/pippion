<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "{{%racing_table_cat}}".
 *
 * @property integer $ID
 * @property string $category
 * @property integer $IDuser
 *
 * @property RacingTable[] $racingTables
 * @property User $iDuser
 */
class RacingTableCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%racing_table_cat}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category', 'IDuser'], 'required'],
            [['IDuser'], 'integer'],
            [['category'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
            'category' => Yii::t('default', 'Category'),
            'IDuser' => Yii::t('default', 'Iduser'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRacingTables()
    {
        return $this->hasMany(RacingTable::className(), ['IDcategory' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDuser()
    {
        return $this->hasOne(User::className(), ['id' => 'IDuser']);
    }

	/*
	* generate dropdownlist for categories
	*/
	public static function dropDownListCategory()
	{
		$result=RacingTableCategory::find()->where(['IDuser'=>Yii::$app->user->getId()])->orderBy('category ASC')->all();
		
		return ArrayHelper::map($result, 'ID', 'category');
	}


}
