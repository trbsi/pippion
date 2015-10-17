<?php
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use backend\helpers\ExtraFunctions;
use backend\models\Breeder;
?>

<div class="page-sidebar" id="main-menu"> 
  <!-- BEGIN MINI-PROFILE -->
  <div class="page-sidebar-wrapper scrollbar-dynamic" id="main-menu-wrapper">
    <div class="user-info-wrapper">
      <div class="profile-wrapper"> 
      <?php
	  	if(Yii::$app->user->isGuest)
		{
			$username="Guest";
			$name_of_breeder="Guest";
		}
		else
		{
			$username=ExtraFunctions::getCookie(\Yii::$app->params['username_cookie']); 
			$name_of_breeder=ExtraFunctions::getCookie(\Yii::$app->params['name_of_breeder_cookie']);
		}
		?>
      <a href="<?= $profile_picture?>" class="profile_pic_colorbox"><img src="<?= $profile_picture ?>"  alt="" data-src="<?= $profile_picture ?>" data-src-retina="<?= $profile_picture ?>" width="69" height="69" /></a> </div>
      <div class="user-info">
        <div class="greeting"><?php echo $username; ?></div>
        <!--<div class="status">Status<a href="#">
          <div class="status-icon green"></div>
          Online</a></div>-->
        <div style="width:110px;"><?= $name_of_breeder; ?></div><!-- IZMJENA width:110px da se napravi word-break -->
      </div>
      
    </div>
    <div style="clear:both"></div>
    <!-- END MINI-PROFILE --> 
    
    <!-- BEGIN SIDEBAR MENU -->
    <?php
	/*
	    NavBar::begin([
			'brandLabel' => 'My Company',
			'brandUrl' => Yii::$app->homeUrl,
			'options' => [
				'class' => 'navbar-inverse navbar-fixed-top',
			],
		]);
		$menuItems = [
			['label' => 'Home', 'url' => ['/site/index']],
		];
		if (Yii::$app->user->isGuest) 
		{
			$menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
		} 
		else 
		{
			$menuItems[] = [
				'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
				'url' => ['/site/logout'],
				'linkOptions' => ['data-method' => 'post']
			];
		}
		echo Nav::widget([
			'options' => ['class' => 'navbar-nav navbar-right'],
			'items' => $menuItems,
		]);
		NavBar::end();*/
		?>
    <!--<p class="menu-title">BROWSE <span class="pull-right"><a href="javascript:;"><i class="fa fa-refresh"></i></a></span></p>-->
    <br /><br />
    <?php
	require "main-menu.php";
	?>
	<br />
    <!-- BEGIN SIDEBAR WIDGETS -->
    <div class="side-bar-widgets"> 
      <!--BEGIN Facebook and Twitter buttoins --> 
      <div>
        <div class="status-widget">
          <div class="status-widget-wrapper">
            <p>
              <?= ExtraFunctions::twitterFacebookButton() ?>
              <br /><br />
            </p>
          </div>
        </div>
      </div>
       <!--END Facebook and Twitter buttoins --> 

      <!--BEGIN IZABERI JEZIK WIDGET -->
      <div class="visible-xs visible-sm">
        <p class="menu-title"><?php echo Yii::t('default', 'Izaberi_Jezik'); ?></p>
        <div class="status-widget">
          <div class="status-widget-wrapper">
            <p>
              <?php $ef = new ExtraFunctions; $ef->prikaziJezike(); ?>
              <br /><br />
            </p>
          </div>
        </div>
      </div>
      <!--END IZABERI JEZIK WIDGET --> 
      
    </div>
    <!-- END SIDEBAR WIDGETS -->
    <div class="clearfix"></div>
    <!-- END SIDEBAR MENU --> 
  </div>
</div>
