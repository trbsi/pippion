<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\modules\club\models\ClubTables;

/* @var $this yii\web\View */
/* @var $model backend\modules\club\models\ClubTables */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="club-tables-form">

    <?php $form = ActiveForm::begin(['options'=>["enctype"=>"multipart/form-data"]]); ?>

	<?php if($ClubTables->isNewRecord==true): ?>
	<?= $form->field($ClubTables, 'pdf_file')->fileInput() ?>
	<?= $form->field($ClubTables, 'result_type')->radioList([ClubTables::RESULT_TYPE_TEAM=>Yii::t('default', 'Tables'), ClubTables::RESULT_TYPE_PIGEON=>Yii::t('default', 'Pigeon tables')], $options = [] ) ?>
    <?php endif; ?>

    <?= $form->field($ClubTables, 'year')->input('number', ['min' => 1900, 'value'=>date("Y")]) ?>

    <?= $form->field($ClubTables, 'description')->textarea(['rows' => 6]) ?>


    <div class="form-group">
        <?= Html::submitButton($ClubTables->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $ClubTables->isNewRecord ? 'btn btn-success btn-cons' : 'btn btn-primary btn-cons']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
