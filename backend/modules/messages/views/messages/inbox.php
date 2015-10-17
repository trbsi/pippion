<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use backend\models\BreederImage;
use backend\helpers\ExtraFunctions;
use backend\modules\messages\models\Messages;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SearchLocations */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('default', 'Inbox');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php 
//this is used when someone wants to send direct message to specific user without searching it, for example after auctions ended
if(isset($_GET["iduser"]) && isset($_GET["username"]))
	$showComposeMsg=NULL;
else
	$showComposeMsg='display:none;';
?>

<style>
/*
----------------------------------------
MESSAGES
----------------------------------------
*/
/*thumbnail size for profile pictures for example in inbox and notifications*/
.thumbnail-size {
	height:65px;
	width:65px;
}
/*inbox*/
.messages_last_updated {
	font-weight:normal;
	padding-left:15px;
	font-size:10px;
	float:right;
}
.messages_unread_message {
	background-color:#C8D5EF;
}

/*global basic info about location*/
@media (min-width: 768px) {
.gobal_basic_info {
	padding-left:12px;
}
}
.gobal_basic_info {
	display:inline-block;
	vertical-align: top;
	font-weight:normal;
	line-height:1.4em;
}
textarea {
	resize:vertical;
}
.message_wrap {
	display:block;
	padding:8px;
}
.table_row td{
	padding:0!important;
}
/*for checkbox column*/
input[name="selection[]"]
{
	margin-top:10px;
	margin-left:10px;
}
</style>
<script>
function compose()
{
	$("#div_compose").toggle();
}
</script>
<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-body no-border email-body"> <br>
        <div class="row-fluid">
          <div class="row-fluid dataTables_wrapper">
            <h2 class=" inline">
              <?= Yii::t('default', 'Inbox') ?>
            </h2>
          </div>
          <div id="email-list"> <a href="javascript:;" onclick="compose()" class="btn btn-block btn-primary"><span class="bold">
            <?= Yii::t('default','Compose') ?>
            </span></a>
            <div id="div_compose" style=" <?= $showComposeMsg ?> ">
              <?= Html::beginForm(Url::to(["compose"]), 'post', ["onsubmit"=>"setCurrentTime()"] ) ?>
              <p>
              <strong>
              <?= Yii::t('default', 'Username') ?>
              </strong>
              <input type="text" id="searchUserFilter" name="user_two"/>
              <input type="hidden"  name="current_time" class="js-setTime"/>
              </p>
              <p>
              <textarea rows="10" class="form-control" name="body"></textarea>
              </p>
              <input type="submit" value="<?= Yii::t('default', 'Send') ?>" class="btn btn-cons btn-success" />
              <?= Html::endForm() ?>
            </div>
            <?= Html::beginForm(Url::to(["delete"]), 'post', ["onsubmit"=>"areYouSure()"] ) ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                //'headerRowOptions'=>['style'=>'display:none;'],
				'tableOptions' => ['class' => 'table table-bordered'],
				'rowOptions' => ['class'=>'table_row'],
                //'filterRowOptions'=> ['style'=>'display:none;'],
                'columns' => 
                [
					['class' => 'yii\grid\CheckboxColumn'],
                    [
                        'label'=>'',
                        'format'=>'raw',
                        'value'=>function($data) 
                        {
                            $class=NULL;
                            if($data->user_one==Yii::$app->user->getId())
                            {
                                $user = $data->relationUserTwo->username;
                                $IDuser=$data->user_two;
                                if($data->user_one_read==0)
                                    $class="messages_unread_message";
                            }
                            else
                            {
                                $user = $data->relationUserOne->username;
                                $IDuser=$data->user_one;
                                if($data->user_two_read==0)
                                    $class="messages_unread_message";
                            }
							
							$lastMessage=Messages::getLastMessage($data);
                            
                            return
                            '
                            <a href="'.Url::to(['view', 'id'=>$data->ID]).'" class="'.$class.' message_wrap">
                                <img src="'.BreederImage::findUserProfilePicture($IDuser).'" class="img-responsive img-thumbnail thumbnail-size">
                                <div class="gobal_basic_info">
                                <strong>'.$user.'</strong>
								<br>'.substr($lastMessage->body,0,100).' ...
                                </div>
                                <div class="messages_last_updated" >'.ExtraFunctions::formatDate($data->last_updated, "ymd-his").'</div>
                            </a>
                            ';
                        }
                    ],
                ],
            ]); ?>
            <?= Html::submitButton(Yii::t('default', 'Delete'), ['class'=>'btn btn-cons btn-danger'] ) ?>
            <?= Html::endForm(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
