<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use backend\models\RacingTable;
use backend\models\RacingTableCategory;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\RacingTableSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$RacingTable = new RacingTable;
$RacingTableCategory = new RacingTableCategory;

$this->title = Yii::t('default', 'Search for pigeons and categories');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'Create RacingTable'),  'url' => ['create']];
?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>


<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4><?php echo Yii::t('default', 'Manage Racing Tables'); ?></h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12"> 
          	<div class="table-responsive">
                <div class="alert alert-warning">
                    <?php echo Yii::t('default', 'Objasnjenje za stupce'); ?>
                </div>
                  <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'tableOptions'=>['class'=>'table table-hover table-bordered table-striped'],
                        'columns' => [
                           // ['class' => 'yii\grid\SerialColumn'],
                
                            [
                                'attribute'=>'IDcategory',
                                'format'=>'raw',
                                'value'=>function($data)
                                {
                                    return Html::a($data->relationIDcategory->category, ["view", "cid"=>$data->IDcategory, "target"=>"category"]);
                                },
								'filter'=>$RacingTableCategory->dropDownListCategory()
                            ],
                            [
                                'attribute'=>'search_pigeon',
								'label'=>Yii::t('default', 'Idgolub'),
                                'format'=>'raw',
                                'value'=>function($data)
                                {
                                    return Html::a($data->relationIDpigeon->pigeonnumber, ["view", "pid"=>$data->IDpigeon, "target"=>"pigeon"]);
                                }
    
                            ],
                            [
                                'label'=>Yii::t('default', 'Category+pigeon'),
                                'format'=>'raw',
                                'value'=>function($data)
                                {
                                    return Html::a(Yii::t("default", "See details"), ["view", "cid"=>$data->IDcategory, "pid"=>$data->IDpigeon, "target"=>"both"]);
                                }
                            ],
							
                            //'ID',
                           /* 'racing_date',
                            'place_of_release',
                            'distance',
                            'participated_competitors',
                            'participated_pigeons',
                            'won_place',
                            'IDcategory',
                            'IDuser',
                            'IDpigeon',*/
                
                            //['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]); ?>
    			</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

