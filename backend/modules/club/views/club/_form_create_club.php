<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\CountryList;

/* @var $this yii\web\View */
/* @var $model backend\models\Club */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="club-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'club')->textInput(['maxlength' => 50]) ?>
    <?= $form->field($model, 'IDcountry')->dropDownList(CountryList::dropdownCountryList()) ?>
    <?= $form->field($model, 'city')->textInput(['maxlength' => 50]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-cons' : 'btn btn-primary btn-cons']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
