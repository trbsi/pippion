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
use dektrium\user\widgets\Connect;
use yii\helpers\Url;


/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\LoginForm $model
 */

$this->title = Yii::t('user', 'Sign in');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container">
  <div class="row login-container animated fadeInUp" style="margin-top:5% <?php //DODAO ?>">
    <div class="col-md-7 col-md-offset-2 tiles white no-padding"  style="background:rgba(255,255,255,0.5); color:black;">
      <div class="p-t-30 p-l-40 p-b-20 xs-p-t-10 xs-p-l-10 xs-p-b-10">
        <h2 class="normal"><?php echo Yii::t('default', 'Sign in to Pippion'); ?></h2>
        <div class="alert alert-warning" style="margin-bottom:10px; padding:5px; font-size:11px; max-width:95%"><?php echo Yii::t('default', 'Sign up Now! for Pippion accounts, its free and always will be..'); ?><br /><?=  Yii::t('default', 'Sign up with Facebook') ?></div>
        <a href="<?=  Url::to(['/user/registration/register']); ?>"  class="btn btn-info btn-cons"><?=  Yii::t('default', 'Create an account') ?></a>
        
        <?= Html::a(Yii::t('default', 'What is this all about?'), ['/site/what-is-pippion'], ['class'=>'btn btn-danger btn-cons']) ?>
        <br />
        
      </div>
      <div class="tiles grey p-t-20 p-b-20 text-black" ">
        <?php $form = ActiveForm::begin([
			'id' => 'login-form',
			'enableAjaxValidation'   => true,
			'enableClientValidation' => false,
			'validateOnBlur'         => false,
			'validateOnType'         => false,
			'validateOnChange'       => false,
		]) ?>

          <div class="row form-row m-l-20 m-r-20 xs-m-l-10 xs-m-r-10">
            <div class="col-md-6 col-sm-6 ">
              <?= $form->field($model, 'login', ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control', 'tabindex' => '1']]) ?>
            </div>
            <div class="col-md-6 col-sm-6">
              <?= $form->field($model, 'password', ['inputOptions' => ['class' => 'form-control', 'tabindex' => '2']])->passwordInput()->label(Yii::t('user', 'Password') . ' (' . Html::a(Yii::t('user', 'Forgot password?'), ['/user/recovery/request'], ['tabindex' => '5']) . ')') ?>

            </div>
          </div>
         
         <?php /* !!!!!!!!!!!!!!REMEMBER ME!!!!!!!!!!! 
          <div class="row p-t-10 m-l-20 m-r-20 xs-m-l-10 xs-m-r-10">
            <div class="control-group  col-md-10">
              <div class="">
                <?= $form->field($model, 'rememberMe')->checkbox(['tabindex' => '4']) ?>
              </div>
            </div>
          </div>
          */?>
 
        <div class="row p-t-10 m-l-20 m-r-20 xs-m-l-10 xs-m-r-10">
            <div class="control-group  col-md-12">
              <div class="checkbox checkbox check-success"> 
               <?= Html::submitButton(Yii::t('user', 'Sign in'), ['class' => 'btn btn-primary btn-block', 'tabindex' => '3']); ?>
              </div>
            </div>
          </div>          

        <?php ActiveForm::end(); ?>
        <br />
        <?= Connect::widget([
			'baseAuthUrl' => ['/user/security/auth']
		]) ?>

        <?php if ($module->enableConfirmation): ?>
        <p class="text-center">
          <?= Html::a(Yii::t('user', 'Didn\'t receive confirmation message?'), ['/user/registration/resend']) ?>
        </p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>



