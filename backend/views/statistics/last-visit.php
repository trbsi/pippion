<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use backend\models\CountryList;
use backend\models\Breeder;
use backend\models\search\BreederSearch;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\BreederSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Last active users";
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4>
          <?= Html::encode($this->title) ?>
        </h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <?= GridView::widget([
			'dataProvider' => $dataProvider,
			'columns' => [
				[
					'attribute'=>'IDuser',
					'value'=>function($data)
					{
						return $data->relationIDuser->username;
					},
				],
				[
					'attribute'=>'last_visit',
					'value'=>function($data)
					{
						return $data->last_visit;
					},
				],
			],
			]); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
