<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\CoupleRacing */
if($_MODEL_CHOOSE=="CoupleRacing")
{
	$this->title=Yii::t('default', 'PAR_NATJEC_VIEW_TITLE');
}
else
{
	$this->title=Yii::t('default', 'PAR_UZGOJNI_VIEW_TITLE');
}
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Couple Racings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'PAR_NATJEC_LINK_UPDATE'),  'url' => ['update', 'id' => $model->ID]];
$menuItems[] = ['label' => Yii::t('default', 'PAR_NATJEC_LINK_DELETE'),  'url' => ['delete', 'id' => $model->ID],
					'linkOptions'=>
					[
						'data' =>
						 [
							'confirm' => Yii::t('default', 'Are you sure you want to delete this item?'),
							'method' => 'post',
						],
					]
				];
$menuItems[] = ['label' => Yii::t('default', 'PAR_NATJEC_LINK_CREATE'),  'url' => ['create']];
$menuItems[] = ['label' => Yii::t('default', 'PAR_NATJEC_LINK_ADMIN'),  'url' => ['index']];
?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4><?php echo Yii::t('default', 'PAR_NATJEC_VIEW_H1'); ?>  <?php echo $model->couplenumber; ?></h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12"> 
			  <?= DetailView::widget([
				'model' => $model,
				'options'=>['class'=>'table table-striped'],
				'attributes' => [
					//'ID',
					//'IDuser',
					[
						'attribute'=>'male',
						'value'=>$model->relationMale->pigeonnumber
					],
					[
						'attribute'=>'female',
						'value'=>$model->relationFemale->pigeonnumber
					],
					'couplenumber',
					'year',
				],
			]) ?>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

