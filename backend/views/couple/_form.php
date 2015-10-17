<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Pigeon;
/* @var $this yii\web\View */
/* @var $model backend\models\CoupleRacing */
/* @var $form yii\widgets\ActiveForm */
$Pigeon = new Pigeon;
?>
<script>
//here are male and female required
$(document).ready(function(e) {
    $("#Mother_ID").attr("required",true);
    $("#Father_ID").attr("required",true);
});
</script>

<?php
//if this is new record dont fill dropdownlist of couples for male and female dropdownlist, otherwise fill it
if($model->isNewRecord)
{
	$id=0;
	$fill_male=NULL;
	$fill_female=NULL;
}
else
{
	$id=0;
	$fill_male=$model->male;//This is actuall ID of a pigeon in mg_pigeon
	$fill_female=$model->female;
}
?>


<?php $form = ActiveForm::begin(); ?>

<h4><strong><?= Yii::t('default', 'Male') ?></strong></h4>
<?= $Pigeon->dependentDropDownFather($id,$fill_male); ?>

<h4><strong><?= Yii::t('default', 'Female') ?></strong></h4>
<?= $Pigeon->dependentDropDownMother($id, $fill_female); ?>

<?= $form->field($model, 'couplenumber')->textInput(['maxlength' => 20]) ?>

<?= $form->field($model, 'year')->textInput()->input('number',['max' => date("Y")+5, 'min' => 1900]) ?>

<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-cons' : 'btn btn-primary btn-cons']) ?>
</div>

<?php ActiveForm::end(); ?>

