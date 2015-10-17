<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\CoupleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

if($_MODEL_CHOOSE=="CoupleRacing")
{
	$this->title=Yii::t('default', 'PAR_NATJEC_ADMIN_TITLE');
	$url_hatchingdiary='/couple-racing/hatching-diary';
}
else if($_MODEL_CHOOSE=="CoupleBreeding")
{
	$this->title=Yii::t('default', 'PAR_UZGOJNI_ADMIN_TITLE');
	$url_hatchingdiary='/couple-breeding/hatching-diary';
}
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'PAR_NATJEC_LINK_CREATE'),  'url' => ['create']];
$menuItems[] = ['label' => Yii::t('default', 'PAR_NATJEC_LINK_ADMIN'),  'url' => ['index']];
?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>

<?= $this->render('/_alert'); ?>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4><?php echo Yii::t('default', 'PAR_NATJEC_ADMIN_H1'); ?></h4>
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
                        'couplenumber',
						[
							'attribute'=>'search_male',
							'label'=>Yii::t('default', 'Male'),
							'value'=>function($data)
							{
								return $data->relationMale->pigeonnumber;
							},
						],
						[
							'attribute'=>'search_female',
							'label'=>Yii::t('default', 'Female'),
							'value'=>function($data)
							{
								return $data->relationFemale->pigeonnumber;
							},
						],
                        'year',
						[
							'label'=>Yii::t('default', 'Hatching diary'),
							'format'=>'raw',
							'value'=>function($data) use ($url_hatchingdiary)
							{
								return '<a href="'.Url::to([$url_hatchingdiary, 'IDcouple'=>$data->ID]).'" target="_blank" class="label label-success">'.Yii::t('default', 'See diary').'</a>';
							},
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
