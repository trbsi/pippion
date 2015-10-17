<?php
use backend\helpers\ExtraFunctions;
use backend\models\Breeder;
use backend\models\Subscription;
use yii\helpers\Url;

$this->title = Yii::$app->name;
$ef = new ExtraFunctions;
$rand=$ef->Wallpapers();

$Breeder = new Breeder;
$Subscription = new Subscription;
?>
<?= $this->render('/_alert'); ?>

<div class="row">
 
    <?php /*<div class="col-md-12" style="margin-bottom:15px;">
        <div class="tiles red col-md-12  no-padding">
          <div class="tiles-body">
            <div class="row">
              <div class="col-md-12">
               <strong><?= Yii::t('default', '50% off'); ?></strong>
            </div>
          </div>
        </div>
    </div>
  </div>*/
  ?>

  <div class="col-md-6">
    <div class=" tiles white col-md-12 no-padding">
      <div class="tiles green cover-pic-wrapper">
        <?php /*<div class="overlayer bottom-right">
          <div class="overlayer-wrapper">
            <div class="padding-10 hidden-xs">
              <button type="button" class="btn btn-primary btn-small"><i class="fa fa-check"></i>&nbsp;&nbsp;Following</button>
              <button type="button" class="btn btn-primary btn-small">Add</button>
            </div>
          </div>
        </div> */ ?>
        <img src="<?php echo Yii::getAlias('@web'); ?>/images/wallpapers/profilecover/<?php echo $rand ?>" alt=""> </div>
      <div class="tiles white">
        <div class="row">
          <div class="col-md-3 col-sm-3">
            <div class="user-profile-pic"> <a href="<?= $profile_picture?>" class="profile_pic_colorbox"><img width="69" height="69" data-src-retina="<?= $profile_picture ?>" data-src="<?= $profile_picture ?>" src="<?= $profile_picture ?>" alt="" > </a></div>
            <?php /*<div class="user-mini-description">
              <h3 class="text-success semi-bold"> 2548 </h3>
              <h5>Followers</h5>
              <h3 class="text-success semi-bold"> 457 </h3>
              <h5>Following</h5>
            </div>*/?>
          </div>
          <?php /*<div class="col-md-5 user-description-box  col-sm-5">
            <h4 class="semi-bold no-margin">John Smith</h4>
            <h6 class="no-margin">CEO of web-arch.co.uk</h6>
            <br>
            <p><i class="fa fa-briefcase"></i>UI &amp; Graphic Design</p>
            <p><i class="fa fa-globe"></i>www.google.com</p>
            <p><i class="fa fa-file-o"></i>Download Resume</p>
            <p><i class="fa fa-envelope"></i>Send Message</p>
          </div>
          <div class="col-md-3  col-sm-3">
            <h5 class="normal">Friends ( <span class="text-success">1223</span> )</h5>
            <ul class="my-friends">
              <li>
                <div class="profile-pic"> <img width="35" height="35" data-src-retina="assets/img/profiles/d2x.jpg" data-src="assets/img/profiles/d.jpg" src="assets/img/profiles/d.jpg" alt=""> </div>
              </li>
              <li>
                <div class="profile-pic"> <img width="35" height="35" data-src-retina="assets/img/profiles/c2x.jpg" data-src="assets/img/profiles/c.jpg" src="assets/img/profiles/c.jpg" alt=""> </div>
              </li>
              <li>
                <div class="profile-pic"> <img width="35" height="35" data-src-retina="assets/img/profiles/h2x.jpg" data-src="assets/img/profiles/h.jpg" src="assets/img/profiles/h.jpg" alt=""> </div>
              </li>
              <li>
                <div class="profile-pic"> <img width="35" height="35" data-src-retina="assets/img/profiles/avatar_small2x.jpg" data-src="assets/img/profiles/avatar_small.jpg" src="assets/img/profiles/avatar_small.jpg" alt=""> </div>
              </li>
              <li>
                <div class="profile-pic"> <img width="35" height="35" data-src-retina="assets/img/profiles/e2x.jpg" data-src="assets/img/profiles/e.jpg" src="assets/img/profiles/e.jpg" alt=""> </div>
              </li>
              <li>
                <div class="profile-pic"> <img width="35" height="35" data-src-retina="assets/img/profiles/b2x.jpg" data-src="assets/img/profiles/b.jpg" src="assets/img/profiles/b.jpg" alt=""> </div>
              </li>
              <li>
                <div class="profile-pic"> <img width="35" height="35" data-src-retina="assets/img/profiles/h2x.jpg" data-src="assets/img/profiles/h.jpg" src="assets/img/profiles/h.jpg" alt=""> </div>
              </li>
              <li>
                <div class="profile-pic"> <img width="35" height="35" data-src-retina="assets/img/profiles/d2x.jpg" data-src="assets/img/profiles/d.jpg" src="assets/img/profiles/d.jpg" alt=""> </div>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>*/ ?>
        </div>
        <div class="tiles-body">
          <div class="row">
            <?= $this->render('view', ['model'=>$model, 'profile_picture'=>$profile_picture]); ?>
            <?php /*<div class="post col-md-12">
              <div class="user-profile-pic-wrapper">
                <div class="user-profile-pic-normal"> <img width="35" height="35" data-src-retina="assets/img/profiles/c2x.jpg" data-src="assets/img/profiles/c.jpg" src="assets/img/profiles/c.jpg" alt=""> </div>
              </div>
             <div class="info-wrapper">
                <div class="username"> <span class="dark-text">John Drake</span> in <span class="dark-text">nervada hotspot</span> </div>
                <div class="info"> Great design concepts by <span class="dark-text">John Smith</span> and his crew! Totally owned the WCG!, Best of luck for your future endeavours, 
                  Special thanks for <span class="dark-text">Jane smith</span> for her motivation ;) </div>
                <div class="more-details">
                  <ul class="post-links">
                    <li><a href="#" class="muted">2 Minutes ago</a></li>
                    <li><a href="#" class="text-info">Collapse</a></li>
                    <li><a href="#" class="text-info"><i class="fa fa-reply"></i> Reply</a></li>
                    <li><a href="#" class="text-warning"><i class="fa fa-star"></i> Favourited</a></li>
                    <li><a href="#" class="muted">More</a></li>
                  </ul>
                </div>
                <div class="clearfix"></div>
                <ul class="action-bar">
                  <li><a href="#" class="muted"><i class="fa fa-comment"></i> 1584</a> Comments</li>
                  <li><a href="#" class="text-error"><i class="fa fa-heart"></i> 47k</a> likes</li>
                </ul>
                <div class="clearfix"></div>
                <div class="post comments-section">
                  <div class="user-profile-pic-wrapper">
                    <div class="user-profile-pic-normal"> <img width="35" height="35" alt="" src="assets/img/profiles/e.jpg" data-src="assets/img/profiles/e.jpg" data-src-retina="assets/img/profiles/e2x.jpg"> </div>
                  </div>
                  <div class="info-wrapper">
                    <div class="username"> <span class="dark-text">Thunderbolt</span> </div>
                    <div class="info"> Congrats, <span class="dark-text">John Smith</span> &amp; <span class="dark-text">Jane Smith</span> </div>
                    <div class="more-details">
                      <ul class="post-links">
                        <li><a href="#" class="muted">2 Minutes ago</a></li>
                        <li><a href="#" class="text-error"><i class="fa fa-heart"></i> Like</a></li>
                        <li><a href="#" class="muted">Details</a></li>
                      </ul>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                </div>
                <div class="post comments-section">
                  <div class="user-profile-pic-wrapper">
                    <div class="user-profile-pic-normal"> <img width="35" height="35" src="assets/img/profiles/h.jpg" data-src="assets/img/profiles/h.jpg" data-src-retina="assets/img/profiles/h2x.jpg" alt=""> </div>
                  </div>
                  <div class="info-wrapper">
                    <div class="username"> <span class="dark-text">Thunderbolt</span> </div>
                    <div class="info"> Congrats, <span class="dark-text">John Smith</span> &amp; <span class="dark-text">Jane Smith</span> </div>
                    <div class="more-details">
                      <ul class="post-links">
                        <li><a href="#" class="muted">2 Minutes ago</a></li>
                        <li><a href="#" class="text-error"><i class="fa fa-heart"></i> Like</a></li>
                        <li><a href="#" class="muted">Details</a></li>
                      </ul>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                </div>
              </div>
              <div class="clearfix"></div>
              <br>
              <br>
            </div>*/ ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- IZMJENA START-->
  <div class="visible-xs m-t-20"></div>
  <!-- IZMJENA END-->
  <div class="col-md-6">
    <div class="tiles white col-md-12  no-padding">
      <div class="tiles-body">
        <h5><span class="semi-bold">
          <?= Yii::t('default','Social networks connect'); ?>
          </span></h5>
        <div class="row">
          <div class="col-md-12">
            <?= '<p style="font-size:11px"><i class="fa fa-info-circle"></i>&nbsp;&nbsp;'.Yii::t('default', 'Social networks connect info').'</p>'; ?>
            <!--<div class="m-t-10 m-b-10" style="border-bottom:1px solid #dddddd;"></div>-->
            <?php //echo $Breeder->isConnectedGoogle(); ?>
            <div class="m-t-10 m-b-10" style="border-bottom:1px solid #dddddd;"></div>
            <?php echo $Breeder->isConnectedFacebook(); ?> </div>
        </div>
      </div>
    </div>
    
   <?php /*<div class="tiles white col-md-12 m-t-20">
      <div class="tiles-body">
        <h5><span class="semi-bold">
          <?= Yii::t('default', 'Admin notifications'); ?>
          </span></h5>
        <div class="row">
          <div class="col-md-12">
            <?= $this->render('/admin-nots/index', ['adminNotsDataProvider'=>$adminNotsDataProvider]);	?>
          </div>
        </div>
      </div>
    </div>*/
	?>
    
    <div class="tiles green col-md-6 m-t-20">
      <div class="tiles-body">
        <h5><span class="semi-bold"></span></h5>
        <div class="row">
          <div class="col-md-12 text-center">
          	<a href="<?= Url::to(['/auction/opened'])?>" style="color:white">
			<i class="fa fa-legal fa-5x"></i><br />
            <h3 style="height:50px;color:white;"><?= Yii::t('default', 'Auctions') ?></h3>
            </a>
          </div>
        </div>
      </div>
    </div>
        
    <div class="tiles white col-md-6 m-t-20">
      <div class="tiles-body">
        <h5><span class="semi-bold"></span></h5>
        <div class="row">
          <div class="col-md-12 text-center">
          	<a href="<?= Url::to(['/site/tutorials'])?>">
			<i class="fa fa-question-circle fa-5x"></i>
            <h3 style="height:50px;"><?= Yii::t('default', 'Tutorials') ?></h3>
            </a>
          </div>
        </div>
      </div>
    </div>
        
    <div class="tiles white col-md-6 m-t-20">
      <div class="tiles-body">
        <h5><span class="semi-bold"></span></h5>
        <div class="row">
          <div class="col-md-12 text-center">
          	<a href="<?= Url::to(['/breeder/index'])?>">
			<i class="fa fa-users fa-5x"></i>
            <h3 style="height:50px;"><?= Yii::t('default', 'Search Breeders') ?></h3>
            </a>
          </div>
        </div>
      </div>
    </div>
        
    <div class="tiles green col-md-6 m-t-20">
      <div class="tiles-body">
        <h5><span class="semi-bold"></span></h5>
        <div class="row">
          <div class="col-md-12 text-center">
           	<a href="<?= Url::to(['/pigeon/pedigree'])?>" target="_blank" style="color:white">
			<i class="fa fa-list-alt fa-5x"></i>
            <h3 style="height:50px;color:white;"><?= Yii::t('default', 'Pedigree') ?></h3>
            </a>
          </div>
        </div>
      </div>
    </div>
    
    <div class="tiles white col-md-12  no-padding">
      <div class="tiles-body">
        <h5><span class="semi-bold">&nbsp;</span></h5>
        <div class="row">
          <div class="col-md-12">
           <div class='embed-container'><iframe width="560" height="315" src="https://www.youtube.com/embed/gMdA5xBnAdE" frameborder="0" allowfullscreen></iframe></div>
        </div>
      </div>
    </div>    
        
    <!--<div class="tiles <?php //$Subscription->lessThanMonthSubs() ?> col-md-12  no-padding m-t-20">
      <div class="tiles-body">
        <h5><span class="semi-bold">
          <?= Yii::t('default','Your Subscription'); ?>
          </span></h5>
        <div class="row">
          <div class="col-md-12">
            <?php //$Subscription->subscriptionInfo(); ?>
          </div>
        </div>
      </div>
    </div>
    <br>-->
    
    <div class="tiles green col-md-12 m-t-20">
      <div class="tiles-body">
        <h5><span class="semi-bold"></span></h5>
        <div class="row">
          <div class="col-md-12 text-center">
           	<a href="https://play.google.com/store/apps/details?id=com.pippion.www.pippion" target="_blank" style="color:white">
			<i class="fa fa-android fa-5x"></i>
            <h3 style="height:50px;">Google Android</h3>
            </a>
          </div>
        </div>
      </div>
    </div>
    
    
    <?php /*<div class="row">
      <div class="col-md-12 no-padding">
        <div class="tiles white">
          <textarea rows="3" class="form-control user-status-box post-input" placeholder="Whats on your mind?"></textarea>
        </div>
        <div class="tiles grey padding-10">
          <div class="pull-left">
            <button class="btn btn-default btn-sm btn-small" type="button"><i class="fa fa-camera"></i></button>
            <button class="btn btn-default btn-sm btn-small" type="button"><i class="fa fa-map-marker"></i></button>
          </div>
          <div class="pull-right">
            <button class="btn btn-primary btn-sm btn-small" type="button">POST</button>
          </div>
          <div class="clearfix"></div>
        </div>
      </div>
    </div>
    <br>
    <br>
    <div class="row">
      <div class="post col-md-12">
        <div class="user-profile-pic-wrapper">
          <div class="user-profile-pic-normal"> <img width="35" height="35" src="assets/img/profiles/c.jpg" data-src="assets/img/profiles/c.jpg" data-src-retina="assets/img/profiles/c2x.jpg" alt=""> </div>
        </div>
        <div class="info-wrapper">
          <div class="username"> <span class="dark-text">John Drake</span> in <span class="dark-text">nervada hotspot</span> </div>
          <div class="info"> Great design concepts by <span class="dark-text">John Smith</span> and his crew! Totally owned the WCG!, Best of luck for your future endeavours, 
            Special thanks for <span class="dark-text">Jane smith</span> for her motivation ;) </div>
          <div class="more-details">
            <ul class="post-links">
              <li><a href="#" class="muted">2 Minutes ago</a></li>
              <li><a href="#" class="text-info">Collapse</a></li>
              <li><a href="#" class="text-info"><i class="fa fa-reply"></i> Reply</a></li>
              <li><a href="#" class="text-warning"><i class="fa fa-star"></i> Favourited</a></li>
              <li><a href="#" class="muted">More</a></li>
            </ul>
          </div>
          <div class="clearfix"></div>
          <ul class="action-bar">
            <li><a href="#" class="muted"><i class="fa fa-comment"></i> 1584</a> Comments</li>
            <li><a href="#" class="text-error"><i class="fa fa-heart"></i> 47k</a> likes</li>
          </ul>
          <div class="clearfix"></div>
          <div class="post comments-section">
            <div class="user-profile-pic-wrapper">
              <div class="user-profile-pic-normal"> <img width="35" height="35" data-src-retina="assets/img/profiles/e2x.jpg" data-src="assets/img/profiles/e.jpg" src="assets/img/profiles/e.jpg" alt=""> </div>
            </div>
            <div class="info-wrapper">
              <div class="username"> <span class="dark-text">Thunderbolt</span> </div>
              <div class="info"> Congrats, <span class="dark-text">John Smith</span> &amp; <span class="dark-text">Jane Smith</span> </div>
              <div class="more-details">
                <ul class="post-links">
                  <li><a href="#" class="muted">2 Minutes ago</a></li>
                  <li><a href="#" class="text-error"><i class="fa fa-heart"></i> Like</a></li>
                  <li><a href="#" class="muted">Details</a></li>
                </ul>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="post comments-section">
            <div class="user-profile-pic-wrapper">
              <div class="user-profile-pic-normal"> <img width="35" height="35" data-src-retina="assets/img/profiles/b2x.jpg" data-src="assets/img/profiles/b.jpg" src="assets/img/profiles/b.jpg" alt=""> </div>
            </div>
            <div class="info-wrapper">
              <div class="username"> <span class="dark-text">Thunderbolt</span> </div>
              <div class="info"> Congrats, <span class="dark-text">John Smith</span> &amp; <span class="dark-text">Jane Smith</span> </div>
              <div class="more-details">
                <ul class="post-links">
                  <li><a href="#" class="muted">2 Minutes ago</a></li>
                  <li><a href="#" class="text-error"><i class="fa fa-heart"></i> Like</a></li>
                  <li><a href="#" class="muted">Details</a></li>
                </ul>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="post comments-section"> </div>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>*/ ?>
  </div>
</div>
