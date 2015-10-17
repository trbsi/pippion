<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\club\models\ClubMembers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="club-members-form">
	<div class="alert alert-info"><?= Yii::t('default', 'How many members do you want to add?') ?></div>
    <?= Html::beginForm(Url::to(["add-member", 'club_page'=>$model->club_link]), 'GET', []); ?>
    <?= Html::input('number', 'amount', 1, ['max'=>100, 'min'=>0] ); ?>
    <?= Html::submitButton(Yii::t('default', 'Submit'),  ['class'=>'btn btn-primary'] ); ?>
    <?= Html::endForm(); ?>
    
    <?php $form = ActiveForm::begin(); ?>
    <br>
	<div class="table-responsive">
	<table class="table table-bordered">
    <?php if(isset($_GET["amount"])) 
			$end=(int)$_GET["amount"]; 
		else
			$end=1;
	?>
    <?php for($i=0;$i<$end; $i++): ?>
      <tr>
        <td><?= $form->field($addMemberModel, "[$i]name")->textInput(['maxlength' => 100]) ?></td>
        <td><?= $form->field($addMemberModel, "[$i]address")->textInput(['maxlength' => 100]) ?></td>
        <td><?= $form->field($addMemberModel, "[$i]tel")->textInput(['maxlength' => 20]) ?></td>
        <td><?= $form->field($addMemberModel, "[$i]mob")->textInput(['maxlength' => 20]) ?></td>
        <td><?= $form->field($addMemberModel, "[$i]email")->textInput(['maxlength' => 30]) ?></td>
      </tr>
      <?php endfor; ?>
    </table>
    </div>
	
    <br>
    <div class="form-group">
        <?= Html::submitButton($addMemberModel->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $addMemberModel->isNewRecord ? 'btn btn-success btn-cons' : 'btn btn-primary btn-cons']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>