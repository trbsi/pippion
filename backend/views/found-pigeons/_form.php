<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use backend\models\Pigeon;
use backend\models\CountryList;
use backend\models\FoundPigeons;

$CountryList = new CountryList;
$Pigeon = new Pigeon;
$FoundPigeons = new FoundPigeons;

/* @var $this yii\web\View */
/* @var $model backend\models\FoundPigeons */
/* @var $form yii\widgets\ActiveForm */
?>


<?php $form = ActiveForm::begin(['options'=>array('enctype'=>'multipart/form-data')]); ?>

<h4><span class="semi-bold"><?php echo Yii::t('default', 'Leg Band');?></span></h4>
<?= $form->field($model, 'IDcountry')->dropDownList($Pigeon->dropDownListPigeonCountry()) ?>

<?= $form->field($model, 'pigeonnumber')->textInput(['maxlength' => 40]) ?>

<?= $form->field($model, 'sex')->dropDownList($Pigeon->dropDownListSex()) ?>

<?= $form->field($model, 'year')->textInput(['maxlength' => 4])->input('number') ?>


<h4><span class="semi-bold"><?php echo Yii::t('default', 'Current location of a pigeon');?></span></h4>
<div class="alert alert-info"><?= Yii::t('default', 'Why location'); ?></div>
<?= $form->field($model, 'country')->dropDownList($CountryList->dropdownCountryList()) ?>

<?= $form->field($model, 'city')->textInput(['maxlength' => 50]) ?>

<?= $form->field($model, 'address')->textInput(['maxlength' => 100]) ?>

<?= $form->field($model, 'zip')->textInput() ?>

<strong><?php echo Yii::t('default', 'Maximum mb per image', ['0'=>round(FoundPigeons::IMAGE_SIZE/1024/1024,0)]) ?></strong>
<?= $form->field($model, 'image_file')->fileInput(['class'=>'js-file-validation-image', 'accept'=>'image/*', 'name'=>'image_file']) ?>

<?php
if($model->isNewRecord==true)
{
	echo $form->field($model, 'returned', ['template' => "{input}"])->hiddenInput(['value'=>0]);
}
else
{
	echo $form->field($model, 'returned')-> checkbox([]);
}
?>
<div class="form-group">
	<?= Html::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-cons' : 'btn btn-primary btn-cons']) ?>
</div>

<?php ActiveForm::end(); ?>

