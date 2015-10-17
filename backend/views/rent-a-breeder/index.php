<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use backend\models\RentABreeder;
use backend\models\CountryList;
use backend\models\Currency;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\RentABreederSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('default', 'Rent a breeder');
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('/_alert'); ?>

<script>
function overflowVisible(div_overflow, show_more_btn, show_less_btn)
{
	//find first div inside this main div where overflow is hidden
	var first_child=$("."+div_overflow).children().first();
	//make overflow of main div visible and put height the same as height of that first div inside main div
	$("."+div_overflow).css("overflow","visible").css("height",first_child.height());
	//toggle show more and show less buttons
	$("."+show_more_btn).toggle();
	$("."+show_less_btn).toggle();
}

function overflowHidden(div_overflow, show_more_btn, show_less_btn)
{
	$("."+div_overflow).css("overflow","hidden").css("height", "150px");
	$("."+show_more_btn).toggle();
	$("."+show_less_btn).toggle();
}

<?php
$html=
'<div class="form-group">
  <label for="name_surname">*'.Yii::t('default', 'Name and surname').'</label>
  <input type="text" class="form-control" id="rent_me_name_surname" >
</div>
<div class="form-group">
  <label for="price">*'.Yii::t('default', 'Price').' / <strong style="color:red">'.Yii::t('default', 'Per year').'</strong></label>
  <div class="col-xs-6"><input type="text" class="form-control" id="rent_me_price"></div>
  <div class="col-xs-6">'.Html::dropDownList('currency', null, Currency::dropdownCurrency(), ['class'=>'form-control', 'id'=>'rent_me_currency'] ).'</div>
</div>
<br><br>
<div class="form-group" style="clear:both">
  <label for="country">*'.Yii::t('default', 'Country').'</label>
  '.Html::dropDownList('currency', null, CountryList::dropdownCountryList(), ['class'=>'form-control', 'id'=>'rent_me_country'] ).'
</div>
<div class="form-group">
  <label for="email">*'.Yii::t('default', 'Email').'</label>
  <input type="email" class="form-control" id="rent_me_email">
</div>
<div class="form-group">
  <label for="email">'.Yii::t('default', 'Additional information').'</label>
  <textarea class="form-control" id="rent_me_extra_info"></textarea>
</div>
';
?>
//ID - ID in mg_rent_a_breeder to know which breeder person chose
function rentMeModal(ID)
{
	bootbox.dialog({
		title: "<?= Yii::t('default', 'Rent me') ?>",
		message: '<?= str_replace(["\n", "\t", "\r"],"",$html); ?>',
		buttons: 
		{
			danger: 
			{
				label: "<?= Yii::t('default', 'Cancel') ?>",
				className: "btn-danger",
			},
			success: 
			{
				label: "<?= Yii::t('default', 'Submit') ?>",
				className: "btn-success",
				callback: function(){ hireThisBreeder(ID); },
			},
		}
	});	
}

//ID - ID in mg_rent_a_breeder to know which breeder person chose
function hireThisBreeder(ID)
{
	var name_surname = $("#rent_me_name_surname").val();
	var rent_me_price = $("#rent_me_price").val();
	var rent_me_currency = $("#rent_me_currency").val(); 
	var rent_me_country = $("#rent_me_country").val();
	var rent_me_email = $("#rent_me_email").val();
	var rent_me_extra_info = $("#rent_me_extra_info").val();
	
	if(name_surname=="" || rent_me_price=="" || rent_me_country=="" || rent_me_email=="")
	{
		alert("<?= Yii::t('default', 'Fill all field') ?>");
		return false;
	}
					
	$.ajax
	({
		method: "POST",
		url: "<?= Url::to(["/rent-a-breeder/rent-me-request"]) ?>",
		dataType: "json",
		data: 
		{ 
			ID: ID,
			name_surname: name_surname, rent_me_price: rent_me_price, rent_me_currency: rent_me_currency, rent_me_country: rent_me_country,
			rent_me_email: rent_me_email, rent_me_extra_info: rent_me_extra_info
		},
		success: function(data)
		{
			if(data.success=="true")
				alert("<?= Yii::t('default', 'Rent a breeder requested') ?>");
		}
	});
}
</script>

<div class="rent-abreeder-index">
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
              <div class="table-responsive">
                <?= GridView::widget([
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'columns' => [
					//'IDuser',
					[
						'attribute'=>'breeder_picture',
						'label'=>'',
						'format'=>'raw',
						'value'=>function($data)
						{
							$return=
							'<a href="'.RentABreeder::rentABreederImageDir(Yii::getAlias('@web')).$data->breeder_picture.'" class="group1_colorbox">
							<img src="'.RentABreeder::rentABreederImageDir(Yii::getAlias('@web')).$data->breeder_picture.'" class="img-responsive img-thumbnail"  style="max-height:200px">
							</a>
							<br><br>
							';
							//user cannot rent himself
							if(Yii::$app->user->getId()!=$data->IDuser)
								$return.='<a href="javascript:;" onclick="rentMeModal('.$data->ID.')" class="btn btn-cons btn-success btn-block">'.Yii::t('default', 'Rent me').'</a>';

							return  $return;
							
						},
						'filter'=>false,
					],
					[
						'attribute'=>'extra_info',
						'format'=>'raw',
						'value'=>function($data)
						{
							return '<div style="width:500px;text-align:justify;overflow:hidden; height:150px;" class="overflow_div_'.$data->ID.'">'.
										'<div>'.nl2br(Html::encode($data->extra_info)).'</div>'.
									'</div>
									<br>
									
									<a href="javascript:;" onclick="overflowVisible(\'overflow_div_'.$data->ID.'\', \'show_more_'.$data->ID.'\', \'show_less_'.$data->ID.'\')" class="label label-success show_more_'.$data->ID.'">'
										.Yii::t('default', 'Show more').
									'</a>
									<a href="javascript:;" onclick="overflowHidden(\'overflow_div_'.$data->ID.'\', \'show_more_'.$data->ID.'\', \'show_less_'.$data->ID.'\')" class="label label-danger show_less_'.$data->ID.'" style="display:none">'
										.Yii::t('default', 'Show less').
									'</a>';
						},
						'filter'=>false,
					],
					[
						'attribute'=>'IDcountry',
						'value'=>function($data)
						{
							return $data->relationIDcountry->country_name;
						},
						'filter'=>CountryList::dropdownCountryList()
					],
					'city',
					[
						'label'=>'',
						'format'=>'raw',
						'value'=>function($data)
						{
							if(Yii::$app->user->getId()==$data->IDuser || in_array(Yii::$app->user->getId(), Yii::$app->params['adminId']))
							return
							'
							<a href="'.Url::to(['/rent-a-breeder/update', 'id'=>$data->ID]).'" title="Update" aria-label="Update" data-pjax="0">
								<span class="glyphicon glyphicon-pencil"></span>
							</a>
							<br>
							<a href="'.Url::to(['/rent-a-breeder/delete', 'id'=>$data->ID]).'" title="Delete" aria-label="Delete" data-confirm="'.Yii::t('default',' Are you sure you want to delete this item?').'" data-method="post" data-pjax="0">
								<span class="glyphicon glyphicon-trash"></span>
							</a>
							';
							else 
							return ''
							;
						},
					],
					/*[
						'class' => 'yii\grid\ActionColumn',
						'template'=>'{update} {delete}',
						'visible'=>(in_array(Yii::$app->user->getId(),Yii::$app->params['adminId'])) ? true : false,
					],*/
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
