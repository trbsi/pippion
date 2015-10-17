<?php
use yii\helpers\Url;
use yii\helpers\Html;
use backend\helpers\ExtraFunctions;
use backend\models\Breeder;

$Breeder = new Breeder;
$ef = new ExtraFunctions;
?>
<div class="header navbar navbar-inverse "> 
  <!-- BEGIN TOP NAVIGATION BAR -->
  <div class="navbar-inner">
	<div class="header-seperation"> 
		<ul class="nav pull-left notifcation-center" id="main-menu-toggle-wrapper" style="display:none">	
		 <li class="dropdown"> <a id="main-menu-toggle" href="#main-menu"  class="" > <div class="iconset top-menu-toggle-white"></div> </a> </li>		 
		</ul>
      <!-- BEGIN LOGO -->	
      <a href="/index.php"><img src="<?= Yii::getAlias('@web') ?>/images/logo.png" class="logo" alt=""  data-src="<?= Yii::getAlias('@web') ?>/images/logo.png" data-src-retina="<?= Yii::getAlias('@web') ?>/images/logo.png" width="108" height="24"/></a>
      <!-- END LOGO --> 
      <ul class="nav pull-right notifcation-center">	
        <li class="dropdown" id="header_task_bar"> <a href="/index.php" class="dropdown-toggle active" data-toggle=""> <div class="iconset top-home"></div> </a> </li>
        <li class="dropdown" id="header_inbox_bar" > <a href="<?= Url::to(["/messages/messages/inbox"]) ?>" class="dropdown-toggle" > <div class="iconset top-messages"></div>  <span class="badge js-msgs-badge" ></span> </a></li>
        
		<li class="dropdown" id="portrait-chat-toggler" style="display:none"> <a href="#sidr" class="chat-menu-toggle"> <div class="iconset top-chat-white "></div> </a> </li>        
      </ul>
      </div>
      <!-- END RESPONSIVE MENU TOGGLER --> 
      <div class="header-quick-nav" > 
      <!-- BEGIN TOP NAVIGATION MENU -->
      <button type="button" style="display:none;" class="js-condensMenuM" onclick="$('body').condensMenu();"></button>
	  <div class="pull-left"> 
        <ul class="nav quick-section">
          <li class="quicklinks"> <a href="javascript:;" class="js-condensMenuCookieSetBtn" id="layout-condensed-toggle" >
            <div class="iconset top-menu-toggle-dark"></div>
            </a> </li>
        </ul>
        <ul class="nav quick-section">
          <li class="quicklinks"> <a href="<?php echo 'http'.(isset($_SERVER['HTTPS']) ? 's' : '').'://'. "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"; ?>" class="" >
            <div class="iconset top-reload"></div>
            </a> </li>
          <!--<li class="quicklinks"> <span class="h-seperate"></span></li>
          <li class="quicklinks"> <a href="#" class="" >
            <div class="iconset top-tiles"></div>
            </a> </li>-->
            <!-- BEGIN SEARCH BOX -->
            <li class="m-r-10 input-prepend inside search-form no-boarder">
                <span class="add-on"><span class="iconset top-search"></span></span>
                <form action="<?php echo Url::to('/pigeon/index'); ?>" method="get" style="display:inline">
                    <input name="PigeonSearch[pigeonnumber]" type="text" class="no-boarder" placeholder="<?php echo Yii::t('default', 'Search Pigeons'); ?>" style="width:220px;">
                </form>
            </li>
            <!-- END SEARCH BOX -->
            
            <!--BEGIN CHOOSE LANGUAGE -->
            <li class="m-r-10 input-prepend inside search-form no-boarder">
				<?php  $ef->prikaziJezike(); ?>
            </li>
            <!-- END CHOOSE LANGUAGE -->

		  </ul>
	  </div>
	 <!-- END TOP NAVIGATION MENU -->
	 <!-- BEGIN CHAT TOGGLER -->
      <div class="pull-right"> 
		<div class="chat-toggler">	
				<a href="#" class="dropdown-toggle" id="my-task-list" data-placement="bottom"  data-content='' data-toggle="dropdown" data-original-title="Notifications">
					<div class="user-details"> 
						<div class="username">
							<!--<span class="badge badge-important">3</span> -->
							<?php 
							if(Yii::$app->user->isGuest)
								echo "Guest";
							else 
								echo ExtraFunctions::getCookie(\Yii::$app->params['username_cookie']); 
							?>									
						</div>						
					</div> 
					<div class="iconset top-down-arrow"></div>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</a>	
				<!--<div id="notification-list" style="display:none">
					<div style="width:300px">
						  <div class="notification-messages info">
									<div class="user-profile">
										<img src="assets/img/profiles/d.jpg"  alt="" data-src="assets/img/profiles/d.jpg" data-src-retina="assets/img/profiles/d2x.jpg" width="35" height="35">
									</div>
									<div class="message-wrapper">
										<div class="heading">
											David Nester - Commented on your wall
										</div>
										<div class="description">
											Meeting postponed to tomorrow
										</div>
										<div class="date pull-left">
										A min ago
										</div>										
									</div>
									<div class="clearfix"></div>									
								</div>	
							<div class="notification-messages danger">
								<div class="iconholder">
									<i class="icon-warning-sign"></i>
								</div>
								<div class="message-wrapper">
									<div class="heading">
										Server load limited
									</div>
									<div class="description">
										Database server has reached its daily capicity
									</div>
									<div class="date pull-left">
									2 mins ago
									</div>
								</div>
								<div class="clearfix"></div>
							</div>	
							<div class="notification-messages success">
								<div class="user-profile">
									<img src="assets/img/profiles/h.jpg"  alt="" data-src="assets/img/profiles/h.jpg" data-src-retina="assets/img/profiles/h2x.jpg" width="35" height="35">
								</div>
								<div class="message-wrapper">
									<div class="heading">
										You haveve got 150 messages
									</div>
									<div class="description">
										150 newly unread messages in your inbox
									</div>
									<div class="date pull-left">
									An hour ago
									</div>									
								</div>
								<div class="clearfix"></div>
							</div>							
						</div>				
				</div>-->
				<div class="profile-pic"> 
					<a href="<?= $profile_picture?>" class="profile_pic_colorbox"><img src="<?= $profile_picture ?>"  alt="" data-src="<?= $profile_picture ?>" data-src-retina="<?= $profile_picture ?>" width="35" height="35" /> </a>
				</div>       			
			</div>
		 <ul class="nav quick-section ">
			<li class="quicklinks"> 
				<a data-toggle="dropdown" class="dropdown-toggle  pull-right " href="#" id="user-options">						
					<div class="iconset top-settings-dark "></div> 	
				</a>
				<ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="user-options">
                  
                  <li><a href="<?= Url::to('/user/settings/account') ?>"><i class="fa fa-key"></i>&nbsp;&nbsp;<?= Yii::t('default', 'Change email password'); ?></a>
                  </li>
                  <li><a href="<?= Url::to('/user/settings/networks') ?>" ><i class="fa fa-facebook"></i>&nbsp;&nbsp;<?= Yii::t('default', 'Connect with Facebook'); ?></a>
                  </li>
                  <!--<li><a href="<?= Url::to('/user/settings/networks') ?>" ><i class="fa fa-google"></i>&nbsp;&nbsp;<?= Yii::t('default', 'Connect with Google'); ?></a>
                  </li>-->

                  <li class="divider"></li>                
                  <li><a href="<?= Url::to('/user/security/logout') ?>" data-method='post'><i class="fa fa-power-off"></i>&nbsp;&nbsp;<?= Yii::t('default', 'Logout'); ?></a></li>
               </ul>
			</li> 
			<!--<li class="quicklinks"> <span class="h-seperate"></span></li> 
			<li class="quicklinks"> 	
			<a id="chat-menu-toggle" href="#sidr" class="chat-menu-toggle" ><div class="iconset top-chat-dark "><span class="badge badge-important hide" id="chat-message-count">1</span></div>
			</a> 
				<div class="simple-chat-popup chat-menu-toggle hide" >
					<div class="simple-chat-popup-arrow"></div><div class="simple-chat-popup-inner">
						 <div style="width:100px">
						 <div class="semi-bold">David Nester</div>
						 <div class="message">Hey you there </div>
						</div>
					</div>
				</div>
			</li> -->
		</ul>
      </div>
	   <!-- END CHAT TOGGLER -->
      </div> 
      <!-- END TOP NAVIGATION MENU --> 
   
  </div>
  <!-- END TOP NAVIGATION BAR --> 
</div>