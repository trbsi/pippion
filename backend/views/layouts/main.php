<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use backend\models\BreederImage;
use backend\modules\messages\models\Messages;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
<meta charset="<?= Yii::$app->charset ?>"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<?php $this->head() ?>
<?= Html::csrfMetaTags() ?>
<?php require "head.php"; ?>
<title>
<?= Html::encode($this->title) ?>
</title>
</head>
<!-- BEGIN BODY -->
<body>
<?php 
//take user's profile picture
$profile_picture=BreederImage::findUserProfilePicture(Yii::$app->user->getId()); 
//check for messages
Messages::checkForNewMessages();
?>
<?php $this->beginBody() ?>
<!-- BEGIN HEADER -->
<?php require "top-header.php"; ?>
<!-- END HEADER --> 

<!-- BEGIN CONTAINER -->
<div class="page-container row-fluid"> 
  
  <!-- BEGIN SIDEBAR -->
  <?php require "left-sidebar.php"; ?>
  <a href="#" class="scrollup">Scroll</a>
  <?php require "footer-small.php"; ?>
  <!-- END SIDEBAR --> 
  
  <!-- BEGIN PAGE CONTAINER-->
  <div class="page-content"> 
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <div id="portlet-config" class="modal hide">
      <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button"></button>
        <h3>Widget Settings</h3>
      </div>
      <div class="modal-body"> Widget settings form goes here </div>
    </div>
    <div class="clearfix"></div>
    <div class="content">
      <?php /*echo Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ])*/ ?>
      <br>
      <?= $content ?>
      
      <div class="m-t-20"><?= Yii::powered() ?></div>
      <!-- RESPONSIVE ADSENSE -->
      <div id="adsense"> 
        <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> 
        <!-- Pippion Ads Responsive --> 
        <ins class="adsbygoogle"
                 style="display:block"
                 data-ad-client="ca-pub-8100130703025232"
                 data-ad-slot="6570145434"
                 data-ad-format="auto"></ins> 
        <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script> 
      </div>
      <!--RESPONSIVE ADSENSE --> 
      
    </div>
  </div>
  <!-- BEGIN CHAT -->
  <?php //require "chat.php"; ?>
  <!-- END CHAT --> 
</div>
<!-- END CONTAINER --> 

<!-- END CONTAINER --> 

<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-50922557-1', 'pippion.com');
ga('require', 'displayfeatures');
ga('send', 'pageview');

</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
