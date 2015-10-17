<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\FoundPigeons;
use backend\models\Pigeon;
use backend\helpers\ExtraFunctions;

$Pigeon = new Pigeon;
$ExtraFunctions = new ExtraFunctions;
/* @var $this yii\web\View */
/* @var $model backend\models\FoundPigeons */

$this->title = Yii::t('default', 'View Found Pigeon');
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Found Pigeons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'Update'),  'url' => ['update', 'id' => $model->ID]];
$menuItems[] = ['label' => Yii::t('default', 'Delete'),  'url' => ['delete', 'id' => $model->ID], 
				'linkOptions'=>
					[
					 	'data' => 
					 	[
							'confirm' => Yii::t('default', 'Are you sure you want to delete this item?'),
							'method' => 'post',
						],
					],
				];
$menuItems[] = ['label' => Yii::t('default', 'Ive found a pigeon'),  'url' => ['create']];
$menuItems[] = ['label' => Yii::t('default', 'List of pigeons Ive found'),  'url' => ['index']];
?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>
<?= $this->render('/_alert'); ?>


<script>
//https://developers.google.com/maps/documentation/javascript/geocoding
var geocoder;
var map;
function initialize() 
{
  geocoder = new google.maps.Geocoder();
  var latlng = new google.maps.LatLng(-34.397, 150.644);
  var mapOptions = {
    zoom: 14,
    center: latlng
  }
  map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
}

function codeAddress() 
{
  var address = document.getElementById('address').value;
  geocoder.geocode( { 'address': address}, function(results, status) 
  {
    if (status == google.maps.GeocoderStatus.OK) 
	{
      map.setCenter(results[0].geometry.location);
      var marker = new google.maps.Marker({
          map: map,
          position: results[0].geometry.location
      });
    } 
	else 
	{
      alert('Geocode was not successful for the following reason: ' + status);
    }
  });
}

google.maps.event.addDomListener(window, 'load', function(){initialize(); codeAddress()});

</script>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4><?php echo Yii::t('default', 'View Found Pigeon').' '.$model->pigeonnumber; ?></h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
        <div class="row">
        
         <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
         	<div class="table-responsive">
            <?= DetailView::widget([
                'model' => $model,
				'options'=> ['class'=>'table table-hover table-striped responsive'],
                'attributes' => [
                   // 'ID',
				    [
                    	'attribute'=>'IDcountry',
						'value'=>$model->relationPigeonIDcountry->country,
					],
                    'pigeonnumber',
					[
						'attribute'=>'sex',
						'value'=>$Pigeon->getSex($model->sex),
					],
                    'year',
                   // 'IDuser',
				    [
                    	'attribute'=>'country',
						'value'=>$model->relationRealIDcountry->country_name
					],
                    'city',
                    'address',
                    'zip',
                   // 'image_file',
				    [
                    	'attribute'=>'returned',
						'format'=>'html',
						'value'=>($model->returned)==0?'<img src="/images/error.png" width="20" height="20">':'<img src="/images/good.png" width="20" height="20">',
                	],
					[
						'attribute'=>'date_created',
						'value'=>call_user_func(function($model, $ExtraFunctions) 
						{ 
							return $ExtraFunctions->formatDate($model->date_created, "ymd");
						}, 
						$model, $ExtraFunctions),	
					],

					[
						'attribute'=>'image_file',
						'format'=>'raw',
						'value'=>call_user_func(function($model) 
						{ 
							$pic=(empty($model->image_file)) ? FoundPigeons::NO_PICTURE : $model->image_file ;
							return '<a class="group1_colorbox" href="'.FoundPigeons::UPLOAD_DIR.$pic.'"><img src="'.FoundPigeons::UPLOAD_DIR.$pic.'" class="img-responsive img-thumbnail"></a>';
						}, 
						$model),
						
						
					],
					
				],
            ]) ?>
            </div>
          </div>
          <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
          	<input id="address" type="textbox" style="display:none" value="<?php echo $model->relationRealIDcountry->country_name.", ".$model->city.", ".$model->address.", ".$model->zip; ?>">
          	<div id="map-canvas"></div>
          </div>
          
        </div>
      </div>
    </div>
  </div>
</div>

