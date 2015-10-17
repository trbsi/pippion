<?php

namespace backend\modules\messages\controllers;

use Yii;
use backend\modules\messages\models\Messages;
use backend\modules\messages\models\MessagesReply;
use backend\modules\messages\models\search\MessagesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\helpers\ExtraFunctions;
use backend\models\Breeder;
use backend\models\BreederImage;

/**
 * MessagesController implements the CRUD actions for Messages model.
 */
class MessagesController extends Controller
{
	/*
	* execute this code before everything else
	*/
	public function beforeAction($action)
	{
		
		$x = new ExtraFunctions;
		$x->beforeActionTimeLanguage();
		$x->beforeActionBreederData();
		
		return parent::beforeAction($action);
	}

    public function behaviors()
    {
        return [
              'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['inbox', 'view', 'pusher-chat', 'compose', 'delete'],
                        'roles' => ['@']
                    ],
                ]
            ],
           'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

	/*
	* composee new message
	*/
	public function actionCompose()
	{
		$sendNotificationTo=$user_two=(int) $_POST["user_two"];
		
		//first check if conversation between those users exists
		$conversationExists=Messages::find()->where('(user_one=:user1 AND user_two=:user2) OR (user_one=:user2 AND user_two=:user1)', [':user1'=>Yii::$app->user->getId(), ':user2'=>$user_two])->one();
		
		//if conversation between 2 users exists
		if(!empty($conversationExists))
		{
			$IDmessage=$conversationExists->ID;

			$MessagesReply = new MessagesReply;
			$MessagesReply->sender_id = Yii::$app->user->getId();
			$MessagesReply->body = $_POST["body"];
			$MessagesReply->created_at = $_POST["current_time"]; //it is not UTC, it is user's local time
			$MessagesReply->IDmessage = $IDmessage;
			$MessagesReply->save();
			
			if($conversationExists->user_one==Yii::$app->user->getId())
			{
				$conversationExists->user_two_read=0;
			}
			else
			{
				$conversationExists->user_one_read=0;
			}
			$conversationExists->save();

		}
		else
		{
			$Messages = new Messages;
			$Messages->user_one = Yii::$app->user->getId();
			$Messages->user_two = $user_two;
			$Messages->user_one_read = 1;
			$Messages->user_two_read = 0;
			$Messages->last_updated = $_POST["current_time"]; //it is not UTC, it is user's local time
			$Messages->save();
			$IDmessage=$Messages->ID;
			
			$MessagesReply = new MessagesReply;
			$MessagesReply->sender_id = Yii::$app->user->getId();
			$MessagesReply->body = $_POST["body"];
			$MessagesReply->created_at = $_POST["current_time"]; //it is not UTC, it is user's local time
			$MessagesReply->IDmessage = $IDmessage;
			$MessagesReply->save();
		}
		
		require_once(Yii::getAlias('@common').'/pusher/Pusher.php');
		$pusher = new \Pusher(\Yii::$app->params['app_key'], \Yii::$app->params['app_secret'], \Yii::$app->params['app_id']);
		//trigger notification for that icon notification in header of site
		Messages::triggerMsgNotification($sendNotificationTo, $pusher);
		
		return $this->redirect(['view', 'id'=>$IDmessage]);
	}
	
	/*
	* send request here for chat and send messages from /messages/view
	*/
	public function actionPusherChat()
	{
		require_once(Yii::getAlias('@common').'/pusher/Pusher.php');
		$pusher = new \Pusher(\Yii::$app->params['app_key'], \Yii::$app->params['app_secret'], \Yii::$app->params['app_id']);
		
		//this is to set channel, cause channel is always messages_chat_+id of conversation, ID  in Messages table
		$IDmessage=(int)$_GET['id'];
		$channel_name = Yii::$app->params['chat_channel_name'].$IDmessage;
		
		$chat_info = $_POST['chat_info'];
		if( !isset($_POST['chat_info']) )
		{
		  header("HTTP/1.0 400 Bad Request");
		  echo('chat_info must be provided');
		}
		
		$user=Breeder::findUserById(Yii::$app->user->getId());
		$options = [];
		$options['displayName'] = htmlspecialchars($user->username);
		$options['text'] = htmlspecialchars($chat_info['text']);
		$options['local_time'] = htmlspecialchars($chat_info['local_time']);

		$data = 
		[
			'id' => uniqid(),
			'body' => $options['text'],
			'published' => date('r'),
			'type' => "chat-message",
			'actor' => 
			[
				'displayName' =>$options['displayName'] ,
				'objectType' => 'person',
				'image' => 
				[
					'url' => BreederImage::findUserProfilePicture(Yii::$app->user->getId()),
					'width' => 48,
					'height' => 48
				]
			]
		];

		
		//show message in chat box
		//chat_message has to be like this because in web\js\PusherChatWidget.js it is also called like that
		$response = $pusher->trigger($channel_name, 'chat_message', $data, null, true);
		
		//save message to database
		$MessagesReply = new MessagesReply;
		$MessagesReply->sender_id = Yii::$app->user->getId();
        $MessagesReply->body = $options['text'];
        $MessagesReply->created_at = $options['local_time'];
        $MessagesReply->IDmessage = $IDmessage;
		$MessagesReply->save();

		//set as unread
		$Messages=Messages::find()->where(['ID'=>$IDmessage])->one();
		if($Messages->user_one==Yii::$app->user->getId())
		{
			$sendNotificationTo=$Messages->user_two; //to who to send notification about new message
			$Messages->user_two_read=0;
		}
		else
		{
			$sendNotificationTo=$Messages->user_one; //to who to send notification about new message
			$Messages->user_one_read=0;
		}
		$Messages->last_updated=$options['local_time'];
		$Messages->save();
		
		//trigger notification for that icon notification in header of site
		Messages::triggerMsgNotification($sendNotificationTo, $pusher);
		
	}

	
	/*
	* list of all messages
	*/
	public function actionInbox()
	{
        $searchModel = new MessagesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
        return $this->render('inbox', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
	
    /**
     * Lists all Messages models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MessagesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Messages model.
     * @param integer $id
     * @return mixed
	 * $id - ID in Messages
     */
    public function actionView($id, $loadallmessages=NULL)
    {
		$id=(int)$id;
		$messagesTable=Messages::getTableSchema();
		$model=$this->findViewModel($id);
		$IDuser=Yii::$app->user->getId();
		
		//get images of people in this conversation so you can just use them from array without querying database
		$BreederImage[$model->user_one]=BreederImage::findUserProfilePicture($model->user_one);
		$BreederImage[$model->user_two]=BreederImage::findUserProfilePicture($model->user_two);
		
		if($model->user_one==$IDuser)
			$model->user_one_read=1;
		else
			$model->user_two_read=1;
		$model->save();
		
		$messagesReplyTable=MessagesReply::getTableSchema();
		$previousMessagesQuery=MessagesReply::find();
		$previousMessagesQuery->where("deleted_by IS NULL OR (deleted_by <> 0 AND deleted_by <> :user)", [':user'=>$IDuser]);//find NOT deleted messages
		$previousMessagesQuery->with(["relationIDSender"]);
		if($loadallmessages==NULL)
		{
			//get the rest of messages, last 5 but in reverse order
			//http://stackoverflow.com/questions/9424327/mysql-select-from-table-get-newest-last-10-rows-in-table
			$previousMessages=$previousMessagesQuery
			->from("(SELECT * FROM $messagesReplyTable->name WHERE IDmessage=$id ORDER BY created_at DESC LIMIT 5) AS temp_table")
			->orderBy('created_at ASC')
			->all();
		}
		else
		{
			//get all messages
			$previousMessages=$previousMessagesQuery->orderBy('created_at ASC')->all();
		}

        return $this->render('view', [
            'model' => $model,
			'previousMessages'=>$previousMessages,
			'BreederImage'=>$BreederImage,
        ]);
    }


	//$id - ID in Messages
    protected function findViewModel($id)
    {
		$model = Messages::find()->where('ID=:id AND (user_one=:IDuser OR user_two=:IDuser)', [':id'=>$id, ':IDuser'=>Yii::$app->user->getId()])->one();
        if ($model !== null) 
		{
            return $model;
        } 
		else 
		{
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new Messages model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Messages();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ID]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Messages model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Messages model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
	 * updateAll( $attributes, $condition = '', $params = [] )
		deleted_by:
		NULL - nobody deleted it
		0 - both users deleted it
		IDuser1 - only first user deleted it
		IDuser2 - only second user deleted it
     */
    public function actionDelete()
    {
		foreach($_POST["selection"] as $value)
		{
			MessagesReply::updateAll(['deleted_by' => Yii::$app->user->getId()], 'deleted_by IS NULL AND IDmessage=:ID', [':ID'=>$value]);
			MessagesReply::updateAll(['deleted_by' => 0], 'deleted_by IS NOT NULL AND deleted_by <> 0 AND deleted_by <> :user AND IDmessage=:ID', [':user'=>Yii::$app->user->getId(), ':ID'=>$value]);
		}

        return $this->redirect(['inbox']);
    }

    /**
     * Finds the Messages model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Messages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Messages::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
