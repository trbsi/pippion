<?php

namespace backend\modules\club\models;

use Yii;

/**
 * This is the model class for table "{{%club_visits}}".
 *
 * @property integer $ID
 * @property integer $IDclub
 * @property string $ip
 *
 * @property Club $iDclub
 */
class ClubVisits extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_visits}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDclub', 'ip'], 'required'],
            [['IDclub'], 'integer'],
            [['ip'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
            'IDclub' => Yii::t('default', 'Idclub'),
            'ip' => Yii::t('default', 'Ip'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDclub()
    {
        return $this->hasOne(Club::className(), ['ID' => 'IDclub']);
    }
}
