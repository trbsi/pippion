<?php

namespace backend\modules\messages\models;

use Yii;
use dektrium\user\models\User;

/**
 * This is the model class for table "{{%messages}}".
 *
 * @property integer $ID
 * @property integer $user_one
 * @property integer $user_two
 * @property integer $user_one_read
 * @property integer $user_two_read
 * @property string $last_updated 
 *
 * @property User $userOne
 * @property User $userTwo
 * @property MessagesReply[] $messagesReplies
 
 deleted_by:
NULL - nobody deleted it
0 - both users deleted it
IDuser1 - only first user deleted it
IDuser2 - only second user deleted it
 */
class Messages extends \yii\db\ActiveRecord
{	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%messages}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return 
		[
			[['user_one', 'user_two', 'user_one_read', 'user_two_read', 'last_updated'], 'required'],
			[['user_one', 'user_two', 'user_one_read', 'user_two_read'], 'integer'],
			[['last_updated'], 'safe']        
		];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
            'user_one' => Yii::t('default', 'User One'),
            'user_two' => Yii::t('default', 'User Two'),
            'user_one_read' => Yii::t('default', 'User One Read'),
            'user_two_read' => Yii::t('default', 'User Two Read'),
			'last_updated' => Yii::t('default', 'Last Updated'), 
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationUserOne()
    {
        return $this->hasOne(User::className(), ['id' => 'user_one']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationUserTwo()
    {
        return $this->hasOne(User::className(), ['id' => 'user_two']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationMessagesReply()
    {
        return $this->hasMany(MessagesReply::className(), ['IDmessage' => 'ID']);
    }
	
	/*
	* get just last message from specific conversation
	* $Messages - Messages model of loaded message
	*/
	public static function getLastMessage($Messages)
	{
		$relationMessagesReply=$Messages->relationMessagesReply;
		$lastMessage=end($relationMessagesReply);
		return $lastMessage;
	}
	
	/*
	* check for new messages everytime user refresh site (any page)
	*/
	public static function checkForNewMessages()
	{
		//get number of unread messages for second user
		$no_of_unread_msg=Messages::numberOfUnreadMessages(Yii::$app->user->getId())->count();
		//trigger notification
		if($no_of_unread_msg>=1)
		{
			ob_start();
			?>
			<script>
			$(document).ready(function()
			{ 
				notificationNewMessage(<?php echo $no_of_unread_msg ?>);
			});
            </script>
			<?php
			$output=ob_get_clean();
			echo $output;
        }
	}

	/*
	* get number of unread messages for specific user
	*/
	public static function numberOfUnreadMessages($IDuser)
	{
		$messagesTable=Messages::getTableSchema();
		$query=Messages::returnUndeletedMessages();
		$query->andWhere('(user_one=:user AND user_one_read=0) OR (user_two=:user AND user_two_read=0)',[':user'=>$IDuser]);
		$query->groupBy("$messagesTable->name.ID");
		return $query;
	}
	
	/*
	return undeleted messages
	*/
	public static function returnUndeletedMessages()
	{
		/*
		 NULL - nobody deleted it
		 0 - both users deleted it
		 IDuser1 - only first user deleted it
		 IDuser2 - only second user deleted it
		*/
		$IDuser=Yii::$app->user->getId();
		$messagesReplyTable=MessagesReply::getTableSchema();
		$query=Messages::find();
		$query->joinWith(['relationMessagesReply']); 
		$query->where("$messagesReplyTable->name.deleted_by IS NULL OR 
						($messagesReplyTable->name.deleted_by <> 0 AND $messagesReplyTable->name.deleted_by <> :user)", 
						[':user'=>$IDuser]);//find NOT deleted messages
		$query->andWhere('user_one=:user OR user_two=:user',[':user'=>$IDuser]);
		return $query;
	}
	
	/*
	* trigger notification for user who needs to receive new message
	* $sendNotificationTo - whom you are sending this notifiction (IDuser (user_two or user_one))
	* $pusher - Pusher object
	*/
	public static function triggerMsgNotification($sendNotificationTo, $pusher)
	{
		//how many unread msg does user has
		$query=Messages::numberOfUnreadMessages($sendNotificationTo)->count();
		$data['number_of_unread_msgs'] = $query; //this one has to be the same as one in: channel.bind('event_msg_X'... 
		$pusher->trigger(Yii::$app->params['pusher_channel'], Yii::$app->params['event_new_message'].$sendNotificationTo, $data);
	}

}
