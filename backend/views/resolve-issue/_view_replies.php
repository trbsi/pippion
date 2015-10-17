<?php
use backend\models\BreederImage;
use yii\helpers\Html;
use backend\helpers\ExtraFunctions;
use backend\helpers\LinkGenerator;

?>
<?php 
$breederProfilePic=BreederImage::findUserProfilePicture($model->IDuser); 
?>
<br>
<div class="post <?= ($index%2)==0 ? "" : "form-actions-custom"; ?>">
  <div class="user-profile-pic-wrapper">
    <div class="user-profile-pic-normal"> <img width="35" height="35" data-src-retina="<?= $breederProfilePic ?>" data-src="<?= $breederProfilePic ?>" src="<?= $breederProfilePic ?>" alt=""> </div>
  </div>
  <div class="info-wrapper">
    <div class="info"> 
	<strong><?= LinkGenerator::breederLink($model->relationIDuser->username, $model->IDuser, $options=['class'=>'none']) ?></strong>
    <br />
	<?php echo nl2br(Html::encode($model->message)); ?>
      <p style="font-size:11px;">
        <?= Yii::t('default', 'Posted on') ?>
        :
        <?= ExtraFunctions::formatDate($model->date_created, "ymd-his") ?>
      </p>
    </div>
    <div class="clearfix"></div>
  </div>
  <div class="clearfix"></div>
</div>
<br>
