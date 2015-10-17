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
//IZMJENA START
use yii\helpers\Url;
use yii\captcha\Captcha;
use dektrium\user\widgets\Connect;
//IZMJENA END

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\User $user
 */

$this->title = Yii::t('user', 'Sign up');
$this->params['breadcrumbs'][] = $this->title;
?>
<script>
$(document).ready(function(e) {
    $(".register_btn").click(function()
	{
		alert("<?= Yii::t('default', 'Registration submit button info') ?>");
	});
});
</script>
<div class="container">
  <div class="row login-container animated fadeInUp" style="margin-top:0% <?php //DODAO ?>">
    <div class="col-md-7 col-md-offset-2 tiles white no-padding"  style="background:rgba(255,255,255,0.5); color:black;">
      <div class="p-t-30 p-l-40 p-b-20 xs-p-t-10 xs-p-l-10 xs-p-b-10">
        <h2 class="normal"><?php echo Yii::t('default', 'Sign in to Pippion'); ?></h2>
        <p class="p-b-20"><?php echo Yii::t('default', 'Sign up Now! for Pippion accounts, its free and always will be..'); ?></p>
        <a href="<?=  Url::to(['/user/security/login']); ?>"  class="btn btn-info btn-cons"><?=  Yii::t('default', 'Sign in to Pippion') ?></a>
        
        <?= Html::a(Yii::t('default', 'What is this all about?'), ['/site/what-is-pippion'], ['class'=>'btn btn-danger btn-cons']) ?>
      </div>
      <div class="tiles grey p-t-20 p-b-20 text-black">
         <?php $form = ActiveForm::begin([
                    'id' => 'registration-form',
                ]); ?>

          <div class="row form-row m-l-20 m-r-20 xs-m-l-10 xs-m-r-10">
            <div class="col-md-6 col-sm-6 ">
              <?= $form->field($model, 'username') ?>
            </div>
            <div class="col-md-6 col-sm-6">
              <?= $form->field($model, 'email')->input("email", $options = [] ) ?>

            </div>
          </div>
          <div class="row p-t-10 m-l-20 m-r-20 xs-m-l-10 xs-m-r-10">
            <div class="control-group  col-md-10">
              <div class="checkbox checkbox check-success"> <!--<a href="#">Trouble login in?</a>&nbsp;&nbsp;-->
                <?php if (Yii::$app->getModule('user')->enableGeneratingPassword == false): ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                <?php endif ?>

              </div>
            </div>
          </div>          
		<?php /*
        <div class="row p-t-10 m-l-20 m-r-20 xs-m-l-10 xs-m-r-10">
            <div class="control-group  col-md-10">
              <div class="checkbox checkbox check-success"> <!--<a href="#">Trouble login in?</a>&nbsp;&nbsp;-->
				 <?php echo $form->field($model, 'captcha')->widget(Captcha::className(), array('captchaAction'=>'/site/captcha')) ?>
              </div>
            </div>
          </div>    
		  */ ?>      

        <div class="row p-t-10 m-l-20 m-r-20 xs-m-l-10 xs-m-r-10">
            <div class="control-group  col-md-10">
              <div class="checkbox checkbox check-success"> <!--<a href="#">Trouble login in?</a>&nbsp;&nbsp;-->
                <?= Html::submitButton(Yii::t('user', 'Sign up'), ['class' => 'btn btn-success btn-block register_btn']) ?>
              </div>
            </div>
          </div>
          
          <br />
		<?= Connect::widget([
			'baseAuthUrl' => ['/user/security/auth']
        ]) ?>

          
         <?php ActiveForm::end(); ?>
        <br />
		<? //Html::a(Yii::t('user', 'Already registered? Sign in!'), ['/user/security/login']) ?>
      </div>
    </div>
  </div>
</div>


