<?php

namespace backend\modules\messages\models;

use Yii;
use dektrium\user\models\User;

/**
 * This is the model class for table "{{%messages_reply}}".
 *
 * @property integer $id
 * @property integer $sender_id
 * @property string $body
 * @property integer $deleted_by
 * @property string $created_at
 * @property integer $IDmessage
 *
 * @property Messages $iDmessage
 * @property User $sender
 */
class MessagesReply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%messages_reply}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sender_id', 'body', 'created_at', 'IDmessage'], 'required'],
            [['sender_id', 'deleted_by', 'IDmessage'], 'integer'],
            [['body'], 'string'],
            [['created_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
            'sender_id' => Yii::t('default', 'Sender ID'),
            'body' => Yii::t('default', 'Body'),
            'deleted_by' => Yii::t('default', 'Deleted By'),
            'created_at' => Yii::t('default', 'Created At'),
            'IDmessage' => Yii::t('default', 'Idmessage'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDmessage()
    {
        return $this->hasOne(Messages::className(), ['ID' => 'IDmessage']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDSender()
    {
        return $this->hasOne(User::className(), ['id' => 'sender_id']);
    }
}
