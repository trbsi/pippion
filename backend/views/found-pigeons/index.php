<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use backend\models\Pigeon;
use backend\models\CountryList;
use backend\models\FoundPigeons;
use backend\helpers\ExtraFunctions;

$ExtraFunctions = new ExtraFunctions;
$CountryList = new CountryList;
$Pigeon = new Pigeon;
$FoundPigeons = new FoundPigeons;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\FoundPigeonsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'Add found pigeon'),  'url' => ['create']];
?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>
<?= $this->render('/_alert'); ?>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4><?php echo Yii::t('default', 'List of pigeons youve found');?></h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
			<?php
            if(Yii::$app->controller->action->id=="public")
			{
                $visible_public=true;
				$visible_owner=false;
			}
            else
			{
                $visible_public=false;
				$visible_owner=true;
			}
            ?>
            <?= GridView::widget([
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'tableOptions'=>['class'=>'table table-hover table-striped table-bordered responsive'],
				'columns' => [
					//['class' => 'yii\grid\SerialColumn'],
		
				   // 'ID',
					[
						'label' => '',
						'format'=>'raw',
						'value'=>function($data)
						{
							return Html::a(Yii::t('default','View'), Url::to(['view', 'id'=>$data->ID]), ['class'=>'label label-success'] );
						},
						'visible'=>$visible_public,
					],
				   'pigeonnumber',
					[
						'attribute'=>'IDcountry',
						'filter'=>$Pigeon->dropDownListPigeonCountry(),
						'value'=>function($data)
						{
							return $data->relationPigeonIDcountry->country;
						},
					],
					
					[
						'attribute'=>'sex',
						'filter'=>$Pigeon->dropDownListSex(),
						'value'=>function($data) use ($Pigeon)
						{
							return $Pigeon->getSex($data->sex);
						},
						
						
					],
					'year',
					// 'IDuser',
					[
						'attribute'=>'country',
						'filter'=>$CountryList->dropdownCountryList(),
						'value'=>function($data)
						{
							return $data->relationRealIDcountry->country_name;
						},
					],
					//'city',
					//'address',
					//'zip',
					/*[
						'label'=>Yii::t('default', 'Picture of a pigeon'),
						'filter'=>false,
					],*/
					[
						'attribute'=>'date_created',
						'value'=>function($data) use ($ExtraFunctions) 
						{ 
							return $ExtraFunctions->formatDate($data->date_created, "ymd");
						}, 	
						'filter'=>Html::activeTextInput($searchModel, 'date_created', ['class'=>'js-datepicker form-control']),
					],

					[
						'attribute'=>'returned',
						'format'=>'html',
						'value'=>function($data)
						{
							return ($data->returned==0)?'<img src="/images/error.png" width="20" height="20">':'<img src="/images/good.png" width="20" height="20">';
						},
						'filter'=>$FoundPigeons->pigeonReturned(),
					],
					[
						'label'=>Yii::t('default', 'IDuser'),
						'format'=>'raw',
						'value'=>function($data)
						{
							return '<span class="label">'.Html::a($data->relationIDuser->username, Url::to(['/breeder/view', 'id'=>$data->IDuser]), ['target'=>'_blank']).'</label>';
						},
						'visible'=>$visible_public
					],
					[
						'class' => 'yii\grid\ActionColumn',
						'visible'=>$visible_owner,
					],
				],
			]); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
