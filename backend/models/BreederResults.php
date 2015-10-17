<?php

namespace backend\models;

use Yii;
use backend\models\search\BreederResultsSearch;
use yii\helpers\Html;
/**
 * This is the model class for table "{{%breeder_results}}".
 *
 * @property integer $ID
 * @property integer $IDuser
 * @property string $breeder_result
 * @property string $year
 * @property string $date_created
 *
 * @property User $iDuser
 */
class BreederResults extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%breeder_results}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDuser', 'breeder_result', 'year', 'date_created'], 'required'],
            [['IDuser'], 'integer'],
            [['breeder_result'], 'string'],
            [['date_created'], 'date', 'format'=>'yyyy-MM-dd HH:mm:ss'],
            [['year', 'date_created'], 'safe']
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
			'breeder_result' => Yii::t('default', 'UZGAJIVAC_REZ_ATTR_REZULTAT'),
			'year' => Yii::t('default', 'UZGAJIVAC_REZ_ATTR_GODINA'),
			'date_created' => Yii::t('default', 'Date Created'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDuser()
    {
        return $this->hasOne(User::className(), ['id' => 'IDuser']);
    }
	
}
