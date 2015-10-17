<?php

use yii\helpers\Html;
use yii\helpers\Url;
use backend\helpers\ExtraFunctions;

/* @var $this yii\web\View */
/* @var $model backend\models\Club */

$this->title = $model->club." | ".Yii::t('default', 'About us');
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Clubs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

//update link
if(!Yii::$app->user->isGuest)
	$update_link = '<a href="'.Url::to(['update', 'club_page'=>$model->club_link]).'"><span class="glyphicon glyphicon-pencil"></span></a>';
else
	$update_link=NULL;


?>
<style>
.cover_photo
{
	background-size:cover;
	background-image:url(/images/wallpapers/profilecover/<?= ExtraFunctions::Wallpapers() ?>);
	height:400px;
	width:100%;
	
}
</style>

<div class="club-create">
  <div class="row">
        <div class="col-md-12">
      <div class="grid solid blue">
        <div class="grid-body">
          <p><?= Yii::t('default', 'You need page for you club?') ?> 
          	<a href="<?=Url::to(["/user/registration/register"]) ?>" target="_blank" class="btn btn-info btn-sm btn-small"><?= Yii::t('default', 'Register') ?> </a> & 
          	<a href="<?=Url::to(["/club/club/create", "club_page"=>0]) ?>" target="_blank" class="btn btn-info btn-sm btn-small"><?= Yii::t('default', 'Create Club') ?> </a>
          </p>
        </div>
      </div>
    </div>
  </div>
  
  <div class="cover_photo"></div>
  
  <div class="row">
    <div class="col-md-12">
      <div class="grid simple">
        <div class="grid-title no-border">
          <h4>
            <?= Html::encode($this->title) ?>
          </h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body no-border"> <br>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="form-group">
                <h1><?= Yii::t('default', 'Welcome to club') ?> <?= $model->club ?> </h1>
                <h2>[<?= $model->relationIDcountry->country_name ?>/ <?= $model->city ?>]</h2>
                
                <?= $update_link ?>                
                <h3 style="display:inline-block"><?= Yii::t('default', 'About us') ?></h3>
                <p><?= nl2br(Html::encode($model->about)) ?></p>
                
                <?= $update_link ?>
                <h3 style="display:inline-block"><?= Yii::t('default', 'Contact') ?></h3>
                <p><?= nl2br(Html::encode($model->contact)) ?></p>
                
                <h3><?= Yii::t('default', 'Visits') ?></h3>
                <?= $Visits ?>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
