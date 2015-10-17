<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AccountBalance */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-balance-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'IDuser')->textInput() ?>

    <?= $form->field($model, 'money_amount')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
