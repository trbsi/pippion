<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\helpers\ExtraFunctions;
/* @var $this yii\web\View */
/* @var $model backend\models\PigeonData */

$this->title = Yii::t('default', 'PODACI_GOLUB_CREATE_TITLE');
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Pigeon Datas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$ExtraFunctions = new ExtraFunctions;

$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'PODACI_GOLUB_LINK_ADMIN'),  'url' => ['index']];
$menuItems[] = ['label' => Yii::t('default', 'PODACI_GOLUB_LINK_CREATE'),  'url' => ['create']];
$menuItems[] = ['label' => Yii::t('default', 'PODACI_GOLUB_LINK_UPDATE'),  'url' => ['update', 'id' => $model->ID]];
$menuItems[] = ['label' => Yii::t('default', 'PODACI_GOLUB_LINK_DELETE'),  'url' => ['delete', 'id' => $model->ID],
					'linkOptions'=>
					[
						'data' => [
							'confirm' => Yii::t('default', 'Are you sure you want to delete this item?'),
							'method' => 'post',
						],
					]
				];
?>

<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4><?php echo Yii::t('default', 'PODACI_GOLUB_VIEW_H1'); ?> - <?php echo $model->relationIDpigeon->pigeonnumber; ?></h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12"> 
              <?= DetailView::widget([
				'model' => $model,
				'options'=>['class'=>'table table-hover table-striped responsive'],
				'attributes' => [
					//'ID',
					//'IDuser',
					[ 
						'attribute'=>'IDpigeon',
						'value'=>$model->relationIDpigeon->pigeonnumber,
					],
					'pigeondata:ntext',
					'year',
					[
						'attribute'=>'date_created',
						'value'=>$ExtraFunctions->formatDate($model->date_created),
					],
				],
			]) ?>

          
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
