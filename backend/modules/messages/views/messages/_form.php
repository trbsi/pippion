<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\messages\models\Messages */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="messages-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_one')->textInput() ?>

    <?= $form->field($model, 'user_two')->textInput() ?>

    <?= $form->field($model, 'user_one_read')->textInput() ?>

    <?= $form->field($model, 'user_two_read')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
