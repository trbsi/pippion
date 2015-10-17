<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\helpers\ExtraFunctions;

/* @var $this yii\web\View */
/* @var $model backend\models\BreederResults */

$this->title = Yii::t('default', 'UZGAJIVAC_REZ_VIEW_TITLE');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Breeder Results'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$menuItems=[];
$menuItems[] = ['label' => Yii::t('default', 'UZGAJIVAC_REZ_LINK_ADMIN'),  'url' => ['index']];
$menuItems[] = ['label' => Yii::t('default', 'UZGAJIVAC_REZ_LINK_CREATE'),  'url' => ['create']];
$menuItems[] = ['label' => Yii::t('default', 'Update'),  'url' => ['update', 'id' => $model->ID]];
$menuItems[] = ['label' => Yii::t('default', 'Delete'),  'url' => ['delete', 'id' => $model->ID], 
				'linkOptions'=>
				[
					'data' => 
					[
						'confirm' => Yii::t('default', 'Are you sure you want to delete this item?'),
						'method' => 'post',
					]
				] 
			];

$ExtraFunctions = new ExtraFunctions;
?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>

<div class="row">
    <div class="col-md-12">
      <div class="grid simple">
        <div class="grid-title no-border">
          <h4><?php echo Yii::t('default', 'UZGAJIVAC_REZ_VIEW_H1'); ?></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body no-border"> <br>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">

				<?= DetailView::widget([
                    'model' => $model,
					'options'=>['class'=>'table table-striped table-hover responsive'],
                    'attributes' => [
                       // 'ID',
                        //'IDuser',
                        'breeder_result:ntext',
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

