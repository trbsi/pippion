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

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\Profile $profile
 */

$this->title = Yii::t('user', 'Profile settings');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('@backend/views/_alert') ?>
<?php //eco $this->render('@backend/views/_menu') ?>

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
            
                <?php $form = \yii\widgets\ActiveForm::begin([
                    'id' => 'profile-form',
                    'options' => ['class' => 'form-none'],
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-lg-12  m-b-20\">{error}\n{hint}</div>",
                        'labelOptions' => ['class' => 'col-lg-3 control-label'],
                    ],
                    'enableAjaxValidation'   => true,
                    'enableClientValidation' => false
                ]); ?>
                <div class="form-group">
                    <div class="controls">
						<?= $form->field($model, 'name') ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="controls">
						<?= $form->field($model, 'public_email') ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="controls">
						<?= $form->field($model, 'website') ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="controls">
						<?= $form->field($model, 'location') ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="controls">
						<?= $form->field($model, 'gravatar_email')->hint(\yii\helpers\Html::a(Yii::t('user', 'Change your avatar at Gravatar.com'), 'http://gravatar.com')) ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="controls">
						<?= $form->field($model, 'bio')->textarea() ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="controls">
                        <?= \yii\helpers\Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-success btn-block']) ?><br>
                    </div>
                </div>

                <?php \yii\widgets\ActiveForm::end(); ?>
                
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
          
