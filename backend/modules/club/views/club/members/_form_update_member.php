<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\club\models\ClubMembers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="club-members-form">
<?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'tel')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'mob')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 30]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-cons' : 'btn btn-primary btn-cons']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>