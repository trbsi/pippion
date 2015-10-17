<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\pigeon_image\models\PigeonImage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pigeon-image-form">
  <?php $form = ActiveForm::begin(); ?>
  <?= $form->field($model, 'image_file')->fileInput( $options=[] ) ?>
  <div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-block' : 'btn btn-primary btn-block']) ?>
  </div>
  <?php ActiveForm::end(); ?>
</div>
