<?php
use yii\helpers\Url;
use yii\helpers\Html;
use backend\helpers\ExtraFunctions;
use backend\helpers\LinkGenerator;
$image_alt="Pippion - Pippion - Pigeon racing and breeding management online software and auctions for pigeon breeders, racers and fanciers. Everything you need from hatching, pairing to racing and selling. Pipion";
?>
<!DOCTYPE html>
<html>
<head>
<meta property="og:image" content="https://<?php echo $_SERVER['HTTP_HOST']; ?>/images/og_image.jpg"/>
<meta property="og:url" content="https://<?php echo $_SERVER['HTTP_HOST']; ?>/site/what-is-pippion"/>
<meta property="og:title" content="Pippion | World's leading pigeon breeding/racing application and auctions"/>
<meta property="og:description" content="Pippion - Pigeon racing and breeding management online software and auctions for pigeon breeders, racers and fanciers. Everything you need from hatching, pairing to racing and selling"/>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<title><?php echo Yii::t('default', 'landing Title') ?></title>
<link rel="stylesheet" href="<?php echo Yii::getAlias('@web'); ?>/landing/css/colorbox.css" />
<link href="<?php echo Yii::getAlias('@web'); ?>/landing/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo Yii::getAlias('@web'); ?>/landing/css/animate.min.css" rel="stylesheet">
<link href="<?php echo Yii::getAlias('@web'); ?>/landing/css/font-awesome.min.css" rel="stylesheet">
<link href="<?php echo Yii::getAlias('@web'); ?>/landing/css/lightbox.css" rel="stylesheet">
<link href="<?php echo Yii::getAlias('@web'); ?>/landing/css/main.css" rel="stylesheet">
<link id="css-preset" href="<?php echo Yii::getAlias('@web'); ?>/landing/css/presets/preset1.css" rel="stylesheet">
<link href="<?php echo Yii::getAlias('@web'); ?>/landing/css/responsive.css" rel="stylesheet">

<!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
  <![endif]-->

<link href='//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700' rel='stylesheet' type='text/css'>
<link rel="shortcut icon" href="<?php echo Yii::getAlias('@web'); ?>/images/favicon.ico">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo Yii::getAlias('@web'); ?>/landing/images/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo Yii::getAlias('@web'); ?>/landing/images/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo Yii::getAlias('@web'); ?>/landing/images/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="<?php echo Yii::getAlias('@web'); ?>/landing/images/ico/apple-touch-icon-57-precomposed.png">
<script type="text/javascript" src="<?php echo Yii::getAlias('@web'); ?>/landing/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo Yii::getAlias('@web'); ?>/landing/js/bootstrap.min.js"></script>
<script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="<?php echo Yii::getAlias('@web'); ?>/landing/js/jquery.inview.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::getAlias('@web'); ?>/landing/js/wow.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::getAlias('@web'); ?>/landing/js/mousescroll.js"></script>
<script type="text/javascript" src="<?php echo Yii::getAlias('@web'); ?>/landing/js/smoothscroll.js"></script>
<script type="text/javascript" src="<?php echo Yii::getAlias('@web'); ?>/landing/js/jquery.countTo.js"></script>
<script type="text/javascript" src="<?php echo Yii::getAlias('@web'); ?>/landing/js/lightbox.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::getAlias('@web'); ?>/landing/js/main.js"></script>
<script src="<?php echo Yii::getAlias('@web'); ?>/landing/js/jquery.colorbox-min.js"></script>
<script>
/*MY MODIFICATIONS*/

$(document).ready(function(e) {
    //Examples of how to assign the Colorbox event to elements
	$(".group1_colorbox").colorbox({rel:'group1_colorbox', width:"100%", height:"100%"})
	$(".group2_colorbox").colorbox({rel:'group2_colorbox', width:"100%", height:"100%"})
});
/*
* If in mobile mode and user clicks on link hide menu by clicking on .navbar-toggle
* Because menu will remain opened when users clicks on link
*/
function hideMenuOnMobile()
{
	if($(window).width()<992)
	{
		$(".navbar-toggle").click();
	}
}

/*
* In "services" section when guest clicks on link to show screenshots of Pippion, colorbox will be activated
*/
function slike()
{
	$(".group1_colorbox").first().click();
}

</script>
<style>
.services_description
{
	height:100px;
}
.well
{
	background-color: #d1dade;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	-webkit-box-shadow: none !important;
	-moz-box-shadow: none !important;
	box-shadow: none !important;
	border: none;
	background-image: none;
	padding:30px;
}
.well a
{
	color:#027DB3;
}
.embed-container 
{ 
	position: relative; 
	padding-bottom: 56.25%; 
	height: 0; 
	overflow:hidden; 
	max-width: 100%; 
} 
.embed-container iframe, .embed-container object, .embed-container embed 
{ 
	position: absolute;
	top: 0; 
	left: 0; 
	width: 100%;
	height: 100%; 
}
</style>
</head>
<!--/head-->

<body>


<!--.preloader-->
<div class="preloader"> <i class="fa fa-circle-o-notch fa-spin"></i></div>
<!--/.preloader-->

