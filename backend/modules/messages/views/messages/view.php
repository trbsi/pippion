<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use backend\models\Breeder;
use backend\models\BreederImage;

/* @var $this yii\web\View */
/* @var $model backend\modules\messages\models\Messages */
if($model->user_one==Yii::$app->user->getId())
	$username=Breeder::findUserById($model->user_two);
else
	$username=Breeder::findUserById($model->user_one);
$channel=(int)$_GET["id"]; //this is ID of conversation in Messages table

$this->title = Yii::t('default', 'Inbox')." [".$username->username."]";
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="<?php echo Yii::getAlias('@web'); ?>/css/pusher-chat-widget.css" />
<script src="<?php echo Yii::getAlias('@web'); ?>/js/PusherChatWidget.js"></script>
<script>
$(function() {     
    var chatWidget = new PusherChatWidget(pusher, {
    chatEndPoint: '<?= Url::to(["/messages/messages/pusher-chat", "id"=>$channel]) ?>',
	appendTo: "#pusher_chat_widget",
	channelName: '<?= Yii::$app->params['chat_channel_name'].$channel ?>'
  });
  
	//fill input[name="nickname"] with username, later you gonna fill with real username just in case
	$( "input[name='nickname']" ).val("noname");
	
	//!!!!!!!!!! there is one FIX in PucherChatWidget!!!!!!!!!!!!!!!
});
</script>

<div class="messages-view">
  <div class="row">
    <div class="col-md-12">
      <div class="grid simple">
        <div class="grid-title no-border">
          <h4>
            <?= $username->username ?>
          </h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body no-border">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12"> <a href="<?= Url::to(['/messages/messages/view','id'=>$channel, 'loadallmessages'=>'true']); ?>" style="margin-bottom:15px;"  class="btn btn-primary btn-sm btn-small">
              <?= Yii::t('app', 'Load all messages')?>
              </a>
              <div class="pusher-chat-widget">
                <div class="pusher-chat-widget-messages">
                  <ul class="activity-stream">
                    <?php foreach($previousMessages as $value):?>
                    <li class="activity" data-activity-id="a">
                      <div class="stream-item-content">
                        <div class="image"><img src="<?php echo $BreederImage[$value->sender_id]; ?>" width="48" height="48"></div>
                        <div class="content">
                          <div class="activity-row"><span class="user-name"><a class="screen-name" title="test">
                            <?= $value->relationIDSender->username; ?>
                            </a></span></div>
                          <div class="activity-row">
                            <div class="text">
                              <?= $value->body; ?>
                            </div>
                          </div>
                          <div class="activity-row"><a class="timestamp"><span>
                            <?= $value->created_at; ?>
                            </span></a><span class="activity-actions"></span></div>
                        </div>
                      </div>
                    </li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              </div>
              <div id="pusher_chat_widget"> </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
