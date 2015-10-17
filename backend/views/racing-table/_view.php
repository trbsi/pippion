<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\helpers\ExtraFunctions;
use yii\grid\GridView;
use backend\models\RacingTable;
use backend\models\RacingTableCategory;
use backend\models\Pigeon;

$ExtraFunctions = new ExtraFunctions;
$RacingTable = new RacingTable;
$RacingTableCategory = new RacingTableCategory;
$Pigeon = new Pigeon;
?>

<?= $h2 ?>

<?php
if(isset($littleTable))
	echo $littleTable;
?>
<div style="clear:both; padding-top:20px;"></div>
<div class="table-responsive">
 <?= GridView::widget([
 
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'tableOptions'=>['class'=>'table table-hover table-bordered table-striped'],
	'headerRowOptions'=>['class'=>'GridView-header'],
	'columns' => [
	   // ['class' => 'yii\grid\SerialColumn'],

		[
			'attribute'=>'racing_date',
			'value'=>function($data) use ($ExtraFunctions)
			{
				return $ExtraFunctions->formatDate($data->racing_date);
			},
			'filter'=>Html::activeTextInput($searchModel, 'racing_date', ['class'=>'js-datepicker form-control']),
		],
		[
			'attribute'=>'search_by_year',
			'label'=>Yii::t('default', 'Year'),
			'value'=>function($data)
			{
				return date("Y", strtotime($data->racing_date));
			}
		],
		'place_of_release',
		'distance',
		[
			'attribute'=>'participated_competitors',
		],
		
		'participated_pigeons',
		'won_place',
		[
			'label'=>Yii::t('default', 'Osv Bodova Domace'),
			'value'=>function($data) use ($RacingTable)
			{
				return $RacingTable->calculationHomePoints33($data);
			},
		],
		[
			'label'=>Yii::t('default', 'Osv Bodova Strano'),
			'value'=>function($data) use ($RacingTable)
			{
				return $RacingTable->calculationForeignPoints20($data);
			},

		],
		[
			'attribute'=>'IDcategory',
			'value'=>function($data)
			{
				return $data->relationIDcategory->category;
			},
			'visible'=>($_GET['target']=="pigeon") ? true : false,
			'filter'=>$RacingTableCategory->dropDownListCategory(),
		],
		
		[
			'attribute'=>'search_pigeon',
			'label'=>Yii::t('default', 'Idgolub'),
			'value'=>function($data)
			{
				return $data->relationIDpigeon->pigeonnumber;
			},
			'visible'=>($_GET['target']=="category") ? true : false,
		],

		[
			'class' => 'yii\grid\ActionColumn',
			'contentOptions'=>['class'=>'ActionColumn-css'],
			'template' => '{update} {delete}'
		],
	],
]); ?>
</div>