<header id="home">
  <div id="home-slider" class="carousel slide carousel-fade" data-ride="carousel">
    <div class="carousel-inner">
      <div class="item active" style="background-image: url(<?php echo Yii::getAlias('@web'); ?>/images/wallpapers/12.jpg)">
        <div class="caption">
          <h1 class="animated fadeInLeftBig"><?php echo Yii::t('default', 'landing_home Welcome to Pippion') ?></h1>
          <p class="animated fadeInRightBig"><?php echo Yii::t('default', 'landing_home description 1') ?></p>
          <a data-scroll class="btn btn-start animated fadeInUpBig" href="#services"><?php echo Yii::t('default', 'landing_home Start')?></a> </div>
      </div>
      <div class="item" style="background-image: url(<?php echo Yii::getAlias('@web'); ?>/images/wallpapers/2.jpg)">
        <div class="caption">
          <h1 class="animated fadeInLeftBig"><?php echo Yii::t('default', 'landing_home Sell pigeons') ?></h1>
          <p class="animated fadeInRightBig"><?php echo Yii::t('default', 'landing_home description 2') ?></p>
          <a data-scroll class="btn btn-start animated fadeInUpBig" href="#services"><?php echo Yii::t('default', 'landing_home Start')?></a> </div>
      </div>
      <div class="item" style="background-image: url(<?php echo Yii::getAlias('@web'); ?>/images/wallpapers/3.jpg)">
        <div class="caption">
          <h1 class="animated fadeInLeftBig"><?php echo Yii::t('default', 'landing_home Racing tables') ?></h1>
          <p class="animated fadeInRightBig"><?php echo Yii::t('default', 'landing_home description 3') ?></p>
          <a data-scroll class="btn btn-start animated fadeInUpBig" href="#services"><?php echo Yii::t('default', 'landing_home Start')?></a> </div>
      </div>
      <div class="item" style="background-image: url(<?php echo Yii::getAlias('@web'); ?>/images/wallpapers/4.jpg)">
        <div class="caption">
          <h1 class="animated fadeInLeftBig"><?php echo Yii::t('default', 'landing_home Lost and Found') ?></h1>
          <p class="animated fadeInRightBig"><?php echo Yii::t('default', 'landing_home description 4') ?></p>
          <a data-scroll class="btn btn-start animated fadeInUpBig" href="#services"><?php echo Yii::t('default', 'landing_home Start')?></a> </div>
      </div>
      <div class="item" style="background-image: url(<?php echo Yii::getAlias('@web'); ?>/images/wallpapers/5.jpg)">
        <div class="caption">
          <h1 class="animated fadeInLeftBig"><?php echo Yii::t('default', 'landing_home Connect') ?></h1>
          <p class="animated fadeInRightBig"><?php echo Yii::t('default', 'landing_home description 5') ?></p>
          <a data-scroll class="btn btn-start animated fadeInUpBig" href="#services"><?php echo Yii::t('default', 'landing_home Start')?></a> </div>
      </div>
      <div class="item" style="background-image: url(<?php echo Yii::getAlias('@web'); ?>/images/wallpapers/7.jpg)">
        <div class="caption">
          <h1 class="animated fadeInLeftBig"><?php echo Yii::t('default', 'landing_home Just beginning') ?></h1>
          <p class="animated fadeInRightBig"><?php echo Yii::t('default', 'landing_home description 7') ?></p>
          <a data-scroll class="btn btn-start animated fadeInUpBig" href="#services"><?php echo Yii::t('default', 'landing_home Start')?></a> </div>
      </div>
    </div>
    <a class="left-control" href="#home-slider" data-slide="prev"><i class="fa fa-angle-left"></i></a> <a class="right-control" href="#home-slider" data-slide="next"><i class="fa fa-angle-right"></i></a> <a id="tohash" href="#services"><i class="fa fa-angle-down"></i></a> </div>
  <!--/#home-slider-->
  <div class="navbar navbar-default" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <a class="navbar-brand" href="/index.php">
        <h1><img class="img-responsive" src="<?php echo Yii::getAlias('@web'); ?>/images/logo.png" style="max-width:180px;" alt="<?= $image_alt?>"></h1>
        </a> </div>
      <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav navbar-right">
          <li class="scroll active"><a href="#home" onClick="hideMenuOnMobile()"><?php echo Yii::t('default', 'landing_link Home') ?></a></li>
          <li class="scroll"><a href="#services" onClick="hideMenuOnMobile()"><?php echo Yii::t('default', 'landing_link Services') ?></a></li>
          <li class="scroll"><a href="#features2" onClick="hideMenuOnMobile()"><?php echo Yii::t('default', 'landing_link About Us') ?></a></li>
          <!--<li class="scroll"><a href="#portfolio">Portfolio</a></li>               -->
          <li class="scroll"><a href="#pricing" onClick="hideMenuOnMobile()"><?php echo Yii::t('default', 'landing_link Pricing') ?></a></li>
          <li class="scroll"><a href="#team" onClick="hideMenuOnMobile()"><?php echo Yii::t('default', 'landing_link Team') ?></a></li>
          <!--<li class="scroll"><a href="#twitter" onClick="hideMenuOnMobile()"><?php echo Yii::t('default', 'landing_link Share') ?></a></li>-->
          <!--<li class="scroll"><a href="#blog">Blog</a></li>-->
          <li class="scroll"><a href="#contact" onClick="hideMenuOnMobile()"><?php echo Yii::t('default', 'landing_link Contact') ?></a></li>
          <li class="scroll"><a href="/pigeonblog" onClick="hideMenuOnMobile()" target="_blank">Blog</a></li>
          <!--<li class=""><a href="<?php echo Url::to('/site/tutorials') ?>" target="_blank"><?php echo Yii::t('default', 'Tutorials') ?></a></li>-->
          <li class="register"><a href="<?php echo Url::to('/user/registration/register') ?>"><?php echo Yii::t('default', 'landing_link Register') ?></a></li>
          <li class="register"><a href="<?php echo Url::to('/user/security/login') ?>"><?php echo Yii::t('default', 'landing_link Login') ?></a></li>
           <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?= Yii::t('default', 'Language') ?> <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                  <li class=""><a href="<?php echo Url::to(['/site/what-is-pippion', 'lang'=>'en']) ?>">English</a></li>
                  <li class=""><a href="<?php echo Url::to(['/site/what-is-pippion', 'lang'=>'hr']) ?>">Hrvatski</a></li>
                  <li class=""><a href="<?php echo Url::to(['/site/what-is-pippion', 'lang'=>'hu']) ?>">Magyar</a></li>
                  <li class=""><a href="<?php echo Url::to(['/site/what-is-pippion', 'lang'=>'de']) ?>">Deutsch</a></li>
                  <li class=""><a href="<?php echo Url::to(['/site/what-is-pippion', 'lang'=>'nl']) ?>">Dutch</a></li>
                  <li class=""><a href="<?php echo Url::to(['/site/what-is-pippion', 'lang'=>'zh-cn']) ?>">中国</a></li>
                  <li class=""><a href="<?php echo Url::to(['/site/what-is-pippion', 'lang'=>'fr']) ?>">Français</a></li>
              </ul>
        </li>
        </ul>
      </div>
    </div>
  </div>
  <!--/.navbar--> 
</header>
<!--/#home-->

