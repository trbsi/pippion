<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Status */

$this->title = Yii::t('default', 'STATUS_VIEW_TITLE');
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'STATUS_LINK_INDEX'),  'url' => ['index']];
$menuItems[] = ['label' => Yii::t('default', 'Update'),  'url' => ['update', 'id' => $model->ID]];
$menuItems[] = ['label' => Yii::t('default', 'Delete'),  'url' => ['delete', 'id' => $model->ID], 'linkOptions'=>
					[
						'data' => 
						[
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
          <h4><?php echo Yii::t('default', 'STATUS_VIEW_STATUS'); ?></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body no-border"> <br>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">

				<?= DetailView::widget([
                    'model' => $model,
					'options' => ['class'=>'table table-striped table-hover responsive'],
                    'attributes' => [
                        //'ID',
                        //'IDuser',
                        'status',
                        [
                            'attribute'=>'frompedigree',
                            'value'=>call_user_func(function($model) {
                                    return $model->frompedigree==0 ? Yii::t('default', 'No') : Yii::t('default', 'Yes');
                                }, $model)
                        ]
                    ],
                ]) ?>
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
