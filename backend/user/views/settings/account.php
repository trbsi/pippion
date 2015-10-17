<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $this  yii\web\View
 * @var $form  yii\widgets\ActiveForm
 * @var $model dektrium\user\models\SettingsForm
 */

$this->title = Yii::t('user', 'Account settings');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('@backend/views/_alert') ?>


 <?php //echo $this->render('@backend/views/_menu') ?>

<div class="row">
    <div class="col-md-12">
      <div class="grid simple">
        <div class="grid-title no-border">
          <h4><?= Html::encode($this->title) ?></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body no-border"> <br>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
            
				<?php $form = ActiveForm::begin([
                    'id'          => 'account-form',
                    'options'     => ['class' => 'form-none'],
                    'fieldConfig' => [
                        'template'     => "{label}\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-lg-12 m-b-20\">{error}\n{hint}</div>",
                        'labelOptions' => ['class' => 'col-lg-3 control-label'],
                    ],
                    'enableAjaxValidation'   => true,
                    'enableClientValidation' => false
                ]); ?>

                <?php //= $form->field($model, 'username') ?>
              <div class="form-group">
                <div class="controls">
                   <?= $form->field($model, 'email') ?>
                </div>
              </div>
              
              <div class="form-group">
                <div class="controls">
					 <?= $form->field($model, 'new_password')->passwordInput() ?>
                </div>
              </div>
              
              <div class="form-group">
                <div class="controls">
					 <?= $form->field($model, 'current_password')->passwordInput() ?>
                </div>
              </div>
              
              <div class="form-group">
                <div class="controls">
					<?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-block btn-success']) ?><br>
                </div>
              </div>
			  <?php ActiveForm::end(); ?>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
          