<section id="services">
  <div class="container">
    <div class="heading wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="300ms">
      <div class="row">        
        <div class="text-center col-sm-12 ">
             <h2><?= LinkGenerator::clubListOfClubs(Yii::t('default', 'Clubs'), ['target'=>'_blank',  "style"=>"text-decoration:underline"]) ?></h2><br>
             <p class="well">
            <?php 
            foreach($clubs as $key=>$value) 
            {
                echo LinkGenerator::clubIndividualClubpage('<i class="fa fa-home"></i>&nbsp;'.$value["club"], $value["club_link"], ['target'=>'_blank', 'class'=>'btn btn-block btn-default'])."<br>";
            }
            ?>
            </p>
        </div>
		<div style="clear:both"></div>
        <div class="text-center col-md-6 ">
            <div class='embed-container'><iframe src='https://www.youtube.com/embed/tX4a-aDiKJg' frameborder='0' allowfullscreen></iframe></div>
        </div>        
        <div class="text-center col-md-6 ">
            <div class='embed-container'><iframe src='https://www.youtube.com/embed/gMdA5xBnAdE' frameborder='0' allowfullscreen></iframe></div>
        </div>        
        
      </div>
      
      <div class="row">
        <div class="text-center col-sm-8 col-sm-offset-2">
          <br>
          <h2><?php echo Yii::t('default', 'landing_service What do we offer') ?></h2>
          <!--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua ut enim ad minim veniam</p>--> 
        </div>
      </div>
    </div>
    <div class="text-center our-services">
      <div class="row">
        <div class="col-sm-4 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
          <div class="service-icon"> <i class="fa fa-envelope"></i> </div>
          <div class="service-info">
            <h3><?php echo Yii::t('default', 'landing_service Messages title') ?></h3>
            <p class="services_description"><?php echo Yii::t('default', 'landing_service Messages desc') ?></p>
          </div>
        </div>
        <div class="col-sm-4 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="450ms">
          <div class="service-icon"> <i class="fa fa-user"></i> </div>
          <div class="service-info">
            <h3><?php echo Yii::t('default', 'landing_service Profile title') ?></h3>
            <p class="services_description"><?php echo Yii::t('default', 'landing_service Profile desc') ?></p>
          </div>
        </div>
        <div class="col-sm-4 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="550ms">
          <div class="service-icon"> <i class="fa fa-twitter"></i> </div>
          <div class="service-info">
            <h3><?php echo Yii::t('default', 'landing_service Pigeon title') ?></h3>
            <p class="services_description"><?php echo Yii::t('default', 'landing_service Pigeon desc') ?></p>
          </div>
        </div>
        <div class="col-sm-4 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="550ms">
          <div class="service-icon"> <i class="fa fa-calendar"></i> </div>
          <div class="service-info">
            <h3><?php echo Yii::t('default', 'landing_service Hatching title') ?></h3>
            <p class="services_description"><?php echo Yii::t('default', 'landing_service Hatching desc') ?></p>
          </div>
        </div>
        <div class="col-sm-4 wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="650ms">
          <div class="service-icon"> <i class="fa fa-trophy"></i> </div>
          <div class="service-info">
            <h3><?php echo Yii::t('default', 'landing_service Racing Tables title') ?></h3>
            <p class="services_description"><?php echo Yii::t('default', 'landing_service Racing Tables desc') ?></p>
          </div>
        </div>
        <div class="col-sm-4 wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="650ms">
          <div class="service-icon"> <i class="fa fa-legal"></i> </div>
          <div class="service-info">
            <h3><?php echo Yii::t('default', 'landing_service Auction title') ?></h3>
            <p class="services_description"><?php echo Yii::t('default', 'landing_service Auction desc') ?></p>
          </div>
        </div>
        
        <!--<div class="col-sm-4 wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="650ms">
          <div class="service-icon"> <i class="fa fa-map-marker"></i> </div>
          <div class="service-info">
            <h3><?php echo Yii::t('default', 'landing_service GPS title') ?></h3>
            <p><?php echo Yii::t('default', 'landing_service GPS desc') ?></p>
          </div>
        </div>-->
        
        <div class="col-sm-4 wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="750ms">
          <div class="service-icon"> <i class="fa fa-list-alt"></i> </div>
          <div class="service-info">
            <h3><?php echo Yii::t('default', 'Pedigree') ?></h3>
            <p class="services_description"><?php echo Yii::t('default', 'landing_pricing Subs desc 6') ?></p>
          </div>
        </div>
        
        <div class="col-sm-4 wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="750ms">
          <div class="service-icon"><a href="<?php echo Url::to('/pigeon-insider/create'); ?>" style="color:white" target="_blank"><i class="fa fa-list"></i> </a></div>
          <div class="service-info">
            <h3><?php echo Yii::t('default', 'landing_service Pigeon Insider title') ?></h3>
            <p class="services_description"><?php echo Yii::t('default', 'landing_service Pigeon Insider desc') ?></p>
          </div>
        </div>
        
        <div class="col-sm-4 wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="850ms">
          <div class="service-icon"> <a href="javascript:;" class="txt-shadow" onClick="slike()" style="color:white"><i class="fa fa-photo"></i> </a></div>
          <div class="service-info">
            <div style="display:none">
              <?php
				$files = scandir($_SERVER['DOCUMENT_ROOT'].'/landing/images/colorbox/screenshots');
				foreach($files as $key=>$value)
				{
					if($value=="." || $value=="..")
						continue;
					else
						echo '<a class="group1_colorbox" href="/landing/images/colorbox/screenshots/'.$value.'">Grouped Photo 1</a><br>';
				}
				?>
            </div>
            <h3><?php echo Yii::t('default', 'landing_service Screenshots title') ?></h3>
            <p class="services_description"><?php echo Yii::t('default', 'landing_service Screenshots desc') ?></p>
          </div>
        </div>
        <div class="col-sm-12 wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="750ms">
          <div class="service-info">
            <h3></h3>
            <p><a href="https://play.google.com/store/apps/details?id=com.pippion.www.pippion" target="_blank"><img src="<?= Yii::getAlias('@web')?>/images/available_on_android.png" class="img-responsive center-block" style="max-height:100px;" alt="<?= $image_alt?>"/></a></p>
          </div>
        </div>
        
        <div class="col-sm-12 wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="750ms">
          <div class="service-info">
            <h3></h3>
            <p>
             <?php
				$files = scandir($_SERVER['DOCUMENT_ROOT'].'/landing/images/colorbox/screenshots');
				foreach($files as $key=>$value)
				{
					if($value=="." || $value==".." || $value=="_notes" || $value=="index.html")
						continue;
					else
						echo '<div class="col-sm-6">
								<a class="group2_colorbox" href="/landing/images/colorbox/screenshots/'.$value.'">
									<img src="/landing/images/colorbox/screenshots/'.$value.'" class="img-responsive img-thumbnail" style="max-height:200px"  alt="'.$image_alt.'">
								</a>
								<br><br>
								</div>';
				}
				?>
            </p>
          </div>
        </div>
     
      </div>
    </div>
  </div>
</section>
             
<!--/#services-->

