<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\BroodBreedingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="brood-breeding-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'IDD') ?>

    <?= $form->field($model, 'IDuser') ?>

    <?= $form->field($model, 'IDcouple') ?>

    <?= $form->field($model, 'firstegg') ?>

    <?php // echo $form->field($model, 'hatchingdate') ?>

    <?php // echo $form->field($model, 'IDcountry') ?>

    <?php // echo $form->field($model, 'ringnumber') ?>

    <?php // echo $form->field($model, 'color') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('default', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('default', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
