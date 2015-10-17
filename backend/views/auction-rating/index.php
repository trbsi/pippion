<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use backend\helpers\LinkGenerator;
use backend\models\AuctionRating;
use backend\models\Auction;


$LinkGenerator = new LinkGenerator;
$AuctionRating = new AuctionRating;
$Auction = new Auction;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AuctionRatingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
.scrollbar-inner 
{
	max-width:400px;
	max-height:300px;
	margin-top:10px;
}

</style>
<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4><?= $title ?></h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
        <div class="row">
          <?php if($visible_owner==true): ?>
          <div class="col-md-12 col-sm-12 col-xs-12">
          	<div class="alert alert-info"><?= Yii::t('default', 'Show buttons info'); ?></div>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
          <a href="<?= Url::to(['/auction-rating/index', 'rating_show_only'=>'both']); ?>" class="btn <?= ($_SESSION["rating_show_only"]=="both")?'btn-success':'btn-info'?> btn-block btn-small"><?= Yii::t('default', 'Show me as winner and seller'); ?></a>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
          <a href="<?= Url::to(['/auction-rating/index', 'rating_show_only'=>'winner']); ?>" class="btn  <?= ($_SESSION["rating_show_only"]=="winner")?'btn-success':'btn-info'?> btn-block btn-small"><?= Yii::t('default', 'Show me as winner'); ?></a>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
          <a href="<?= Url::to(['/auction-rating/index', 'rating_show_only'=>'seller']); ?>" class="btn  <?= ($_SESSION["rating_show_only"]=="seller")?'btn-success':'btn-info'?> btn-block btn-small"><?= Yii::t('default', 'Show me as seller'); ?></a>
          </div>
          <div>&nbsp;</div>
          <?php endif; ?>

          <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="table-responsive">
			<?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],
        
                    //'ID',
					[
						'label'=>Yii::t('default', 'Auction'),
						'format'=>'raw',
						'value'=>function($data) use ($LinkGenerator , $Auction)
						{
							$return=NULL;
							$return.=$LinkGenerator->auctionLink(Yii::t('default', 'See auction'), $data->IDauction, ['class'=>'label label-success']);
							if(!empty($data->relationIDauction->title))
								$titleOrUsername=$data->relationIDauction->title;
							else
								$titleOrUsername=$data->relationIDauction->relationIDuser->username;
							
							$return.='<a href="'.Url::to(['/auction/view', 'id'=>$data->IDauction]).'" target="_blank" style="white-space:normal">
									  <div style="margin-top:10px;">';
							$return.=Html::encode($Auction->auctionTitle
								($data->relationIDauction->relationIDpigeon->relationPigeonIDcountry->country, 
								$data->relationIDauction->relationIDpigeon->sex, 
								$data->relationIDauction->relationIDpigeon->pigeonnumber, 
								$titleOrUsername));
							$return.='<br><img src="'.Auction::UPLOAD_DIR_IMAGES.$Auction->auctionImage($data->relationIDauction).'" class="img-responsive img-thumbnail img-auction-search" style="margin-top:10px;">';
							$return.='</div>
									  </a>';
							return $return;
						}
					],

					[
						'label'=>Yii::t('default', 'Buyer'),
						'format'=>'raw',
						'value'=>function($data) use ($AuctionRating, $LinkGenerator)
						{
							$return=NULL;
							$return.=$AuctionRating->ratingStars($data->winner_rating);
							$return.=" ".$LinkGenerator->breederLink($data->relationIDwinner->username, $data->IDwinner);
							$return.="<br>";
							$return.='<div class="scrollbar-inner">'.$data->relationIDseller->username.': <i>"'.nl2br(Html::encode($data->winner_feedback)).'"</i></div>';
							return $return;
						}
					],
					[
						'label'=>Yii::t('default', 'Seller'),
						'format'=>'raw',
						'value'=>function($data) use ($AuctionRating, $LinkGenerator)
						{
							$return=NULL;
							$return.=$AuctionRating->ratingStars($data->seller_rating);
							$return.=" ".$LinkGenerator->breederLink($data->relationIDseller->username, $data->IDseller);
							$return.="<br>";
							$return.='<div class="scrollbar-inner">'.$data->relationIDwinner->username.': <i>"'.nl2br(Html::encode($data->seller_feedback)).'"</i></div>';
							return $return;
						}

					],
        			
        			
       				
       				/*Yii::t('default', 'Winner rating')
        			Yii::t('default', 'Seller rating')
        			Yii::t('default', 'Winner Feedback')
        			Yii::t('default', 'Seller Feedback')*/
                    /*'IDauction',
                    'winner_rating',
                    'seller_rating',
                    'winner_feedback:ntext',*/
                    // 'seller_feedback:ntext',
                    // 'IDwinner',
                    // 'IDseller',
        
                    [
						'class' => 'yii\grid\ActionColumn',
						'buttons'=>
						[
							'update' => function ($url, $model, $key) 
							{
								$url=Url::to(['update', 'id'=>$model->IDauction]);
								return  Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
							},
						],
						'visible'=>$visible_owner,
						'template'=>'{update}',
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