<section id="features2"></section>
<br>
<section id="features" class="parallax">
  <div class="container">
    <div class="row count">
      <div id="twitter-carousel" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
          <li data-target="#twitter-carousel" data-slide-to="0" class="active"></li>
          <li data-target="#twitter-carousel" data-slide-to="1"></li>
          <li data-target="#twitter-carousel" data-slide-to="2"></li>
          <li data-target="#twitter-carousel" data-slide-to="3"></li>
          <li data-target="#twitter-carousel" data-slide-to="4"></li>
        </ol>
        <!-- /.carousel-indicators -->
        <div class="carousel-inner">
          <div class="item active">
            <p>
              <?= Yii::t('default', 'landing_testimonials 3')?>
            </p>
          </div>
          <div class="item">
            <p>
              <?= Yii::t('default', 'landing_testimonials 1')?>
            </p>
          </div>
          <div class="item">
            <p>
              <?= Yii::t('default', 'landing_testimonials 2')?>
            </p>
          </div>
          <div class="item">
            <p>
              <?= Yii::t('default', 'landing_testimonials 4')?>
            </p>
          </div>
          <div class="item">
            <p>
              <?= Yii::t('default', 'landing_testimonials 5')?>
            </p>
          </div>
        </div>
      </div>
      
      <!-- <div class="col-sm-3 col-xs-6 wow fadeInLeft" data-wow-duration="1000ms" data-wow-delay="300ms"> <i class="fa fa-user"></i>
        <h3 class=""><?php echo Yii::t('default', 'landing_features 1000') ?></h3>
        <p><?php echo Yii::t('default', 'landing_features Happy Clients') ?></p>
      </div>
      <div class="col-sm-3 col-xs-6 wow fadeInLeft" data-wow-duration="1000ms" data-wow-delay="500ms"> <i class="fa fa-desktop"></i> <i class="fa fa-mobile"></i>
        <h3 class="">&nbsp;</h3>
        <p><?php echo Yii::t('default', 'landing_features Online and mobile application') ?></p>
      </div>
      <div class="col-sm-3 col-xs-6 wow fadeInLeft" data-wow-duration="1000ms" data-wow-delay="700ms"> <i class="fa fa-trophy"></i>
        <h3 class="timer">1</h3>
        <p><?php echo Yii::t('default', 'landing_features Winning Award') ?></p>
      </div>
      <div class="col-sm-3 col-xs-6 wow fadeInLeft" data-wow-duration="1000ms" data-wow-delay="700ms"> <i class="fa fa-star"></i>
        <h3 class="timer">5</h3>
        <p><?php echo Yii::t('default', 'landing_features Rated 5 stars') ?></p>
      </div> --> 
    </div>
  </div>
</section>
<!--/#features--> 

<!--
  <section id="portfolio">
    <div class="container">
      <div class="row">
        <div class="heading text-center col-sm-8 col-sm-offset-2 wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="300ms">
          <h2>Our Portfolio</h2>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua ut enim ad minim veniam</p>
        </div>
      </div> 
    </div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3">
          <div class="folio-item wow fadeInRightBig" data-wow-duration="1000ms" data-wow-delay="300ms">
            <div class="folio-image">
              <img class="img-responsive" src="<?php echo Yii::getAlias('@web'); ?>/landing/images/portfolio/1.jpg" alt="">
            </div>
            <div class="overlay">
              <div class="overlay-content">
                <div class="overlay-text">
                  <div class="folio-info">
                    <h3>Time Hours</h3>
                    <p>Design, Photography</p>
                  </div>
                  <div class="folio-overview">
                    <span class="folio-link"><a class="folio-read-more" href="#" data-single_url="portfolio-single.html" ><i class="fa fa-link"></i></a></span>
                    <span class="folio-expand"><a href="<?php echo Yii::getAlias('@web'); ?>/landing/images/portfolio/portfolio-details.jpg" data-lightbox="portfolio"><i class="fa fa-search-plus"></i></a></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="folio-item wow fadeInLeftBig" data-wow-duration="1000ms" data-wow-delay="400ms">
            <div class="folio-image">
              <img class="img-responsive" src="<?php echo Yii::getAlias('@web'); ?>/landing/images/portfolio/2.jpg" alt="">
            </div>
            <div class="overlay">
              <div class="overlay-content">
                <div class="overlay-text">
                  <div class="folio-info">
                    <h3>Time Hours</h3>
                    <p>Design, Photography</p>
                  </div>
                  <div class="folio-overview">
                    <span class="folio-link"><a class="folio-read-more" href="#" data-single_url="portfolio-single.html" ><i class="fa fa-link"></i></a></span>
                    <span class="folio-expand"><a href="<?php echo Yii::getAlias('@web'); ?>/landing/images/portfolio/portfolio-details.jpg" data-lightbox="portfolio"><i class="fa fa-search-plus"></i></a></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="folio-item wow fadeInRightBig" data-wow-duration="1000ms" data-wow-delay="500ms">
            <div class="folio-image">
              <img class="img-responsive" src="<?php echo Yii::getAlias('@web'); ?>/landing/images/portfolio/3.jpg" alt="">
            </div>
            <div class="overlay">
              <div class="overlay-content">
                <div class="overlay-text">
                  <div class="folio-info">
                    <h3>Time Hours</h3>
                    <p>Design, Photography</p>
                  </div>
                  <div class="folio-overview">
                    <span class="folio-link"><a class="folio-read-more" href="#" data-single_url="portfolio-single.html" ><i class="fa fa-link"></i></a></span>
                    <span class="folio-expand"><a href="<?php echo Yii::getAlias('@web'); ?>/landing/images/portfolio/portfolio-details.jpg" data-lightbox="portfolio"><i class="fa fa-search-plus"></i></a></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="folio-item wow fadeInLeftBig" data-wow-duration="1000ms" data-wow-delay="600ms">
            <div class="folio-image">
              <img class="img-responsive" src="<?php echo Yii::getAlias('@web'); ?>/landing/images/portfolio/4.jpg" alt="">
            </div>
            <div class="overlay">
              <div class="overlay-content">
                <div class="overlay-text">
                  <div class="folio-info">
                    <h3>Time Hours</h3>
                    <p>Design, Photography</p>
                  </div>
                  <div class="folio-overview">
                    <span class="folio-link"><a class="folio-read-more" href="#" data-single_url="portfolio-single.html" ><i class="fa fa-link"></i></a></span>
                    <span class="folio-expand"><a href="<?php echo Yii::getAlias('@web'); ?>/landing/images/portfolio/portfolio-details.jpg" data-lightbox="portfolio"><i class="fa fa-search-plus"></i></a></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="folio-item wow fadeInRightBig" data-wow-duration="1000ms" data-wow-delay="700ms">
            <div class="folio-image">
              <img class="img-responsive" src="<?php echo Yii::getAlias('@web'); ?>/landing/images/portfolio/5.jpg" alt="">
            </div>
            <div class="overlay">
              <div class="overlay-content">
                <div class="overlay-text">
                  <div class="folio-info">
                    <h3>Time Hours</h3>
                    <p>Design, Photography</p>
                  </div>
                  <div class="folio-overview">
                    <span class="folio-link"><a class="folio-read-more" href="#" data-single_url="portfolio-single.html" ><i class="fa fa-link"></i></a></span>
                    <span class="folio-expand"><a href="<?php echo Yii::getAlias('@web'); ?>/landing/images/portfolio/portfolio-details.jpg" data-lightbox="portfolio"><i class="fa fa-search-plus"></i></a></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="folio-item wow fadeInLeftBig" data-wow-duration="1000ms" data-wow-delay="800ms">
            <div class="folio-image">
              <img class="img-responsive" src="<?php echo Yii::getAlias('@web'); ?>/landing/images/portfolio/6.jpg" alt="">
            </div>
            <div class="overlay">
              <div class="overlay-content">
                <div class="overlay-text">
                  <div class="folio-info">
                    <h3>Time Hours</h3>
                    <p>Design, Photography</p>
                  </div>
                  <div class="folio-overview">
                    <span class="folio-link"><a class="folio-read-more" href="#" data-single_url="portfolio-single.html" ><i class="fa fa-link"></i></a></span>
                    <span class="folio-expand"><a href="<?php echo Yii::getAlias('@web'); ?>/landing/images/portfolio/portfolio-details.jpg" data-lightbox="portfolio"><i class="fa fa-search-plus"></i></a></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="folio-item wow fadeInRightBig" data-wow-duration="1000ms" data-wow-delay="900ms">
            <div class="folio-image">
              <img class="img-responsive" src="<?php echo Yii::getAlias('@web'); ?>/landing/images/portfolio/7.jpg" alt="">
            </div>
            <div class="overlay">
              <div class="overlay-content">
                <div class="overlay-text">
                  <div class="folio-info">
                    <h3>Time Hours</h3>
                    <p>Design, Photography</p>
                  </div>
                  <div class="folio-overview">
                    <span class="folio-link"><a class="folio-read-more" href="#" data-single_url="portfolio-single.html" ><i class="fa fa-link"></i></a></span>
                    <span class="folio-expand"><a href="<?php echo Yii::getAlias('@web'); ?>/landing/images/portfolio/portfolio-details.jpg" data-lightbox="portfolio"><i class="fa fa-search-plus"></i></a></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="folio-item wow fadeInLeftBig" data-wow-duration="1000ms" data-wow-delay="1000ms">
            <div class="folio-image">
              <img class="img-responsive" src="<?php echo Yii::getAlias('@web'); ?>/landing/images/portfolio/8.jpg" alt="">
            </div>
            <div class="overlay">
              <div class="overlay-content">
                <div class="overlay-text">
                  <div class="folio-info">
                    <h3>Time Hours</h3>
                    <p>Design, Photography</p>
                  </div>
                  <div class="folio-overview">
                    <span class="folio-link"><a class="folio-read-more" href="#" data-single_url="portfolio-single.html" ><i class="fa fa-link"></i></a></span>
                    <span class="folio-expand"><a href="<?php echo Yii::getAlias('@web'); ?>/landing/images/portfolio/portfolio-details.jpg" data-lightbox="portfolio"><i class="fa fa-search-plus"></i></a></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="portfolio-single-wrap">
      <div id="portfolio-single">
      </div>
    </div><!-- /#portfolio-single-wrap --REMOVE>
  </section><!--/#portfolio-->

