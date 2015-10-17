<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Auction;
use backend\models\CountryList;
use backend\models\Currency;
use backend\models\Pigeon;

$CountryList = new CountryList;
$Currency = new Currency;
$Pigeon = new Pigeon;
/* @var $this yii\web\View */
/* @var $model backend\models\search\AuctionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
    <?php echo $form->field($model, 'IDuser')->textInput(['class'=>'searchUserFilter form-control']) ?>
    </div>
    
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
    <?php  echo $form->field($model, 'IDbreeder')->textInput(['class'=>'searchUserFilter form-control']) ?>
	</div>

    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
    <?= Yii::t('default', 'Broj Goluba'); ?>
    <?php echo $form->field($model, 'pigeon_search', ['template'=>"{input}"]) ?>
	</div>
    
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
     <?= Yii::t('default', 'Drzava'); ?>
    <?php echo $form->field($model, 'pigeon_country_search', ['template'=>"{input}"])->dropDownList($Pigeon->dropDownListPigeonCountry(), ['prompt'=>'']) ?>
	</div>
    
    <div style="clear:both"></div>
    
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
    <?php echo $form->field($model, 'start_time')->textInput(['class'=>'js-datepicker form-control']) ?>
	</div>
    
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
    <?php  echo $form->field($model, 'end_time')->textInput(['class'=>'js-datepicker form-control']) ?>
	</div>
    
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
    <?php  echo $form->field($model, 'start_price') ?>
	</div>
    <?php // echo $form->field($model, 'min_bid') ?>

    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
    <?php  echo $form->field($model, 'currency')->dropDownList(Currency::dropdownPayPalCurrency(), ['prompt'=>'']) ?>
    </div>

	<div style="clear:both"></div>
    
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
    <?php echo $form->field($model, 'title') ?>
	</div>

    <?php // echo $form->field($model, 'information') ?>

	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
    <?php  echo $form->field($model, 'country')->dropDownList($CountryList->dropdownCountryList(), ['prompt'=>'']) ?>
	</div>
    
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
    <?php  echo $form->field($model, 'city') ?>
	</div>
    
   
    
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="form-group">
        <?= Html::submitButton(Yii::t('default', 'Search'), ['class' => 'btn btn-primary btn-cons']) ?>
        <?= Html::resetButton(Yii::t('default', 'Reset'), ['class' => 'btn btn-default btn-cons']) ?>
    </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>