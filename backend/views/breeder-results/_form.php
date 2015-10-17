<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BreederResults */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="breeder-results-form">

    <?php $form = ActiveForm::begin(['options'=>['onSubmit'=>'setCurrentTime()']]); ?>


    <?= $form->field($model, 'breeder_result')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'year')->textInput(['maxlength' => 4])->input('number', ['max'=>(date('Y')+5)]) ?>
    
	<?php // https://github.com/yiisoft/yii2/issues/641 ?>
    <?= $form->field($model, 'date_created', ['template' => "{input}"])->hiddenInput(['class'=>'js-setTime']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-cons btn-success' : 'btn btn-cons btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
