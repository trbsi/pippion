<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\PigeonDataSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pigeon-data-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'IDuser') ?>

    <?= $form->field($model, 'IDpigeon') ?>

    <?= $form->field($model, 'pigeondata') ?>

    <?= $form->field($model, 'year') ?>

    <?php // echo $form->field($model, 'date_created') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('default', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('default', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
