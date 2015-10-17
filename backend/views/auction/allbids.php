<?php
/* @var $this AuctionController */
/* @var $model AuctionBid */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use backend\models\Auction;
use backend\helpers\LinkGenerator;

$LinkGenerator = new LinkGenerator;

$this->title=Yii::t('default', 'View all bids');
?>
<?php
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'View auction'),  'url' => ['view', 'id' => $IDauction]];
?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple ">
      <div class="grid-title no-border">
        <h4><?php echo Yii::t('default', 'View all bids') ?></h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border">
        <div class="table-responsive">
		  <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'columns' => [
                [
                    //'attribute'=>'IDbidder',
					'label'=>Yii::t('default', 'Bidder'),
					'format'=>'raw',
                    'value'=>function($data) use ($LinkGenerator)
                    {
                        return $LinkGenerator->breederLink($data->relationIDbidder->username, $data->IDbidder);
                    },
                    //'filter'=>false,
                ],
                [
                    //'attribute'=>'price',
                    'label'=>Yii::t('default', 'Price'),
                    'value'=>function($data)
                    {
                        return $data->price." ".$data->relationIDauction->relationIDcurrency->currency;
                    },
                ],
                //['class' => 'yii\grid\SerialColumn'],
                //['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
        </div>
      </div>
    </div>
  </div>
</div>
