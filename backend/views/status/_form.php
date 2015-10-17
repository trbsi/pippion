<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Status;

/* @var $this yii\web\View */
/* @var $model backend\models\Status */
/* @var $form yii\widgets\ActiveForm */
$Status = new Status;
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'status')->textInput(['maxlength' => 50]) ?>

<?= $form->field($model, 'frompedigree')->dropDownList($Status->dropDownListYesNo()) ?>
<span class="help"><strong><?php echo Yii::t('default', 'STATUS_IZ_RODOVNIKA'); ?>: </strong><?php echo Yii::t('default', 'STATUS_IZ_RODOVNIKA_EXPLAIN') ?></span>
<br /><br />

<div class="form-group">
	<?= Html::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-cons' : 'btn btn-primary btn-cons']) ?>
</div>

<?php ActiveForm::end(); ?>
