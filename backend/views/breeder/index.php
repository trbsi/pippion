<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use backend\models\CountryList;
use backend\models\Breeder;
use backend\helpers\ExtraFunctions;
use backend\models\search\BreederSearch;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\BreederSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('default', 'Search breeders');
$this->params['breadcrumbs'][] = $this->title;
$isAdmin=Yii::$app->user->identity->getIsAdmin();
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
            <?php
			$CountryList = new CountryList;
			$Breeder = new Breeder;
			$BreederSearch = new BreederSearch;
			?>
				<?= GridView::widget([
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'columns' => [
					//['class' => 'yii\grid\SerialColumn'],
				   // 'ID',
					[
						'attribute'=>'IDuser',
						'value'=>function($data){return $data->relationIDuser->username;}, //ILI 'relationIDuser.username'
						'filter'=>Html::activeTextInput($searchModel, 'IDuser', ['id'=>'searchUserFilter']),
					],
				   // 'IDuser',
					'name_of_breeder',
					[
						'attribute'=>'country',
						'value'=>function($data)
						{
							return $data->relationCountry->country_name;
						},
						'filter'=>$CountryList->dropdownCountryList(),
					],
					//'town',
					//'address',
					//'tel1',
					//'tel2',
					//'mob1',
					//'mob2',
					'email1:email',
					//'email2:email',
					//'fax',
					//'website',
					[
						'attribute'=>'verified',
						'format'=>'raw',
						'value'=>function($data)
						{
							if($data->verified==0) 
							{
								return '<img src="'.Yii::getAlias('@web').'/images/not_verified.png" width="25" height="25">';
							}
							else
								return '<img src="'.Yii::getAlias('@web').'/images/verified.png"  width="25" height="25">'; 
						
						},
						'filter'=>$Breeder->dropDownListSearchYesNo(),
					],
					[
						'attribute'=>'last_visit',
						'value'=>function($data)
						{
							if (!empty($data->relationLastVisit->last_visit))
								return ExtraFunctions::formatDate($data->relationLastVisit->last_visit, "ymd-his");
							else
								return "";
						},
					],
					/*[
						'attribute'=>'end_date',
						'value'=>function($data)
						{
							//return ExtraFunctions::formatDate($data->relationSubscription->end_date, "ymd-his");
						},
						'visible'=>$isAdmin
					],*/
					[
						'label'=>Yii::t('default', 'View'),
						'format'=>'raw',
						'value'=>function($data)
						{
							return  Html::a('View', Url::to(['view','id'=>$data->IDuser]),  ['class'=>'label label-success'] );
						}
					],
					/*[
						'class' => 'yii\grid\ActionColumn',
						'template'=>'{view}',
					],*/
				],
			]); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

    <?php /* Html::a(Yii::t('default', 'Create {modelClass}', [
    'modelClass' => 'Breeder',
]), ['create'], ['class' => 'btn btn-success'])*/ ?>

