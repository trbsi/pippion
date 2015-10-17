<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Auction;
use backend\models\Pigeon;
use backend\models\CountryList;
use backend\models\Currency;
use backend\helpers\ExtraFunctions;

$Auction = new Auction;
$Pigeon = new Pigeon;
$CountryList = new CountryList;
$Currency = new Currency;
$ExtraFunctions = new ExtraFunctions;
/* @var $this yii\web\View */
/* @var $model backend\models\Auction */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(['method'=>'post', 'options'=>['enctype'=>'multipart/form-data']]); ?>

<div class="col-md-12">
  <ul class="nav nav-tabs" id="tab-01">
    <li class="active"><a href="#tabInformation">
      <?= Yii::t('default', 'Information'); ?>
      </a></li>
    <li class=""><a href="#tabImages">
      <?=  Yii::t('default', 'Image'); ?>
      </a></li>
  </ul>
  <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
  <div class="tab-content">
    <div class="tab-pane active" id="tabInformation">
      <div class="row">
        <div class="col-md-12">
          <div class="alert alert-info"> <span style="font-size:11px"><?php echo Yii::t('default', 'UTC time explanation')?></span><br />
            <strong><?php echo Yii::t('default', 'Time'); ?> (UTC timezone)</strong>
            <div class="showUTCTime" style="display:inline"><script>startTime()</script></div>
          </div>
          <?= $form->field($model, 'title')->textInput(['maxlength' => 50]) ?>
          <?php if($model->start_time > $ExtraFunctions->currentTime('ymd-his')): ?>
          <?= $form->field($model, 'start_time')->textInput(['class'=>'js-datetimepicker form-control']) ?>
          <?= $form->field($model, 'end_time')->textInput(['class'=>'js-datetimepicker form-control']) ?>
          <?php endif; ?>
          <?= $form->field($model, 'country')->dropDownList($CountryList->dropdownCountryList()) ?>
          <?= $form->field($model, 'city')->textInput(['maxlength' => 100]) ?>
          <?= $form->field($model, 'IDbreeder')->textInput(['id'=>'searchUserFilter']) ?>
          <?php if($model->start_time > $ExtraFunctions->currentTime('ymd-his')): ?>
          <?= $form->field($model, 'start_price')->textInput()->input('number', ['step'=>'any']) ?>
          <?php endif; ?>
          <?= $form->field($model, 'min_bid')->textInput()->input('number', ['step'=>'any']) ?>
          <?php if($model->start_time > $ExtraFunctions->currentTime('ymd-his')): ?>
          <?= $form->field($model, 'currency')->dropDownList($Currency->dropdownCurrency()) ?>
          <?php endif; ?>
          <?= $form->field($model, 'information')->textarea(['rows' => 6,'placeholder'=>Yii::t('default' ,'Additional information...')]) ?>
          <?php if($model->relationIDpigeon->pedigree!="auto"): ?>
           <?php require "helpers/pedigree_upload.php"; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="tab-pane" id="tabImages">
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        	<?php require "helpers/multiple_file_upload.php"; ?>
        </div>
		
      	<div class="alert alert-danger" style="clear:both"><?= Yii::t('default', 'Choose images to delete'); ?></div>
        <style>
		.scrollbar-inner 
		{
			height:200px;
		}
		</style>
        <?php
		//$value->ID is ID in mg_auction_image
        foreach($images as $value)
        {
            
            echo '
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3" style="margin-top:15px;">
                <div class="scrollbar-inner">
                    <input type="checkbox" value="'.$value->ID.'" name="delete_images[]">
                    <img src="'.Auction::UPLOAD_DIR_IMAGES.$value->image_file.'" class="img-responsive img-thumbnail">
                </div>
            </div>';
        }
        ?>
      </div>
    </div>
  </div>
</div>
<div class="form-group">
  <?= Html::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-block' : 'btn btn-primary btn-block']) ?>
</div>
<?php ActiveForm::end(); ?>
