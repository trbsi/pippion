<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\AuctionRating;

$AuctionRating= new AuctionRating;
/* @var $this yii\web\View */
/* @var $model backend\models\AuctionRating */
/* @var $form yii\widgets\ActiveForm */
?>

    <?php $form = ActiveForm::begin(); ?>

	<?php
    if($model->IDseller==Yii::$app->user->getId()):
    ?>
    <?= $form->field($model, 'winner_rating')->radioList($AuctionRating->radioButtonsStars()) ?>
    <?= $form->field($model, 'winner_feedback')->textarea(['rows' => 6]) ?>
	<?php endif; ?>
    
	<?php
    if($model->IDwinner==Yii::$app->user->getId()):
    ?>
    <?= $form->field($model, 'seller_rating')->radioList($AuctionRating->radioButtonsStars()) ?>
    <?= $form->field($model, 'seller_feedback')->textarea(['rows' => 6]) ?>
	<?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary btn-cons']) ?>
    </div>

    <?php ActiveForm::end(); ?>
