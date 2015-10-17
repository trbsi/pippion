<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use backend\models\AccountBalance;
use backend\models\Currency;
use backend\helpers\LinkGenerator;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('default', 'Account balance');
?>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4>
          <?= Yii::t('default', 'Account balance') ?>
        </h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border">
        <div class="row">
          <div class="col-md-4 col-vlg-3 col-sm-6">
            <div class="tiles blue m-b-10">
              <div class="tiles-body">
                <div class="controller"> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
                <div class="tiles-title" style="text-transform:uppercase">
                  <?= Yii::t('default', 'Current balance') ?>
                </div>
                <?php AccountBalance::totalBalance(); ?>
              </div>
            </div>
          </div>
          <div class="col-md-8 col-vlg-9 col-sm-6"> 
          	<div class="alert alert-warning"><?= Yii::t('default', 'Account balance warning')?></div>
          </div>
          <div class="col-md-12 col-sm-12 col-xs-12">
          <br />
          <strong><?= Yii::t('default', 'Total price - Pippion commission') ?></strong>
          <br />
            <div class="table-responsive">
              <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                       // ['class' => 'yii\grid\SerialColumn'],
						[
							'attribute'=>'IDauction',
							'format'=>'raw',
							'value'=>function($data)
							{
								return LinkGenerator::auctionLink("Auction", $data->IDauction, $options=['class'=>'label label-success']);
							}
						],
						[
							'attribute'=>'money_transferred',
							'format'=>'boolean',
						],
						/*[
							'attribute'=>'pigeon_arrived',
							'format'=>'boolean',
						],*/
						'txn_id',
						[
							'attribute'=>'current_balance',
							'label'=>Yii::t('default', 'Account balance'),
							'format'=>'html',
							'value'=>function($data)
							{
								return AccountBalance::currentBalance($data->IDauction, "full");
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
