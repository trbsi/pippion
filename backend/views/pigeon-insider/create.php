<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model backend\models\PigeonInsider */
/* @var $form ActiveForm */

$this->title=Yii::t('default', 'Pigeon Insider Maker');
?>
<?=  $this->render('_form', ['model'=>$model, 'tabularData'=>$tabularData]); ?>
<?php /*
<div class="pigeoninsider-create">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'place') ?>
        <?= $form->field($model, 'competition') ?>
        <?= $form->field($model, 'release_place') ?>
        <?= $form->field($model, 'distance') ?>
        <?= $form->field($model, 'total_pigeons') ?>
        <?= $form->field($model, 'pigeon_name') ?>
        <?= $form->field($model, 'pigeon_number') ?>
        <?= $form->field($model, 'breeder_name') ?>
        <?= $form->field($model, 'address') ?>
        <?= $form->field($model, 'tel') ?>
        <?= $form->field($model, 'mob') ?>
        <?= $form->field($model, 'email') ?>
        <?= $form->field($model, 'web') ?>
    
        <div class="form-group">
            <?= Html::submitButton(Yii::t('default', 'Submit'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- pigeoninsider-create -->
*/
?>
