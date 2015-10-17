<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\CoupleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="couple-racing-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'IDuser') ?>

    <?= $form->field($model, 'male') ?>

    <?= $form->field($model, 'female') ?>

    <?= $form->field($model, 'couplenumber') ?>

    <?php // echo $form->field($model, 'year') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('default', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('default', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
