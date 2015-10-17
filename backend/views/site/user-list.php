<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
?>
<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'tableOptions'=>['class'=>'table table-striped table-hover table-bordered responsive'],
	'columns' => [
		['class' => 'yii\grid\SerialColumn'],
		'name_of_breeder',
		'email1',
		'email2',
		[
			'label'=>'Real Email',
			'value'=>function($data){ return $data->relationIDuser->email; },
		],
		[
			'label'=>'Username',
			'value'=>function($data){ return $data->relationIDuser->username; },
		]
		//'IDuser',
	],
]); ?>
<hr>
<?php
foreach($userEmail as $value)
{
	echo $value->email.", ".$value->username."<br>";
}
?>
