<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Pigeon;
/* @var $this yii\web\View */
/* @var $model backend\models\PigeonData */
/* @var $form yii\widgets\ActiveForm */

$Pigeon = new Pigeon;
?>



<?php $form = ActiveForm::begin(['options'=>['onSubmit'=>'setCurrentTime()']]); ?>

<div class="alert alert-info">
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

<?= $form->field($model, 'date_created', ['template' => "{input}"])->hiddenInput(['class'=>'js-setTime']) ?>
<?= $form->field($model, 'pigeondata')->textarea(['rows' => 6]) ?>
<?= $form->field($model, 'year')->textInput(['maxlength' => 4])->input('number', ['max'=>date("Y")+5, 'min'=>1900]); ?>
<div class="form-group">
	<?= Html::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>

