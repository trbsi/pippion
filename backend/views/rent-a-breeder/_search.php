<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\RentABreederSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rent-abreeder-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'IDuser') ?>

    <?= $form->field($model, 'IDcountry') ?>

    <?= $form->field($model, 'city') ?>

    <?= $form->field($model, 'active') ?>

    <?php // echo $form->field($model, 'breeder_picture') ?>

    <?php // echo $form->field($model, 'extra_info') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('default', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('default', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
