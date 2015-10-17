<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\PaymentGateway;
/* @var $this yii\web\View */
/* @var $model backend\models\PaymentGateway */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-gateway-form">
  <?php $form = ActiveForm::begin(); ?>
  <?= $form->field($model, 'gateway')->radioList(PaymentGateway::filterGateways(), ['encode'=>false] ) ?>
  <div class="form-group">
    <label class="control-label">
      <?= Yii::t('default', 'Paypal first name') ?>
    </label>
    <?= Html::textInput("FIRSTNAME", null, ['required'=>'required', 'class'=>'form-control']); ?>
  </div>
  <div class="form-group">
    <label class="control-label">
      <?= Yii::t('default', 'Paypal last name') ?>
    </label>
    <?= Html::textInput("LASTNAME", null, ['required'=>'required', 'class'=>'form-control']); ?>
  </div>
  <?= $form->field($model, 'pay_email')->input('email',['maxlength' => true]) ?>
  <div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-cons' : 'btn btn-primary btn-cons']) ?>
  </div>
  <?php ActiveForm::end(); ?>
</div>
