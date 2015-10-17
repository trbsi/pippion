<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\BreederSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="breeder-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'IDuser') ?>

    <?= $form->field($model, 'name_of_breeder') ?>

    <?= $form->field($model, 'country') ?>

    <?= $form->field($model, 'town') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'tel1') ?>

    <?php // echo $form->field($model, 'tel2') ?>

    <?php // echo $form->field($model, 'mob1') ?>

    <?php // echo $form->field($model, 'mob2') ?>

    <?php // echo $form->field($model, 'email1') ?>

    <?php // echo $form->field($model, 'email2') ?>

    <?php // echo $form->field($model, 'fax') ?>

    <?php // echo $form->field($model, 'website') ?>

    <?php // echo $form->field($model, 'verified') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('default', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('default', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
