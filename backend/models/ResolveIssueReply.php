<?php

namespace backend\models;

use Yii;
use dektrium\user\models\User;

/**
 * This is the model class for table "{{%resolve_issue_reply}}".
 *
 * @property integer $ID
 * @property integer $IDresolveIssue
 * @property integer $IDuser
 * @property string $message
 * @property string $date_created
 *
 * @property User $iDuser
 * @property ResolveIssue $IDresolveIssue
 */
class ResolveIssueReply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%resolve_issue_reply}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDresolveIssue', 'IDuser', 'message', 'date_created'], 'required'],
            [['IDresolveIssue', 'IDuser'], 'integer'],
            [['message'], 'string'],
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
            'IDresolveIssue' => Yii::t('default', 'Idresolve An Issue'),
            'IDuser' => Yii::t('default', 'Iduser'),
            'message' => Yii::t('default', 'Message'),
            'date_created' => Yii::t('default', 'Date Created'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDuser()
    {
        return $this->hasOne(User::className(), ['id' => 'IDuser']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDresolveIssue()
    {
        return $this->hasOne(ResolveIssue::className(), ['ID' => 'IDresolveIssue']);
    }

    /**
     * @inheritdoc
     * @return \backend\models\activequery\ResolveIssueReplyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \backend\models\activequery\ResolveIssueReplyQuery(get_called_class());
    }
	
	/*
	* get the last reply
	* $IDresolveIssue - ID in ResolveIssue
	*/
	public static function lastReply($IDresolveIssue)
	{
		return ResolveIssueReply::find()->where(['IDresolveIssue'=>$IDresolveIssue])->orderBy('ID DESC')->limit(1)->one();
	}
}
