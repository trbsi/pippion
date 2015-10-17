<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Club */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="club-form">

    <?php $form = ActiveForm::begin(); ?>
	<div class="alert alert-warning"><?= Yii::t('default', 'Only letters and numbers') ?></div>
    <?= $form->field($model, 'about')->textarea(["style"=>"height:350px", 'class'=>'form-control']) ?>
    <?= $form->field($model, 'contact')->textarea(["style"=>"height:350px", 'class'=>'form-control']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-cons' : 'btn btn-primary btn-cons']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
