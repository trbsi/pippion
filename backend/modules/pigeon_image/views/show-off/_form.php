<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\pigeon_image\models\PigeonImage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pigeon-image-form">
  <div class="row">
    <div class="col-md-12">
      <div class="grid simple">
        <div class="grid-title no-border">
          <h4>Simple <span class="semi-bold">Elemets</span></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body no-border"> <br>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
			  <?php $form = ActiveForm::begin(); ?>
              <?= $form->field($model, 'image_file')->fileInput( $options=[] ) ?>
              <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-block' : 'btn btn-primary btn-block']) ?>
              </div>
              <?php ActiveForm::end(); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
