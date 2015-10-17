<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\club\models\search\ClubMembersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->club." | ".Yii::t('default', 'Club Members');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="club-members-index">
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
              <p>
                <?php
				 if($pageAdmin==true)
				 	echo Html::a(Yii::t('default', 'Add member'), ['add-member', 'club_page'=>$model->club_link], ['class' => 'btn btn-success']);
				 ?>
              </p>
              <div class="table-responsive">
                <?= GridView::widget([
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'columns' => [
					['class' => 'yii\grid\SerialColumn'],
		
				   //'ID',
					//'IDclub',
					'name',
					'address',
					'tel',
					'mob',
					'email:email',
					[
						'class' => 'yii\grid\ActionColumn',
						'template'=>'{update} {delete}',
						'buttons'=>
						[
							'update' => function ($url, $model, $key)
							{
								return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 
										Url::to(["/club/club/update-member", "id"=>$model->ID, 'club_page'=>$model->relationIDclub->club_link]));
							},
							'delete' => function ($url, $model, $key)
							{
								return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::to(["/club/club/delete-member", "id"=>$model->ID]), ["data-method"=>"post", "data-confirm"=>Yii::t('default', 'Are you sure you want to delete this item?')]);
							},
						],
						'visible'=>$pageAdmin,
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
