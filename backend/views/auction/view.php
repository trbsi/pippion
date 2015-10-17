<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use backend\models\Auction;
use backend\models\BreederImage;
use backend\helpers\ExtraFunctions;
use backend\helpers\LinkGenerator;

$ExtraFunctions = new ExtraFunctions;
$LinkGenerator = new LinkGenerator;
/* @var $this yii\web\View */
/* @var $model backend\models\Auction 
$auction -> Auction model (this auction)
$images -> AuctionImage model (all images of this auction)
$title -> auction title, generated from Auction::auctionTitle()

*/

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Auctions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

//register meta tags for facebook
$this->registerMetaTag(['name' => 'og:title', 'content' => $title]);
$this->registerMetaTag(['name' => 'og:image', 'content' => ExtraFunctions::pippionFullUrl().Auction::UPLOAD_DIR_IMAGES.Auction::auctionImage($auction->ID)]);

?>
<?php
$menuItems = [];
if($auction->IDuser==Yii::$app->user->getId())
{

$menuItems[] = ['label' => Yii::t('default', 'Update auction'),  'url' => ['update', 'id' => $auction->ID]];
$menuItems[] = ['label' => Yii::t('default', 'Delete'),  'url' => ['delete', 'id' => $auction->ID], 
					'linkOptions'=>
					[
						'data' => 
						[
							'confirm' => Yii::t('default', 'Are you sure you want to delete this item?'),
							'method' => 'post',
						],
					]
				];
}				

$menuItems[] = ['label' => Yii::t('default', 'Create auction'),  'url' => ['create']];
$menuItems[] = ['label' => Yii::t('default', 'My auctions'),  'url' => ['index']];
$menuItems[] = ['label' => Yii::t('default', 'All auctions'),  'url' => ['opened']];

$AuctionImage=$auction->auctionImage($auction);
?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>
<?= $this->render('/_alert'); ?>

<script>

$(document).ready(function()
	{
		var end = new Date(<?php echo strtotime($auction->end_time)*1000; ?>);
		$(".countdownContainer").countdown(
		{
			until: end, 
			format:"D H M S",
			serverSync:serverTime,
		}); 
	}
);

function serverTime() 
{ 
    var time = null; 
    $.ajax({
		url: '<?= Url::to(['/site/server-time']) ?>', 
        async: false, 
		dataType: 'text', 
        success: function(text) 
		{
            time = new Date(text); 
        }, 
		error: function(http, message, exc) 
		{ 
            time = new Date(); 
    }}); 
    return time; 
}
</script>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple ">
      <div class="grid-title no-border">
        <h4>
          <?php  echo $title ?>
        </h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border">
        <h3>&nbsp;</h3>
        <div class="row row-margin-bottom">
          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"> 
          <a class="group1_colorbox" href="<?= Yii::getAlias('@web').Auction::UPLOAD_DIR_IMAGES.$AuctionImage ?>"> 
          	<img src="<?= Yii::getAlias('@web').Auction::UPLOAD_DIR_IMAGES.$AuctionImage ?>"  class="img-responsive img-thumbnail center-block" alt="<?= $title ?>"> 
            </a> <br>
            <?= $auction->otherAuctionImages($auction, $title); ?>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <?php
            $twittertext=urlencode($title.' '.ExtraFunctions::pippionFullUrl().Url::to(['/auction/view','id'=>$auction->ID]));
            echo ExtraFunctions::shareAnythingButtons(Url::to(['/auction/view','id'=>$auction->ID]), ['twitter'=>$twittertext]);
            ?>
            <div class="grid simple vertical green">
              <div class="grid-body">
                <?= BreederImage::findUserProfilePicture($auction->IDuser, ['img_class'=>'img-circle pull-left m-r-20', 'img_style'=>'width:69px; height:69px;']); ?>
                <strong>


                <?= Yii::t('default', 'Country') ?>
                :</strong>
                <?= $auction->relationRealIDcountry->country_name; ?>
                <br />
                <strong>
                <?= Yii::t('default', 'City') ?>
                :</strong>
                <?= $auction->city.''; ?>
              </div>
            </div>
            <div class="grid simple vertical green">
              <div class="grid-body">
                <?php echo $auction->sellerRating($auction->IDuser) ?>
              </div>
            </div>
          </div>
        </div>
        <div class="row row-margin-bottom">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="grid simple vertical green">
              <div class="grid-body">
                <p>
                  <?php echo $auction->bidForm($auction)?>
                </p>
                <div style="clear:both"></div>
                <p> 
                	<div class="col-md-4 m-b-10">
					<?php echo
                        '<strong>'.Yii::t('default', 'Starting bid').': </strong>'.$auction->start_price.' '.$auction->relationIDcurrency->currency.' ('.$auction->relationIDcurrency->country.') | '.
                        '<strong>'.Yii::t('default', 'Breeder').':</strong> '.$LinkGenerator->breederLink($auction->relationIDbreeder->username, $auction->IDbreeder);
                    ?>
                	</div>
                    
                    <div class="col-md-4 m-b-10">
                    <?php
                    echo
                    '<strong>'.Yii::t('default', 'Current bid').':</strong><br>'.$auction->lastBidder($auction).' | '.
                    $LinkGenerator->auctionAllBidsLink(Yii::t('default', 'all bids'),$auction->ID, ['class'=>'label label-inverse'] );
                    ?> 
                    </div>
                    
                    <div class="col-md-4 m-b-10">
                    <?php echo Auction::takePercentage($auction); ?>
                    </div>
                  </p>
                
              </div>
            </div>
          </div>
        </div>
        <div class="table-responsive ">
          <table class="table table-bordered ">
            <thead>
              <tr>
                <th><?= Yii::t('default', 'Time') ?></th>
                <th><?= Yii::t('default', 'Start') ?></th>
                <th><?= Yii::t('default', 'End') ?></th>
                <th><?= Yii::t('default', 'Time left') ?></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><div class="showUTCTime" ><script>startTime()</script></div>
                  UTC timezone</td>
                <td><?= $ExtraFunctions->formatDate($auction->start_time) ?>
                  <br>
                  UTC timezone</td>
                <td><?= $ExtraFunctions->formatDate($auction->end_time) ?>
                  <br>
                  UTC timezone</td>
                <td><div class="countdownContainer"></div></td>
              </tr>
            </tbody>
          </table>
        </div>
        <?php echo $auction->pigeonInfo($auction) ?>
        <?php
		if(!empty($auction->information))
		{
			echo '<h3 style="border-bottom:1px dotted #686868"><strong>'.Yii::t('default', 'Information').'</strong></h3>';
			echo '<div style="color:black">'.nl2br($auction->information).'</div>';
		}
		?>
      </div>
    </div>
  </div>
</div>
