<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use backend\helpers\LinkGenerator;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('default', 'Transfer money to seller');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="account-balance-index">
  <div class="row">
    <div class="col-md-12">
      <div class="grid simple">
        <div class="grid-title no-border">
          <h4>
            <?= Html::encode($this->title) ?>
          </h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body no-border"> <br>
          <div class="row">
            <div class="col-md-12"> 
            
				<?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                       // ['class' => 'yii\grid\SerialColumn'],
						[
							'attribute'=>'IDuser',
							'value'=>function($data)
							{
								return $data->relationIDuser->username;
							}
						],
						[
							'attribute'=>'IDauction',
							'format'=>'raw',
							'value'=>function($data)
							{
								return LinkGenerator::auctionLink("Auction", $data->IDauction, $options=['class'=>'label label-success']);
							}
						],
                        'money_transferred',
						'pigeon_arrived',
						'txn_id',
						[
							'label'=>'Transfer money',
							'format'=>'raw',
							'value'=>function($data)
							{
								return '<a href="'.Url::to(['transfer-money-to-seller', 'id'=>$data->IDauction]).'" class="label label-success" target="_blank">Transfer</a>';
							},
						],
                       // ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
            
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
