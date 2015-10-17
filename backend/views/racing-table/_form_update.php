<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\RacingTable */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
.font-change td, .font-change th
{
	font-size:12px;
	font-weight:bold;
}
</style>

<?php $form = ActiveForm::begin(); ?>

<div class="table-responsive">
<table class="table table-bordered">

<thead>
  <tr class="font-change">
    <th></th>
    <th></th>
    <th></th>
    <td colspan="2" class="create-participated"><?php echo strtoupper(Yii::t('default', 'RTSudjelovalo')); ?></td>
    <td colspan="1" class="create-won" ><?php echo strtoupper(Yii::t('default', 'RTOsvojeno')); ?></td>
  </tr>
  <tr class="font-change">
    <th><?php echo Yii::t('default', 'Datum'); ?></th>
    <th><?php echo Yii::t('default', 'Mjesto Pustanja'); ?></th>
    <th><?php echo Yii::t('default', 'Udaljenost'); ?></td>
    <td class="create-participated"><?php echo strtoupper(Yii::t('default', 'Sud Natjecateljsa')); ?></td>
    <td class="create-participated"><?php echo strtoupper(Yii::t('default', 'Sud Golubova')); ?></td>
    <td class="create-won"><?php echo strtoupper(Yii::t('default', 'Osv Mjesto')); ?></td>
  </tr>
  </thead>
  
  <tbody>
  <tr style="background-color:#FFFFFF;">
    <td>
        <?= $form->field($model, 'racing_date', ['template' => "{input}\n{hint}\n{error}"])->textInput(['class'=>'js-datepicker' ,'required'=>true, 'placeholder'=>Yii::t('default', 'Example: 2013-10-29')]) ?>
    </td>
    <td>
        <?= $form->field($model, 'place_of_release', ['template' => "{input}\n{hint}\n{error}"])->textInput(['maxlength' => 100,'required'=>true,]) ?>
    </td>
    <td>
        <?= $form->field($model, 'distance', ['template' => "{input}\n{hint}\n{error}"])->input('number',['style'=>'width:100px;', 'required'=>true, 'min'=>0, 'step'=>'any', 'placeholder'=>'xxx.xxx']) ?>
    </td>
    <td>
        <?= $form->field($model, 'participated_competitors', ['template' => "{input}\n{hint}\n{error}"])->input('number', ['style'=>'width:100px;', 'required'=>true, 'min'=>0]) ?>
    </td>
    <td>
        <?= $form->field($model, 'participated_pigeons', ['template' => "{input}\n{hint}\n{error}"])->input('number', ['style'=>'width:100px;', 'required'=>true, 'min'=>0]) ?>
    </td>
    <td>
        <?= $form->field($model, 'won_place', ['template' => "{input}\n{hint}\n{error}"])->input('number', ['style'=>'width:100px;', 'required'=>true, 'min'=>0]) ?>
    </td>
  </tr>
</tbody>
</table>
</div>

<div class="form-group">
	<?= Html::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-cons' : 'btn btn-primary btn-cons']) ?>
</div>

<?php ActiveForm::end(); ?>
