<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\RacingTableCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('default', 'Search all your racing categories');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'Manage RacingTableCategory'),  'url' => ['index']];
$menuItems[] = ['label' => Yii::t('default', 'Create RacingTableCategory'),  'url' => ['create']];
?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>
<?= $this->render('/_alert'); ?>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4><?php echo Yii::t('default', 'Manage Racing Table Categories'); ?></h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <?= GridView::widget([
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'columns' => [
					//['class' => 'yii\grid\SerialColumn'],
		
					//'ID',
					'category',
					//'IDuser',
		
					[
						'class' => 'yii\grid\ActionColumn',
						'template' =>"{update} {delete}",
					],
				],
			]); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
