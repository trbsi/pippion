<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\club\models\search\ClubResultsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="club-results-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'IDclub') ?>

    <?= $form->field($model, 'IDuser') ?>

    <?= $form->field($model, 'pdf_file') ?>

    <?= $form->field($model, 'place') ?>

    <?php // echo $form->field($model, 'distance') ?>

    <?php // echo $form->field($model, 'year') ?>

    <?php // echo $form->field($model, 'date_created') ?>

    <?php // echo $form->field($model, 'description') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('default', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('default', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
