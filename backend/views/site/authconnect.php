<?PHP
use yii\helpers\Url;
use yii\helpers\Html;
$this->title=Yii::t('default', 'Connect then login title');
?>

<div class="container">
  <div class="row login-container animated fadeInUp">
    <div class="col-md-7 col-md-offset-2 tiles white no-padding"  style="background:rgba(255,255,255,0.5); color:black;">
      <div class="p-t-30 p-l-40 p-b-20 xs-p-t-10 xs-p-l-10 xs-p-b-10">
        <h2 class="normal"><?php echo Yii::t('default', 'Sign in to Pippion'); ?></h2>
        <div class="alert alert-warning" style="margin-bottom:10px; padding:5px; font-size:11px; max-width:95%"><?php echo Yii::t('default', 'Sign up Now! for Pippion accounts, its free and always will be..'); ?><br />
          <?=  Yii::t('default', 'Sign up with Facebook') ?>
        </div>
        <a href="<?=  Url::to(['/user/registration/register']); ?>"  class="btn btn-info btn-cons">
        <?=  Yii::t('default', 'Create an account') ?>
        </a>
        <?= Html::a(Yii::t('default', 'What is this all about?'), ['/site/what-is-pippion'], ['class'=>'btn btn-danger btn-cons']) ?>
        <br />
      </div>
      <div class="tiles grey p-t-20 p-b-20 text-black" style="max-height:200px; overflow-y:scroll;">
        <div class="row form-row m-l-20 m-r-20 xs-m-l-10 xs-m-r-10">
          <div class="col-md-12 col-sm-12 alert alert-danger"> <?= Yii::t('default', 'Connect then login'); ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
