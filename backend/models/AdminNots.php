<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%admin_nots}}".
 *
 * @property integer $ID
 * @property string $title
 * @property string $body
 * @property string $date_t
 */
class AdminNots extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_nots}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'body', 'date_t'], 'required'],
            [['body'], 'string'],
            [['date_t'], 'safe'],
            [['title'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
            'title' => Yii::t('default', 'Title'),
            'body' => Yii::t('default', 'Body'),
            'date_t' => Yii::t('default', 'Date T'),
        ];
    }
}
