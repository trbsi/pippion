<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\PigeonSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pigeon-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'IDuser') ?>

    <?= $form->field($model, 'pigeonnumber') ?>

    <?= $form->field($model, 'sex') ?>

    <?= $form->field($model, 'color') ?>

    <?php // echo $form->field($model, 'breed') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'year') ?>

    <?php // echo $form->field($model, 'IDcountry') ?>

    <?php // echo $form->field($model, 'IDstatus') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('default', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('default', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