<section id="pricing">
  <div class="container">
    <div class="row">
      <div class="heading text-center col-sm-8 col-sm-offset-2 wow fadeInUp" data-wow-duration="1200ms" data-wow-delay="300ms">
        <h2><?php echo Yii::t('default', 'landing_pricing Pricing Table') ?></h2>
        <p><?php echo Yii::t('default', 'landing_pricing What you get') ?></p>
      </div>
    </div>
    <div class="pricing-table">
      <div class="row">
       <!-- <div class="col-sm-12 col-md-12">
          <div class="single-table featured wow flipInY" data-wow-duration="1000ms" data-wow-delay="800ms">
            <h3><?php echo Yii::t('default', 'landing_pricing free Free') ?></h3>
            <div class="price"> <?php echo Yii::t('default', 'landing_pricing free Free') ?><span>/<?php echo Yii::t('default', 'landing_pricing free 6 Months') ?></span> </div>
            <ul>
              <li><?php echo Yii::t('default', 'landing_pricing free desc 1')?></li>
            </ul>
          </div>
        </div>-->
        <div class="col-sm-6 col-md-6">
          <div class="single-table  wow flipInY" data-wow-duration="1000ms" data-wow-delay="500ms">
            <h3><?php echo Yii::t('default', 'landing_pricing Auction and Ads') ?></h3>
            <div class="price"> <?php echo Yii::t('default', 'landing_pricing Auction Price') ?><span>/<?php echo Yii::t('default', 'landing_pricing Percentage on Auctions') ?></span> </div>
            <ul>
              <li><?php echo Yii::t('default', 'landing_pricing Auction and Ads desc 1') ?></li>
              <li><?php echo Yii::t('default', 'landing_pricing Auction and Ads desc 3') ?></li>
            </ul>
             <a href="<?= Url::to(['/auction/rules']) ?>" class="btn btn-lg btn-primary" target="_blank"><?= Yii::t('default', 'See details') ?></a>
          </div>
        </div>
        <div class="col-sm-6 col-md-6">
          <div class="single-table wow flipInY" data-wow-duration="1000ms" data-wow-delay="300ms">
            <h3><?php echo Yii::t('default', 'landing_pricing Annual Subscription') ?></h3>
            <div class="price"> 
				<!--<?php //echo Yii::t('default', 'landing_pricing Subs Price') ?>100$<span>/<?php echo Yii::t('default', 'landing_pricing Year') ?></span> 
				<br>
				<?php //echo Yii::t('default', 'landing_pricing Subs Price Month') ?>10$<span>/<?php echo Yii::t('default', 'landing_pricing Month') ?></span> -->
                <?php echo Yii::t('default', 'landing_pricing free Free') ?>
            </div>
            <ul>
              <li><?php echo Yii::t('default', 'landing_pricing Subs desc 1') ?></li>
              <li><?php echo Yii::t('default', 'landing_pricing Subs desc 2') ?></li>
              <li><?php echo Yii::t('default', 'landing_pricing Subs desc 4') ?></li>
              <li><?php echo Yii::t('default', 'landing_pricing Subs desc 5') ?></li>
              <li><?php echo Yii::t('default', 'landing_pricing Subs desc 6') ?></li>
              <li><?php echo Yii::t('default', 'landing_pricing Subs desc 7') ?></li>
              <li><?php echo Yii::t('default', 'landing_pricing Subs desc 8') ?></li>
              <li><?php echo Yii::t('default', 'landing_pricing Subs desc 9') ?></li>
            </ul>
            <!-- <a href="<?php echo Url::to('/user/registration/register') ?>" class="btn btn-lg btn-primary" target="_blank"><?php echo Yii::t('default', 'landing_link Register')?></a>--> 
          </div>
        </div>
        
        <!--<div class="col-sm-6 col-md-3">
          <div class="single-table wow flipInY" data-wow-duration="1000ms" data-wow-delay="800ms">
            <h3><?php echo Yii::t('default', 'landing_pricing Hardware Tracking') ?></h3>
            <div class="price"> <?php echo Yii::t('default', 'landing_pricing hardware price') ?><span></span> </div>
            <ul>
              <li><?php echo Yii::t('default', 'landing_pricing hardware desc 1') ?></li>
              <li><?php echo Yii::t('default', 'landing_pricing hardware desc 2') ?></li>
              <li><?php echo Yii::t('default', 'landing_pricing hardware desc 3') ?></li>
            </ul>
           <!-- <a href="#" class="btn btn-lg btn-primary">Sign up</a> ->
           </div>
        </div>
        <div class="col-sm-6 col-md-3">
          <div class="single-table wow flipInY" data-wow-duration="1000ms" data-wow-delay="1100ms">
            <h3>Professional</h3>
            <div class="price"> $49<span>/Month</span> </div>
            <ul>
              <li>Free Setup</li>
              <li>10GB Storage</li>
              <li>100GB Bandwith</li>
              <li>5 Products</li>
            </ul>
            <a href="#" class="btn btn-lg btn-primary">Sign up</a> </div>
        </div>--> 
      </div>
    </div>
  </div>
