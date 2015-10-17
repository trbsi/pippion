<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\search\BreederResultsSearch;
use backend\models\BreederResults;
use backend\helpers\ExtraFunctions;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\BreederResultsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('default', 'UZGAJIVAC_REZ_ADMIN_TITLE');
$this->params['breadcrumbs'][] = $this->title;
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'UZGAJIVAC_REZ_LINK_ADMIN'),  'url' => ['index']];
$menuItems[] = ['label' => Yii::t('default', 'UZGAJIVAC_REZ_LINK_CREATE'),  'url' => ['create']];
	/*array('label'=>Yii::t('default', 'UZGAJIVAC_REZ_LINK_CREATE'), 'url'=>array('create')),
	array('label'=>Yii::t('default', 'UZGAJIVAC_REZ_LINK_VIEW'), 'url'=>array('view', 'id'=>$model->ID)),
	array('label'=>Yii::t('default', 'UZGAJIVAC_REZ_LINK_ADMIN'), 'url'=>array('admin')),
	array('label'=>, 'url'=>array('update', 'id'=>$model->ID)),*/
$BreederResults=new BreederResults;
?>

<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>


<div class="row">
    <div class="col-md-12">
      <div class="grid simple">
        <div class="grid-title no-border">
          <h4><?php echo Yii::t('default', 'UZGAJIVAC_REZ_ADMIN_H1'); ?></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body no-border"> <br>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
				<div class="table-responsive">
				<?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
					'tableOptions'=>['class'=>'table table-striped table-hover table-bordered',],
                    'columns' => [
                       // ['class' => 'yii\grid\SerialColumn'],
            
                        //'ID',
                        //'IDuser',
                        'breeder_result:ntext',
                        'year',
						[
                        	'attribute'=>'date_created',
							'value'=>function($data){
								$ExtraFunctions = new ExtraFunctions;
								return $ExtraFunctions->formatDate($data->date_created);
							},
							'filter'=>Html::activeTextInput($searchModel, 'date_created', ['class'=>'js-datepicker']),
						],
            
                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
                </div>
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
