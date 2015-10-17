<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\search\StatusSearch;
use backend\models\Status;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\StatusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('default', 'STATUS_INDEX_TITLE');
$this->params['breadcrumbs'][] = $this->title;
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'STATUS_LINK_INDEX'),  'url' => ['index']];
$menuItems[] = ['label' => Yii::t('default', 'Create'),  'url' => ['create']];

$Status = new Status;
$s = new StatusSearch;
?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>

<div class="row">
    <div class="col-md-12">
      <div class="grid simple">
        <div class="grid-title no-border">
          <h4><?php echo Yii::t('default', 'STATUS_STATUS'); ?></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body no-border"> <br>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">

				<?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
					'tableOptions'=>['class'=>'table table-striped table-hover table-bordered responsive'],
                    'columns' => [
                        //['class' => 'yii\grid\SerialColumn'],
            
                        //'ID',
                        //'IDuser',
                        'status',
						[
                        	'attribute'=>'frompedigree',
							'value'=>function($data){
								return ($data->frompedigree==0) ? Yii::t('default', 'No') : Yii::t('default', 'Yes');
							},
							'filter'=>$Status->dropDownListYesNo(),
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
