<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use  backend\modules\club\models\ClubTables;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\club\models\search\ClubTablesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
if($result_type==ClubTables::RESULT_TYPE_TEAM)
	$title=Yii::t('default', 'Tables');
else
	$title=Yii::t('default', 'Pigeon tables');
$this->title = $model->club." | ".$title;
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('@backend/views/_alert'); ?>

<div class="club-tables-index">
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
              <div class="form-group">
                <div class="table-responsive">
                  <?= GridView::widget([
					'dataProvider' => $dataProvider,
					'filterModel' => $searchModel,
					'columns' => [
						//['class' => 'yii\grid\SerialColumn'],
						[
							'attribute'=>'pdf_file',
							'label'=>Yii::t('default', 'Download'),
							'format'=>'raw',
							'value'=>function($data)
							{
								$dir=ClubTables::clubTablesUploadDir(Yii::getAlias('@web'), $data->year, $data->relationIDclub->ID, $data->pdf_file);
								return '<a href="'.Html::encode($dir).'" target="_blank" class="btn btn-primary">'.Yii::t('default', 'Download').'</a>';	
							},
							'filter'=>""
						],
						[
							'attribute'=>'description',
							'format'=>'html',
							'value'=>function($data)
							{
								return "<strong>".Html::encode($data->description)."</strong>";
							}
						],
						'year',
						[
							'class' => 'yii\grid\ActionColumn',
							'template' => '{update} {delete}',
							'buttons'=>
							[
								'update' => function ($url, $model, $key)
								{
									return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 
											Url::to(["/club/club-tables/update", "id"=>$model->ID, 'club_page'=>$model->relationIDclub->club_link]));
								},
							],
							'visible'=>$pageAdmin
						],
					],
				]); ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