</section>
<!--/#pricing-->

<section id="team">
  <div class="container">
    <div class="row">
      <div class="heading text-center col-sm-8 col-sm-offset-2 wow fadeInUp" data-wow-duration="1200ms" data-wow-delay="300ms">
        <h2>
          <?= Yii::t('default','The team')?>
        </h2>
        <p></p>
      </div>
    </div>
    <div class="team-members">
      <div class="row">
        <div class="col-sm-6 col-md-3">
          <div class="team-member wow flipInY" data-wow-duration="1000ms" data-wow-delay="300ms">
            <div class="member-image"> <img class="img-responsive" src="<?php echo Yii::getAlias('@web'); ?>/landing/images/team/dario.jpg"  alt="Dario Trbović"> </div>
            <div class="member-info">
              <h3>Dario Trbović - CEO</h3>
              <h4>
                <?= Yii::t('default','landing_team CEO')?>
              </h4>
              <p>
                <?= Yii::t('default','landing_team Web')?>
              </p>
            </div>
            <div class="social-icons">
              <ul>
                <li><a class="linkedin" href="https://www.linkedin.com/in/dariotrbovic" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                <li><a class="rss" href="mailto:dario.trbovic@pippion.com" target="_blank"><i class="fa fa-envelope"></i></a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-md-3">
          <div class="team-member wow flipInY" data-wow-duration="1000ms" data-wow-delay="500ms">
            <div class="member-image"> <img class="img-responsive" src="<?php echo Yii::getAlias('@web'); ?>/landing/images/team/ivan.jpg" alt="Ivan Jurlina"> </div>
            <div class="member-info">
              <h3>Ivan Jurlina - COO</h3>
              <h4>
                <?= Yii::t('default','landing_team marketing and founder')?>
              </h4>
              <p>
                <?= Yii::t('default','landing_team Marketing')?>
              </p>
            </div>
            <div class="social-icons">
              <ul>
                <li><a class="linkedin" href="https://www.linkedin.com/in/ivanjurlina" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                <li><a class="rss" href="mailto:ivan.jurlina@pippion.com" target="_blank"><i class="fa fa-envelope"></i></a></li>
              </ul>
            </div>
          </div>
        </div>
        
        <div class="col-sm-6 col-md-3">
          <div class="team-member wow flipInY" data-wow-duration="1000ms" data-wow-delay="800ms">
            <div class="member-image"> <img class="img-responsive" src="<?php echo Yii::getAlias('@web'); ?>/landing/images/team/mario.jpg" alt="Mario Hribar"> </div>
            <div class="member-info">
              <h3>Mario Hribar - CTO</h3>
              <h4>
                <?= Yii::t('default','landing_team Dev and founder')?>
              </h4>
              <p>
                <?= Yii::t('default','landing_team Mobile')?>
              </p>
            </div>
            <div class="social-icons">
              <ul>
                <li><a class="linkedin" href="https://hr.linkedin.com/in/mariohribar" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                <li><a class="rss" href="mailto:mario.hribar@pippion.com" target="_blank"><i class="fa fa-envelope"></i></a></li>
              </ul>
            </div>
          </div>
        </div>
        
        <div class="col-sm-6 col-md-3">
          <div class="team-member wow flipInY" data-wow-duration="1000ms" data-wow-delay="800ms">
            <div class="member-image"> <img class="img-responsive" src="<?php echo Yii::getAlias('@web'); ?>/landing/images/team/dario_almasi.jpg" alt="Dario Almaši"> </div>
            <div class="member-info">
              <h3>Dario Almaši</h3>
              <h4>
                <?= Yii::t('default','landing_team Breeder')?>
              </h4>
              <p>
                <?= Yii::t('default','landing_team Advisor')?>
              </p>
            </div>
            <div class="social-icons">
              <ul>
                <li><a class="rss" href="mailto:almasidario@gmail.com" target="_blank"><i class="fa fa-envelope"></i></a></li>
                <li><a class="twitter" href="http://www.skuglpakrac.com/" target="_blank"><i class="fa fa-link"></i></a></li>
              </ul>
            </div>
          </div>
        </div>

        
      </div>
    </div>
    <div class="row">
      <div class="heading text-center col-sm-8 col-sm-offset-2 wow fadeInUp" data-wow-duration="1200ms" data-wow-delay="300ms" style="padding-bottom:20px; padding-top:20px;">
        <h2>
          <?= Yii::t('default','landing_media In media')?>
        </h2>
      </div>
      <div class="col-sm-12 col-md-12"> <strong>In English</strong>
        <ul>
          <li><a href="https://www.facebook.com/GaussDevelopment/photos/a.247025022061893.52106.236955229735539/675595509204840/?type=1" target="_blank">https://www.facebook.com/GaussDevelopment/photos/a.247025022061893.52106.236955229735539/675595509204840/?type=1</a> </li>
        </ul>
        <strong>In Croatian</strong>
        <ul>
          <li> <a href="http://zimo.dnevnik.hr/startup/izvjestaj-sa-odrzanog-akceleratorskog-poduzetnickog-kampa-osijek/10024#.VGYPwfnF-E0" target="_blank">http://zimo.dnevnik.hr/startup/izvjestaj-sa-odrzanog-akceleratorskog-poduzetnickog-kampa-osijek/10024#.VGYPwfnF-E0</a></li>
          <li><a href="http://djakovo-danas.hr/djakovo-danas/zanimljivosti/688-pippion-daria-trbovica-pobjednik-ovogodisnjeg-acceleration-boot-campa-2014" target="_blank">http://djakovo-danas.hr/djakovo-danas/zanimljivosti/688-pippion-daria-trbovica-pobjednik-ovogodisnjeg-acceleration-boot-campa-2014</a></li>
          <li><a href="http://www.radio-djakovo.hr/2014/11/aplikacijom-za-golubare-do-novca-za-vlastitu-tvrtku/" target="_blank">http://www.radio-djakovo.hr/2014/11/aplikacijom-za-golubare-do-novca-za-vlastitu-tvrtku/</a></li>
          <li><a href="https://www.pippion.com/images/media/pippion_jutarnji_list.jpg" target="_blank">Jutarnji List Newspapers</a></li>
          <li><a href="https://www.facebook.com/accelerationbootcamposijek/photos/a.387754224707211.1073741828.386670851482215/395685860580714/?type=1" target="_blank">https://www.facebook.com/accelerationbootcamposijek/photos/a.387754224707211.1073741828.386670851482215/395685860580714/?type=1</a></li>
          <li><a href="http://www.osijek031.com/osijek.php?topic_id=53819" target="_blank">http://www.osijek031.com/osijek.php?topic_id=53819</a></li>
          <li><a href="http://inchoo.hr/acceleration-bootcamp-osijek/" target="_blank">http://inchoo.hr/acceleration-bootcamp-osijek/</a></li>
          <li><a href="http://www.netokracija.com/pippion-akceleratorski-poduzetnicki-kamp-osijek-91254" target="_blank">http://www.netokracija.com/pippion-akceleratorski-poduzetnicki-kamp-osijek-91254</a></li>
          <li><a href="http://gauss-informatika.com/pippion/#more-1623" target="_blank">http://gauss-informatika.com/pippion/#more-1623</a></li>
          <li><a href="https://www.facebook.com/gauss.informatika/posts/728734240544924" target="_blank">https://www.facebook.com/gauss.informatika/posts/728734240544924</a></li>
          <li><a href="http://softwarecity.hr/opcenito/intervju-pippion-servis-za-natjecatelje-uzgajivace-golubova/" target="_blank">http://softwarecity.hr/opcenito/intervju-pippion-servis-za-natjecatelje-uzgajivace-golubova/</a></li>
          <li><a href="https://www.pippion.com/images/media/glas_slavonije_article.jpg" target="_blank">Glas Slavonije Newspapers</a></li>
          <li><a href="https://www.pippion.com/images/media/glas_slavonije_frontpage.jpg" target="_blank">Glas Slavonije Frontpage</a></li>
        </ul>
      </div>
    </div>
  </div>
