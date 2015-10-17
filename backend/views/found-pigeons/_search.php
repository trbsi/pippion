<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\FoundPigeonsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="found-pigeons-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'IDcountry') ?>

    <?= $form->field($model, 'pigeonnumber') ?>

    <?= $form->field($model, 'sex') ?>

    <?= $form->field($model, 'year') ?>

    <?php // echo $form->field($model, 'IDuser') ?>

    <?php // echo $form->field($model, 'country') ?>

    <?php // echo $form->field($model, 'city') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'zip') ?>

    <?php // echo $form->field($model, 'image_file') ?>

    <?php // echo $form->field($model, 'returned')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('default', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('default', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
