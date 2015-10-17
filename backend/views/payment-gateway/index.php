<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\PaymentGateway; 

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('default', 'Payment Gateways');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="payment-gateway-index">
  <div class="payment-gateway-create">
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
              <div class="col-md-12 col-sm-12 col-xs-12">
                <p>
                  <?= Html::a(Yii::t('default', 'Add Payment Gateway'), ['create'], ['class' => 'btn btn-success btn-cons']) ?>
                </p>
				<?= GridView::widget([
					'dataProvider' => $dataProvider,
					'columns' => [
						//['class' => 'yii\grid\SerialColumn'],
			
						//'ID',
						//'IDuser',
						[
							'attribute'=>'gateway',
							'format'=>'html',
							'value'=>function($data)
							{
								return PaymentGateway::returnGatewayPic($data->gateway);
							},
						],
						'pay_email:email',
			
						[
							'class' => 'yii\grid\ActionColumn',
							'template'=>'{update} {delete}',
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
</div>
