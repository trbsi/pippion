<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\AuctionRatingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auction-rating-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'IDauction') ?>

    <?= $form->field($model, 'winner_rating') ?>

    <?= $form->field($model, 'seller_rating') ?>

    <?= $form->field($model, 'winner_feedback') ?>

    <?php // echo $form->field($model, 'seller_feedback') ?>

    <?php // echo $form->field($model, 'IDwinner') ?>

    <?php // echo $form->field($model, 'IDseller') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('default', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('default', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
