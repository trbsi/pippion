<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\CountryList;

/* @var $this yii\web\View */
/* @var $model backend\models\Breeder */
/* @var $form yii\widgets\ActiveForm */
$CountryList = new CountryList;
?>

<div class="breeder-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'name_of_breeder')->textInput(['maxlength' => 40]) ?>

    <?= $form->field($model, 'country')->textInput()->dropDownList($CountryList->dropdownCountryList()) ?>

    <?= $form->field($model, 'town')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => 100]) ?>

    <?= $form->field($model, 'tel1')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'tel2')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'mob1')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'mob2')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'email1')->textInput(['maxlength' => 50, 'value'=>($model->email1=="-") ? $model->relationIDuser->email : $model->email1 ]) ?>

    <?= $form->field($model, 'email2')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'fax')->textInput(['maxlength' => 20]) ?>

    <?= $form->field($model, 'website', ['inputOptions'=>['placeholder'=>'http://www.yoursite.com', 'class'=>'form-control']])->textInput(['maxlength' => 50])->input('url') ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