</section>
<!--/#team-->

<section id="twitter" class="parallax">
  <div>
    <div class="container">
      <div class="row">
        <div class="col-sm-8 col-sm-offset-2 wow fadeIn" data-wow-duration="1000ms" data-wow-delay="300ms">
          <div class="twitter-icon text-center"> <i class="fa fa-share-alt"></i>
            <h4><?php echo Yii::t('default', 'landing_share Share') ?></h4>
          </div>
          <div id="twitter-carousel" class="carousel slide" data-ride="carousel">
            <?php 	$x= new ExtraFunctions;
					$x->shareButtons();
			 ?>
            <!--
              <ol class="carousel-indicators">
                <li data-target="#twitter-carousel" data-slide-to="0" class="active"></li>
                <li data-target="#twitter-carousel" data-slide-to="1"></li>
                <li data-target="#twitter-carousel" data-slide-to="2"></li>
              </ol><!-- /.carousel-indicators --

              <div class="carousel-inner">
                <div class="item active">
                  <p>Introducing Shortcode generator for Helix V2 based templates <a href="#"><span>#helixframework #joomla</span> http://bit.ly/1qlgwav</a></p>
                </div>
                <div class="item">
                  <p>Introducing Shortcode generator for Helix V2 based templates <a href="#"><span>#helixframework #joomla</span> http://bit.ly/1qlgwav</a></p>
                </div>
                <div class="item">                                
                  <p>Introducing Shortcode generator for Helix V2 based templates <a href="#"><span>#helixframework #joomla</span> http://bit.ly/1qlgwav</a></p>
                </div>
              </div>--> 
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--/#twitter--> 

<!--
  <section id="blog">
    <div class="container">
      <div class="row">
        <div class="heading text-center col-sm-8 col-sm-offset-2 wow fadeInUp" data-wow-duration="1200ms" data-wow-delay="300ms">
          <h2>Blog Posts</h2>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua ut enim ad minim veniam</p>
        </div>
      </div>
      <div class="blog-posts">
        <div class="row">
          <div class="col-sm-4 wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="400ms">
            <div class="post-thumb">
              <a href="#"><img class="img-responsive" src="<?php echo Yii::getAlias('@web'); ?>/landing/images/blog/1.jpg" alt=""></a> 
              <div class="post-meta">
                <span><i class="fa fa-comments-o"></i> 3 Comments</span>
                <span><i class="fa fa-heart"></i> 0 Likes</span> 
              </div>
              <div class="post-icon">
                <i class="fa fa-pencil"></i>
              </div>
            </div>
            <div class="entry-header">
              <h3><a href="#">Lorem ipsum dolor sit amet consectetur adipisicing elit</a></h3>
              <span class="date">June 26, 2014</span>
              <span class="cetagory">in <strong>Photography</strong></span>
            </div>
            <div class="entry-content">
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
            </div>
          </div>
          <div class="col-sm-4 wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="600ms">
            <div class="post-thumb">
              <div id="post-carousel"  class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                  <li data-target="#post-carousel" data-slide-to="0" class="active"></li>
                  <li data-target="#post-carousel" data-slide-to="1"></li>
                  <li data-target="#post-carousel" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                  <div class="item active">
                    <a href="#"><img class="img-responsive" src="<?php echo Yii::getAlias('@web'); ?>/landing/images/blog/2.jpg" alt=""></a>
                  </div>
                  <div class="item">
                    <a href="#"><img class="img-responsive" src="<?php echo Yii::getAlias('@web'); ?>/landing/images/blog/1.jpg" alt=""></a>
                  </div>
                  <div class="item">
                    <a href="#"><img class="img-responsive" src="<?php echo Yii::getAlias('@web'); ?>/landing/images/blog/3.jpg" alt=""></a>
                  </div>
                </div>                               
                <a class="blog-left-control" href="#post-carousel" role="button" data-slide="prev"><i class="fa fa-angle-left"></i></a>
                <a class="blog-right-control" href="#post-carousel" role="button" data-slide="next"><i class="fa fa-angle-right"></i></a>
              </div>                            
              <div class="post-meta">
                <span><i class="fa fa-comments-o"></i> 3 Comments</span>
                <span><i class="fa fa-heart"></i> 0 Likes</span> 
              </div>
              <div class="post-icon">
                <i class="fa fa-picture-o"></i>
              </div>
            </div>
            <div class="entry-header">
              <h3><a href="#">Lorem ipsum dolor sit amet consectetur adipisicing elit</a></h3>
              <span class="date">June 26, 2014</span>
              <span class="cetagory">in <strong>Photography</strong></span>
            </div>
            <div class="entry-content">
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
            </div>
          </div>
          <div class="col-sm-4 wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="800ms">
            <div class="post-thumb">
              <a href="#"><img class="img-responsive" src="<?php echo Yii::getAlias('@web'); ?>/landing/images/blog/3.jpg" alt=""></a>
              <div class="post-meta">
                <span><i class="fa fa-comments-o"></i> 3 Comments</span>
                <span><i class="fa fa-heart"></i> 0 Likes</span> 
              </div>
              <div class="post-icon">
                <i class="fa fa-video-camera"></i>
              </div>
            </div>
            <div class="entry-header">
              <h3><a href="#">Lorem ipsum dolor sit amet consectetur adipisicing elit</a></h3>
              <span class="date">June 26, 2014</span>
              <span class="cetagory">in <strong>Photography</strong></span>
            </div>
            <div class="entry-content">
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
            </div>
          </div>                    
        </div>
        <div class="load-more wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="500ms">
          <a href="#" class="btn btn-loadmore"><i class="fa fa-repeat"></i> Load More</a>
        </div>                
      </div>
    </div>
  </section><!--/#blog-->

