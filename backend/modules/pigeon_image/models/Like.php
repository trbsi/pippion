<?php

namespace backend\modules\pigeon_image\models;

use Yii;
use dektrium\user\models\User;
/**
 * This is the model class for table "{{%pigeon_image_like}}".
 *
 * @property integer $ID
 * @property integer $IDimage
 * @property integer $IDuser
 *
 * @property PigeonImage $iDimage
 * @property User $iDuser
 */
class Like extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pigeon_image_like}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDimage', 'IDuser'], 'required'],
            [['IDimage', 'IDuser'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
            'IDimage' => Yii::t('default', 'Idimage'),
            'IDuser' => Yii::t('default', 'Iduser'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDimage()
    {
        return $this->hasOne(PigeonImage::className(), ['ID' => 'IDimage']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDuser()
    {
        return $this->hasOne(User::className(), ['id' => 'IDuser']);
    }
}
