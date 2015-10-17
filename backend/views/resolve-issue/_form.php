<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ResolveIssue */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="resolve-issue-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($modelResolveIssueReply, 'message')->textarea(['rows'=>10]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-cons' : 'btn btn-primary btn-cons']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
