<?php

use yii\helpers\Html;
use yii\grid\GridView;

use backend\helpers\ExtraFunctions;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\PigeonDataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('default', 'PODACI_GOLUB_ADMIN_TITLE');
$this->params['breadcrumbs'][] = $this->title;
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'PODACI_GOLUB_LINK_CREATE'),  'url' => ['create']];
$menuItems[] = ['label' => Yii::t('default', 'PODACI_GOLUB_LINK_ADMIN'),  'url' => ['index']];
?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4><?php echo Yii::t('default', 'PODACI_GOLUB_H1'); ?></h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">

			<?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
				'tableOptions'=>['class'=>'table table-hover table-bordered table-striped responsive'],
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],
        
                    //'ID',
                    //'IDuser',
                    [
                        'attribute'=>'pigeonnumber_search',
                        'label'=> Yii::t('default', 'PODACI_GOLUB_ATTR_GOLUB'),
                        'value'=>function($data)
                        {
                            return $data->relationIDpigeon->pigeonnumber;
                        }
                        
                    ],
                    'pigeondata:ntext',
                    'year',
                    [
                        'attribute'=>'date_created',
                        'value'=>function($data)
                        {
                            $ef = new ExtraFunctions;
                            return $ef->formatDate($data->date_created);
                        },
                        'filter'=>Html::activeTextInput($searchModel, 'date_created', ['class'=>'js-datepicker'] ),
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
