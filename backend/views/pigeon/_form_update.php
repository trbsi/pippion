<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\Pigeon;
/* @var $this yii\web\View */
/* @var $model backend\models\Pigeon */
/* @var $form yii\widgets\ActiveForm */

$Pigeon = new Pigeon;
?>
<script>
$(document).ready(function(e) {
    $('input[type="checkbox"]').click(function()
	{
		var checked=$(this).is(':checked');
		var father=$("#father");
		var mother=$("#mother");
		var hide_input_father=$("#hide-input-father");
		var hide_input_mother=$("#hide-input-mother");
		if(checked==true)
		{
			if(this.id=="addfather")
			{
				father.find('.alert').removeClass('alert-danger').addClass('alert-info');
				hide_input_father.toggle();
			}
			if(this.id=="addmother")
			{
				mother.find('.alert').removeClass('alert-danger').addClass('alert-info');
				hide_input_mother.toggle();
			}
		}
		else
		{
			if(this.id=="addfather")
			{
				father.find('.alert').removeClass('alert-info').addClass('alert-danger');
				hide_input_father.toggle();
			}
			
			if(this.id=="addmother")
			{
				mother.find('.alert').removeClass('alert-info').addClass('alert-danger');
				hide_input_mother.toggle();
			}

		}
	});
});
</script>
<?php $form = ActiveForm::begin(['options'=>["enctype"=>"multipart/form-data"]]); ?>
<?= $form->field($model, 'IDcountry')->dropDownList($Pigeon->dropDownListPigeonCountry()) ?>
<?= $form->field($model, 'pigeonnumber')->textInput(['maxlength' => 40]) ?>
<?= $form->field($model, 'year')->textInput(['maxlength' => 4])->input('number', ['max'=>date('Y')+5, 'min'=>1900]) ?>
<?= $form->field($model, 'color')->textInput(['maxlength' => 40]) ?>
<?= $form->field($model, 'sex')->dropDownList($Pigeon->dropDownListSex()); ?>
<?= $form->field($model, 'breed')->textInput(['maxlength' => 50]) ?>
<?= $form->field($model, 'IDstatus')->dropDownList($Pigeon->dropDownListStatus()) ?>
<?= $form->field($model, 'name')->textInput(['maxlength' => 50]) ?>

<div id="father">
  <div class="alert alert-danger">
    <div class="checkbox check-primary" style="margin-top:20px">
      <input id="addfather" name="addfather" type="checkbox" value="1">
      <label for="addfather"><?= Yii::t('default', 'Change father')?></label>
    </div>
  </div>
  <div id="hide-input-father" style="display:none">
    <?= $Pigeon->dependentDropDownFather($model->ID) ?>
  </div>
</div>
<div id="mother">
  <div class="alert alert-danger">
    <div class="checkbox check-primary" style="margin-top:20px">
      <input id="addmother" name="addmother" type="checkbox" value="1" >
      <label for="addmother"><?= Yii::t('default', 'Change mother')?></label>
    </div>
  </div>
  <div id="hide-input-mother" style="display:none">
    <?= $Pigeon->dependentDropDownMother($model->ID) ?>
  </div>
</div>
<br />
<br />
<p>
<?=  Yii::t('default', 'Picture of a pigeon') ?>
<?= Html::fileInput('pigeon_image', $value = null, $options = [] ) ?>
</p>
<p>
<?=  Yii::t('default', 'The eye') ?>
<?= Html::fileInput('eye_image', $name=null, $value = null, $options = [] ) ?>
</p>
<?= Html::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-block' : 'btn btn-primary btn-block']) ?>
<?php ActiveForm::end(); ?>
