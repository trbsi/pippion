<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\CountryList;

/* @var $this yii\web\View */
/* @var $model backend\models\RentABreeder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rent-abreeder-form">

    <?php $form = ActiveForm::begin(['options'=>["enctype"=>"multipart/form-data"]]); ?>

    <?= $form->field($model, 'IDcountry')->dropDownList(CountryList::dropdownCountryList()) ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => 30]) ?>

    <?= $form->field($model, 'breeder_picture')->fileInput( $options = [] ) ?>
		
    <?= $form->field($model, 'extra_info')->textarea(['rows' => 6]) ?>

	<?php if($model->isNewRecord==false && in_array(Yii::$app->user->getId(), Yii::$app->params['adminId'])):?>
    <?= $form->field($model, 'active')->dropDownList([0=>'No', 1=>'Yes']) ?>
	<?php endif; ?>
    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-cons' : 'btn btn-primary btn-cons']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