<section id="contact"> 
  <!-- <div id="google-map" style="height:350px" class="wow fadeIn" data-latitude="52.365629" data-longitude="4.871331" data-wow-duration="1000ms" data-wow-delay="400ms"></div>-->
  <div class="text-center">
    <h2><?php echo Yii::t('default', 'landing_contact Contact us') ?></h2>
  </div>
  <div class="container">
    <div class="row">
      <div class="heading text-center col-sm-8 col-sm-offset-2 wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="300ms">
        <p><strong><?php echo Yii::t('default', 'landing_contact description 1') ?></strong></p>
      </div>
    </div>
    <div class="contact-form wow fadeIn" data-wow-duration="1000ms" data-wow-delay="600ms">
      <div class="row">
        <div class="col-sm-12">
          <?= Html::beginForm(['/site/what-is-pippion'], 'post', ["id"=>"main-contact-form", "name"=>"contact-form"]) ?>
          <div class="row  wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="300ms">
            <div class="col-sm-6">
              <div class="form-group">
                <input type="text" name="name" class="form-control" placeholder="<?= Yii::t('default', 'Contact Name') ?>" required>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="<?= Yii::t('default', 'Contact Email Address') ?>" required>
              </div>
            </div>
          </div>
          <div class="form-group">
            <input type="text" name="subject" class="form-control" placeholder="<?= Yii::t('default', 'Contact Subject') ?>" required>
          </div>
          <div class="form-group">
            <textarea name="message" id="message" class="form-control" rows="4" placeholder="<?= Yii::t('default', 'Contact Enter your message') ?>" required></textarea>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg btn-block"><?php echo Yii::t('default', 'landing_contact Send') ?></button>
          </div>
          <?= Html::endForm(); ?>
        </div>
        <!-- <div class="col-sm-6">
      <div class="contact-info wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="300ms">
        <p></p>
       <ul class="address">
          <li><i class="fa fa-map-marker"></i> <span> Address:</span> 2400 South Avenue A </li>
          <li><i class="fa fa-phone"></i> <span> Phone:</span> +928 336 2000  </li>
          <li><i class="fa fa-envelope"></i> <span> Email:</span><a href="mailto:someone@yoursite.com"> support@oxygen.com</a></li>
          <li><i class="fa fa-globe"></i> <span> Website:</span> <a href="#">www.sitename.com</a></li>
        </ul>
      </div>                            
    </div>--> 
      </div>
    </div>
  </div>
  <div id="contact-us" class="parallax"> 
    <!-- ovdje je bio .container koji je sad iznad ovog #contact-us --> 
  </div>
</section>
<!--/#contact--> 

<!--
  <section id="about-us" class="parallax">
    <div class="container">
      <div class="row">
        <div class="col-sm-6" >
        
          <div class="about-info wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="300ms">
            <h2>About us</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.Ullamco laboris nisi ut aliquip ex ea commodo consequat. </p>
            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
          </div>
          
        </div>
        <div class="col-sm-6">
        
          <div class="our-skills wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
            <div class="single-skill wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="300ms">
              <p class="lead">User Experiances</p>
              <div class="progress">
                <div class="progress-bar progress-bar-primary six-sec-ease-in-out" role="progressbar"  aria-valuetransitiongoal="95">95%</div>
              </div>
            </div>
            <div class="single-skill wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="400ms">
              <p class="lead">Web Design</p>
              <div class="progress">
                <div class="progress-bar progress-bar-primary six-sec-ease-in-out" role="progressbar"  aria-valuetransitiongoal="75">75%</div>
              </div>
            </div>
            <div class="single-skill wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="500ms">
              <p class="lead">Programming</p>
              <div class="progress">
                <div class="progress-bar progress-bar-primary six-sec-ease-in-out" role="progressbar"  aria-valuetransitiongoal="60">60%</div>
              </div>
            </div>
            <div class="single-skill wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
              <p class="lead">Fun</p>
              <div class="progress">
                <div class="progress-bar progress-bar-primary six-sec-ease-in-out" role="progressbar"  aria-valuetransitiongoal="85">85%</div>
              </div>
            </div>
          </div>
        </div>
        
      </div>
    </div>
  </section><!--/#about-us-->

<footer id="footer">
  <div class="footer-top wow fadeInUp" data-wow-duration="1000ms" data-wow-delay="300ms">
    <div class="container text-center">
      <div class="footer-logo"> <a href="/index.php"><img class="img-responsive" src="<?php echo Yii::getAlias('@web'); ?>/images/pippion_logo_white.png"  alt="<?= $image_alt?>"></a> </div>
      <div class="social-icons">
        <?php 	$x= new ExtraFunctions;
				$x->shareButtons();
		 ?>
        <!--<ul>
          <li><a class="envelope" href="#"><i class="fa fa-envelope"></i></a></li>
          <li><a class="twitter" href="#"><i class="fa fa-twitter"></i></a></li>
          <li><a class="dribbble" href="#"><i class="fa fa-dribbble"></i></a></li>
          <li><a class="facebook" href="#"><i class="fa fa-facebook"></i></a></li>
          <li><a class="linkedin" href="#"><i class="fa fa-linkedin"></i></a></li>
          <li><a class="tumblr" href="#"><i class="fa fa-tumblr-square"></i></a></li>
        </ul>--> 
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="container">
      <div class="row">
        <div class="col-sm-6">
          <p>&copy; <a href="https://<?php echo $_SERVER['SERVER_NAME'] ?>">Pippion</a> &copy; 2012 - <?php echo date("Y") ?></p>
        </div>
        <div class="col-sm-6"> 
          <!--<p class="pull-right">Designed by <a href="http://www.themeum.com/">Themeum</a></p>--> 
        </div>
      </div>
    </div>
  </div>
</footer>

<!-- BEGIN twitter and facebook buttons -->
<style>
.twitter-follow-button
{
	position:fixed!important;
	right:10px;
	bottom:10px;
}
.fb-like
{
	position:fixed!important;
	right:10px;
	bottom:40px;
}
</style>

<?= ExtraFunctions::twitterFacebookButton(); ?>
<!-- END twitter and facebook buttons -->

</body>
</html>