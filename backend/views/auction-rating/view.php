<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\helpers\LinkGenerator;
use backend\models\AuctionRating;

$LinkGenerator = new LinkGenerator;
$AuctionRating = new AuctionRating;
/* @var $this yii\web\View */
/* @var $model backend\models\AuctionRating */

$this->title = Yii::t('default', 'Auction rating').' | '.$model->relationIDseller->username.' <-> '.$model->relationIDwinner->username;;
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Auction Ratings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'View auction'),  'url' => ['/auction/view', 'id' => $model->IDauction]];
?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4><?php echo Yii::t('default', 'Rating').' '.$model->relationIDseller->username.' &#8596; '.$model->relationIDwinner->username; ?></h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="table-responsive">
            <?= DetailView::widget([
				'model' => $model,
				'options'=>['class'=>'table table-hover table-striped '],
				'attributes' => [
					[
						'attribute'=>'IDauction',
						'format'=>'raw',
						'value'=>$LinkGenerator->auctionLink(Yii::t('default', 'See auction'), $model->IDauction, ['class'=>'label label-success']),
					],
					[
						'attribute'=>'winner_rating',
						'format'=>'raw',
						'value'=>$AuctionRating->ratingStars($model->winner_rating)." ".
						$LinkGenerator->breederLink($model->relationIDwinner->username, $model->IDwinner)."<br><br>".
						nl2br(Html::encode($model->winner_feedback)),
					],
					[
						'attribute'=>'seller_rating',
						'format'=>'raw',
						'value'=>$AuctionRating->ratingStars($model->seller_rating)." ".
						$LinkGenerator->breederLink($model->relationIDseller->username, $model->IDseller)."<br><br>".
						nl2br(Html::encode($model->seller_feedback)),
					],
				],
			]) ?>
    		</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
