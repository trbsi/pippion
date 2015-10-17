<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\modules\club\models\ClubResults;

/* @var $this yii\web\View */
/* @var $model backend\modules\club\models\ClubResults */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="club-results-form">

    <?php $form = ActiveForm::begin(['options'=>["enctype"=>"multipart/form-data"]]); ?>

	<?php if($ClubResults->isNewRecord==true): ?>
    <?= $form->field($ClubResults, 'pdf_file')->fileInput() ?>
    <?php endif; ?>

    <?= $form->field($ClubResults, 'result_type')->dropDownList(ClubResults::resultType(), [] ) ?>
   
    <?= $form->field($ClubResults, 'place')->textInput(['maxlength' => 50]) ?>

   <div class="col-sm-6"><?= $form->field($ClubResults, 'distance')->textInput(['maxlength' => 15]) ?></div>
    <div class="col-sm-6"><?= $form->field($ClubResults, 'distance_type')->dropDownList(ClubResults::dropDownListDistanceType(), []) ?></div>

    <?= $form->field($ClubResults, 'year')->input('number', ['min' => 1900, 'value'=>date("Y")]) ?>

    <?= $form->field($ClubResults, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($ClubResults->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $ClubResults->isNewRecord ? 'btn btn-success btn-cons' : 'btn btn-primary btn-cons']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
