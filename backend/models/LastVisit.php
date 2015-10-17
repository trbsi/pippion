<?php

namespace backend\models;

use Yii;
use dektrium\user\models\User;
/**
 * This is the model class for table "{{%last_visit}}".
 *
 * @property integer $ID
 * @property integer $IDuser
 * @property string $last_visit
 *
 * @property User $iDuser
 */
class LastVisit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%last_visit}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDuser', 'last_visit'], 'required'],
            [['IDuser'], 'integer'],
            [['last_visit'], 'safe']
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
            'last_visit' => Yii::t('default', 'Last Visit'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDuser()
    {
        return $this->hasOne(User::className(), ['id' => 'IDuser']);
    }
}
