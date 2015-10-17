<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\modules\club\models\ClubAdmin;

/* @var $this yii\web\View */
/* @var $model backend\modules\pigeon_image\models\PigeonImageAlbum */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="pigeon-image-album-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'album')->textInput(['maxlength' => 100]) ?>
    
    <div class="alert alert-warning"><?= Yii::t('default', 'Add club album') ?></div>
    <?= $form->field($model, 'IDclub')->dropDownList(ClubAdmin::allClubsWhereUserIsAdmin(isset($_GET["club"])?$_GET["club"]:NULL), []) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-cons' : 'btn btn-primary btn-cons']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
