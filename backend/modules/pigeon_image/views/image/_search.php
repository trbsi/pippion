<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\pigeon_image\models\search\PigeonImageSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pigeon-image-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'IDuser') ?>

    <?= $form->field($model, 'image_file') ?>

    <?= $form->field($model, 'IDalbum') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('default', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('default', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
