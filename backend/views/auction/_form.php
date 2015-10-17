<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\Auction;
use backend\models\Pigeon;
use backend\models\CountryList;
use backend\models\Currency;
use backend\helpers\LinkGenerator;


$Auction = new Auction;
$Pigeon = new Pigeon;
$CountryList = new CountryList;
$Currency = new Currency;
/* @var $this yii\web\View */
/* @var $model backend\models\Auction */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(['method'=>'post', 'options'=>['enctype'=>'multipart/form-data']]); ?>

<div class="col-md-12">
  <ul class="nav nav-tabs" id="tab-01">
    <li class="active"><a href="#tab1hellowWorld">
      <?= Yii::t('default', 'Pigeon'); ?>
      </a></li>
    <li class=""><a href="#tab1FollowUs">
      <?= Yii::t('default', 'Information'); ?>
      </a></li>
  </ul>
  <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
  <div class="tab-content">
    <div class="tab-pane active" id="tab1hellowWorld">
      <div class="row column-seperation">
        <div class="col-md-12">
          <?php
            echo $Auction->sellNewOrExisting();
            ?>
          <div id="div-new" style="display:none;">

            <?= $form->field($pigeon, 'pigeonnumber')->textInput(['maxlength' => 100, 'class'=>'js-onhide-fill form-control']) ?>
            <?= $form->field($pigeon, 'IDcountry')->dropDownList($Pigeon->dropDownListPigeonCountry()) ?>
            <?= $form->field($pigeon, 'sex')->dropDownList($Pigeon->dropDownListSex()) ?>
            <?= $form->field($pigeon, 'breed')->textInput(['maxlength' => 40, 'class'=>'js-onhide-fill form-control']) ?>
            <?php require "helpers/pedigree_upload.php"; ?>
          </div>
          <div id="div-existing" style="display:none;">
            <div class="alert alert-info" style="margin:0 10px;">
              <?= $Pigeon->maleFemaleDropDownChoose() ?>
            </div>
            <div style="display:none" id="div-male">
              <?php
                   echo $Pigeon->dependentDropDownFather();
               ?>
            </div>
            <div style="display:none" id="div-female">
              <?php
                    echo $Pigeon->dependentDropDownMother();
              ?>
            </div>
            <div class="alert alert-warning"><?= Yii::t('default', 'Pedigree for existing pigeon'); ?> </div>
          </div>
            <?php require "helpers/multiple_file_upload.php"; ?>
        </div>
      </div>
    </div>
    <div class="tab-pane" id="tab1FollowUs">
      <div class="row">
        <div class="col-md-12">
        <div class="alert alert-info">
        	<span style="font-size:13px"><?php echo Yii::t('default', 'UTC time explanation')?></span><br />
            <strong><?php echo Yii::t('default', 'Time'); ?> (UTC timezone)</strong> 
            <div class="showUTCTime" style="display:inline"><script>startTime()</script></div>
        </div>
          <?= $form->field($model, 'title')->textInput(['maxlength' => 50]) ?>
          <?= $form->field($model, 'start_time')->textInput(['class'=>'js-datetimepicker form-control']) ?>
          <?= $form->field($model, 'end_time')->textInput(['class'=>'js-datetimepicker form-control']) ?>
          <?= $form->field($model, 'country')->dropDownList($CountryList->dropdownCountryList()) ?>
          <?= $form->field($model, 'city')->textInput(['maxlength' => 100]) ?>
          <?= $form->field($model, 'IDbreeder')->textInput(['id'=>'searchUserFilter']) ?> 
          <?= $form->field($model, 'start_price')->textInput()->input('number', ['step'=>'1', 'min'=>1]) ?>
          <?= $form->field($model, 'min_bid')->textInput()->input('number', ['step'=>'1', 'min'=>1]) ?>
          <?= $form->field($model, 'currency')->dropDownList(Currency::dropdownPayPalCurrency()) ?>
          <?= $form->field($model, 'information')->textarea(['rows' => 6,'placeholder'=>Yii::t('default' ,'Additional information...')]) ?>

        </div>
      </div>
    </div>
  </div>
</div>
<div class="form-group text-center">
    <h4><strong><?= LinkGenerator::auctionTermsOfUse(Yii::t('default', 'Agree to the terms'), ['style'=>'text-decoration:underline']) ?></strong></h4>
	<br />
  <?= Html::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-block' : 'btn btn-primary btn-block']) ?>
  
</div>
<?php ActiveForm::end(); ?>
