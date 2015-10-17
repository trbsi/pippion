<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\RacingTableSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="racing-table-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'racing_date') ?>

    <?= $form->field($model, 'place_of_release') ?>

    <?= $form->field($model, 'distance') ?>

    <?= $form->field($model, 'participated_competitors') ?>

    <?php // echo $form->field($model, 'participated_pigeons') ?>

    <?php // echo $form->field($model, 'won_place') ?>

    <?php // echo $form->field($model, 'IDcategory') ?>

    <?php // echo $form->field($model, 'IDuser') ?>

    <?php // echo $form->field($model, 'IDpigeon') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('default', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('default', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
