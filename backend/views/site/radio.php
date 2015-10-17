<?php
/* @var $this yii\web\View */
use yii\helpers\Url;
$this->title = 'Pippion';
?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=375971292568983&version=v2.3";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

function openRadio()
{
	window.open("http://www.3nrgfm.com/player/", 'Radio','height=505,width=564');
}
</script>

<div class="row "> 
  <!-- BEGIN BLOG POST SIMPLE-->
  <div class="col-md-4 m-b-10">
    <div class="widget-item ">
      <div class="controller overlay right"> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      <div class="tiles green " style="max-height:345px">
        <div class="tiles-body">
          <h3 class="text-white m-t-50 m-b-30 m-r-20"><span class="semi-bold"><?= Yii::t('default', 'Pigeon Radio') ?></span> <?= Yii::t('default', '3NRG') ?></h3>
          <div class="overlayer bottom-right fullwidth">
            <div class="overlayer-wrapper">
              <div class=" p-l-20 p-r-20 p-b-20 p-t-20">
                <div class="clearfix"></div>
              </div>
            </div>
          </div>
          <br>
        </div>
      </div>
      <div class="tiles white ">
        <div class="tiles-body">
          <div class="row">
            <div class="p-l-15 p-t-10 p-r-20">
              <p><?= Yii::t('default', 'Every Tuesday from 4PM to 6PM') ?></p>
              <p><?php include "radio_clock.php" ?><strong></strong></p>
              <p><a href="javascript:;" onclick="openRadio()" ><img src="<?= Yii::getAlias('@web')?>/images/play.png" class="img-reponsive center-block" style="max-height:70px" /></a></p>
              <div class="post p-t-10 p-b-10">
                <ul class="action-bar no-margin p-b-20 ">
                  <li><div class="fb-share-button" data-href="http://www.pippion.com" data-layout="button_count"></div></li>
                </ul>
                <div class="clearfix"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- START --> 
  <div class="col-md-4 m-b-10">
    <div class="widget-item ">
      <div class="controller overlay right"> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      <div class="tiles blue " style="max-height:345px">
        <div class="tiles-body">
          <h3 class="text-white m-t-50 m-b-30 m-r-20"><span class="semi-bold"><?= Yii::t('default', 'Pigeon radio') ?></span> <?= Yii::t('default', 'audio archives') ?></h3>
          <div class="overlayer bottom-right fullwidth">
            <div class="overlayer-wrapper">
              <div class=" p-l-20 p-r-20 p-b-20 p-t-20">
                <div class="clearfix"></div>
              </div>
            </div>
          </div>
          <br>
        </div>
      </div>
      <div class="tiles white ">
        <div class="tiles-body">
          <div class="row">
            <div class="p-l-15 p-t-10 p-r-20">
              <p><a href="http://pigeonmedia.com.au/archives/" target="_blank" class="btn btn-success btn-cons btn-block"><?= Yii::t('default', 'Missed the show?') ?></a></p>
              
              <div class="post p-t-10 p-b-10">
                <ul class="action-bar no-margin p-b-20 ">
                  <li><div class="fb-share-button" data-href="http://www.pippion.com" data-layout="button_count"></div></li>
                </ul>
                <div class="clearfix"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- END -->
  <!-- START -->
  <div class="col-md-4 m-b-10">
    <div class="widget-item ">
      <div class="controller overlay right"> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      <div class="tiles red " style="max-height:345px">
        <div class="tiles-body">
          <h3 class="text-white m-t-50 m-b-30 m-r-20"><span class="semi-bold"><?= Yii::t('default', 'Listen via') ?></span> </h3>
          <div class="overlayer bottom-right fullwidth">
            <div class="overlayer-wrapper">
              <div class=" p-l-20 p-r-20 p-b-20 p-t-20">
                <div class="clearfix"></div>
              </div>
            </div>
          </div>
          <br>
        </div>
      </div>
      <div class="tiles white ">
        <div class="tiles-body">
          <div class="row">
            <div class="p-l-15 p-t-10 p-r-20">
              <p class="text-center">
              	<a href="http://tunein.3nrgfm.com/winamp.pls" target="_blank"><img src="<?= Yii::getAlias('@web')?>/images/winamp.png" style="max-height:50px" /></a>
              	<a href="http://tunein.3nrgfm.com/wmp.asx" target="_blank"><img src="<?= Yii::getAlias('@web')?>/images/wmp.png" style="max-height:50px" /></a>
                <a href="http://tunein.3nrgfm.com/winamp.pls" target="_blank"><img src="<?= Yii::getAlias('@web')?>/images/itunes.png" style="max-height:50px" /></a>
                <a href="https://www.youtube.com/channel/UCOUtlQXCIcp9zE93urkhVHA/videos" target="_blank"><img src="<?= Yii::getAlias('@web')?>/images/youtube.png" style="max-height:50px" /></a>
              </p>
              <div class="post p-t-10 p-b-10">
                <ul class="action-bar no-margin p-b-20 ">
                  <li><div class="fb-share-button" data-href="http://www.pippion.com" data-layout="button_count"></div></li>
                </ul>
                <div class="clearfix"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- END -->
</div>
