<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\RacingTableCategory;
use backend\models\RacingTable;

$RacingTableCategory = new RacingTableCategory;

/* @var $this yii\web\View */
/* @var $model backend\models\RacingTableCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'category')->textInput(['maxlength' => 100]) ?>

<div class="form-group">
<?= Html::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-cons' : 'btn btn-primary btn-cons']) ?>
</div>

<?php ActiveForm::end(); ?>
