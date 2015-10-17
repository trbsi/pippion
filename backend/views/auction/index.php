<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use backend\models\Auction;
use backend\models\BreederImage;
use backend\helpers\ExtraFunctions;

$ExtraFunctions = new ExtraFunctions;
$Auction = new Auction;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AuctionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('default', 'My auctions');
$this->params['breadcrumbs'][] = $this->title;
?>

<script>
function showSearch()
{
	$("#div-search").toggle();
}
</script>
<?php
if(Yii::$app->controller->action->id=="index")
{
	$h4=Yii::t('default', 'Search your auctions');
	$visible_owner=true;
}
else
{
	$h4=Yii::t('default', 'Search all auctions');
	$visible_owner=false;
}
?>

<div class="current_time_fixed"> 
    <strong><?= Yii::t('default', 'Time') ?> (UTC):</strong> <br />
    <div class="showUTCTime" style="display:inline-block"><script>startTime()</script></div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4><?php echo $h4; ?></h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12"> <a href="javascript:;" class="btn btn-success btn-cons btn-block btn-small" onclick="showSearch()"><strong>
            <?= strtoupper(Yii::t('default','Search')) ?>
            </strong></a>
            <div id="div-search" style="display:none; background:#EDEDED; padding:10px; border:1px solid #CCC; border-radius:5px;">
              <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
            </div>
            <style>
			.auction-detail
			{
				width:150px;
				float:left;
				height:25px;
			}
			.auction-detail-wrap
			{
				padding:4px 0;
			}
			.auction-table-min-width
			{
				min-width:600px;
			}
			</style>
            
            <div class="well"> 
            	<strong><?= Yii::t('default', 'Time') ?> (UTC):</strong> 
                <div class="showUTCTime" style="display:inline-block"><script>startTime()</script></div>
            </div>
            
            <div style="padding-bottom:70px;">
				<h4><strong><?= Yii::t('default', 'Sort');?></strong></h4>
                <div style="clear:both"></div>
                <?= $Auction->sortAuctions();?> 
            </div>
            <div class="table-responsive">
              <?= GridView::widget([
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'tableOptions'=>['class'=>'table table-bordered table-striped auction-table-min-width'],
				'columns' => [
					//['class' => 'yii\grid\SerialColumn'],
					[
						'label'=>Yii::t('default', 'Image'),
						'format'=>'raw',
						'value'=>function($data)
						{
							if(!empty($data->title))
								$titleOrUsername=$data->title;
							else
								$titleOrUsername=$data->relationIDuser->username;
									
							$alt=Auction::auctionTitle($data->relationIDpigeon->relationPigeonIDcountry->country, $data->relationIDpigeon->sex, $data->relationIDpigeon->pigeonnumber, $titleOrUsername);
							$image_url=Auction::UPLOAD_DIR_IMAGES.$data->auctionImage($data);
							
							return '<a href="'.$image_url.'" class="group1_colorbox"><img src="'.$image_url.'" class="img-responsive img-thumbnail img-auction-search" alt="'.$alt.'"></a>';
						}
					],
					[
						'label'=>Yii::t('default', 'Information'),
						'format'=>'raw',
						'value'=>function($data) use ($ExtraFunctions, $Auction)
						{
							if(empty($data->title))
								$title=$data->relationIDuser->username;
							else
								$title=$data->title;
							
							$fulltitle=Html::encode(Auction::auctionTitle($data->relationIDpigeon->relationPigeonIDcountry->country, $data->relationIDpigeon->sex, $data->relationIDpigeon->pigeonnumber, $title));
								
							//now return content
							$return=NULL;
							//$return.=BreederImage::findUserProfilePicture($data->IDuser, ['img_class'=>'img-circle pull-left m-r-20', 'img_style'=>'width:69px; height:69px;']);

							$return.='<a href="'.Url::to(['view', 'id'=>$data->ID]).'"><h4>'.$fulltitle.'</h4></a>';
							
							$return.='<div style="clear:both;"></div>';
						
							$return.='<a href="'.Url::to(['view', 'id'=>$data->ID]).'">';
							$return.='<h4 style="font-weight:bold;">'.Auction::latestPrice($data).'</h4>';

							$return.='<div class="auction-detail-wrap"><div class="auction-detail"><strong>'.Yii::t('default', 'Auctioneer').': </strong></div><span class="label">'.$data->relationIDuser->username.'</span></div>';
							
							$return.='<div class="auction-detail-wrap"><div class="auction-detail"><strong>'.Yii::t('default', 'Start Time').': </strong></div>'.ExtraFunctions::formatDate($data->start_time).' (UTC)</div>';
							
							$return.='<div class="auction-detail-wrap"><div class="auction-detail"><strong>'.Yii::t('default', 'End Time').': </strong></div>'.ExtraFunctions::formatDate($data->end_time).' (UTC)</div>';
							
							$return.='<div class="auction-detail-wrap"><div class="auction-detail"><strong>'.Yii::t('default', 'Starting bid').': </strong></div>'.$data->start_price.' '.$data->relationIDcurrency->currency.'</div>';
														
							$return.='</a>';
							
							$twittertext=urlencode($fulltitle.' '.ExtraFunctions::pippionFullUrl().Url::to(['/auction/view','id'=>$data->ID]));
							$return.=ExtraFunctions::shareAnythingButtons(Url::to(['/auction/view','id'=>$data->ID]), ['twitter'=>$twittertext]);
								
							return $return;
						}
					],
					/*//'ID',
					'IDuser',
					//'IDpigeon',
					'title',
					'start_time',
					'end_time',
					// 'information:ntext',
					'country',
					'city',
					// 'IDbreeder',
					'start_price',
					//'min_bid',
					'currency',*/
		
					[
						'class' => 'yii\grid\ActionColumn',
						'visible'=>$visible_owner
					],
				],
			]); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
