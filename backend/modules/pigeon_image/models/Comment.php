<?php

namespace backend\modules\pigeon_image\models;

use Yii;
use dektrium\user\models\User;

/**
 * This is the model class for table "{{%pigeon_image_comment}}".
 *
 * @property integer $ID
 * @property integer $IDimage
 * @property integer $IDuser
 * @property string $comment
 * @property string $date_created
 *
 * @property PigeonImage $iDimage
 * @property User $iDuser
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pigeon_image_comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDimage', 'IDuser', 'comment', 'date_created'], 'required'],
            [['IDimage', 'IDuser'], 'integer'],
            [['comment'], 'string'],
            [['date_created'], 'safe']
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
            'comment' => Yii::t('default', 'Comment'),
            'date_created' => Yii::t('default', 'Date Created'),
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
