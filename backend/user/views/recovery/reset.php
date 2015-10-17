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
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\RecoveryForm $model
 */

$this->title = Yii::t('user', 'Reset your password');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
  <div class="row login-container animated fadeInUp">
    <div class="col-md-7 col-md-offset-2 tiles white no-padding"  style="background:rgba(255,255,255,0.5); color:black;">
      <div class="p-t-30 p-l-40 p-b-20 xs-p-t-10 xs-p-l-10 xs-p-b-10">
        <h2 class="normal">
          <?= Html::encode($this->title) ?>
        </h2>
        <a href="<?=  Url::to(['/user/security/login']); ?>"  class="btn btn-info btn-cons">
        <?=  Yii::t('default', 'Sign in to Pippion') ?>
        </a> <a href="<?=  Url::to(['/user/registration/register']); ?>"  class="btn btn-info btn-cons">
        <?=  Yii::t('default', 'Create an account') ?>
        </a>
        <?= Html::a(Yii::t('default', 'What is this all about?'), ['/site/what-is-pippion'], ['class'=>'btn btn-danger btn-cons']) ?>
        <br />
      </div>
      <div class="tiles grey p-t-20 p-b-20 text-black">
        <?php $form = ActiveForm::begin([
                'id' => 'password-recovery-form',
            ]); ?>
        <div class="row form-row m-l-20 m-r-20 xs-m-l-10 xs-m-r-10">
          <div class="col-md-12 col-sm-12 ">
            <?= $form->field($model, 'password')->passwordInput() ?>
          </div>
          <div class="col-md-12 col-sm-12">
            <?= Html::submitButton(Yii::t('user', 'Finish'), ['class' => 'btn btn-success btn-block']) ?>
            <br>
          </div>
        </div>
        <?php ActiveForm::end(); ?>
        <br />
      </div>
    </div>
  </div>
</div>
