<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use backend\models\CountryList;
use backend\helpers\ExtraFunctions;
use backend\helpers\LinkGenerator;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ClubSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('default', 'Clubs');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="club-index">
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
                <?= Html::a(Yii::t('default', 'Create Club'), ['create'], ['class' => 'btn btn-success']) ?>
              </p>
              <div class="table-responsive">
				<?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        //['class' => 'yii\grid\SerialColumn'],
            
                        'club',
                        
                        [
                            'attribute'=>'IDcountry',
                            'value'=>function($data)
                            {
                                return "[".$data->relationIDcountry->country_code."] ".$data->relationIDcountry->country_name."/".$data->city;
                            },
                            'filter'=>Html::activeDropDownList($searchModel, 'IDcountry', CountryList::dropdownCountryList(), ['prompt'=>'', 'class'=>'form-control'] ),
                        ],
                                            
                        [
                            'attribute'=>'date_created',
                            'value'=>function($data)
                            {
                                return ExtraFunctions::formatDate($data->date_created, $custom=NULL);
                            },
                            'filter'=>Html::activeTextInput($searchModel, 'date_created', ['class'=>'js-datepicker'] ),
                        ],
                        
                        [
                            'format'=>'html',
                            'value'=>function($data)
                            {
                                return LinkGenerator::clubIndividualClubpage(Yii::t('default', 'Visit club'), strtolower($data->club_link), ['class'=>'label label-success']);
								
                            }
                        ]
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
</div>
