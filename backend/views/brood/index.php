<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use backend\helpers\ExtraFunctions;
use backend\models\Pigeon;
use backend\models\Status;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\BroodBreedingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

if($_MODEL_CHOOSE=="BroodRacing")
{
	$this->title = Yii::t('default', 'LEGLO_NATJEC_ADMIN_TITLE');
	//add young pigeons to list url
	$addyoungtolist_url=Url::to('/brood-racing/add-young-to-list');
}
else
{
	$this->title = Yii::t('default', 'LEGLO_UZGOJNI_ADMIN_TITLE');
	//add young pigeons to list url
	$addyoungtolist_url=Url::to('/brood-breeding/add-young-to-list');
}

$currentUrl='/'.Yii::$app->controller->id.'/'.Yii::$app->controller->action->id;

$this->params['breadcrumbs'][] = $this->title;

$Pigeon = new Pigeon;
$Status = new Status;
$ExtraFunctions = new ExtraFunctions;
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'LEGLO_NATJEC_LINK_CREATE'),  'url' => ['create']];
?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>
<?= $this->render('/_alert'); ?>

<script>
$(document).ready(function(e) {
    $('button[name="add_to_list_of_pigeons"]').click(function()
	{
		var val = $("#status-id").val();
		if(val=="")
		{
			 alert("<?php echo Yii::t('default', 'Odaberi status') ?>");
			 return false
		}
		else if (confirm('<?= Yii::t('default', 'Are you sure?')?>')==false)
		{
			return false;
		}
			
	});
});
</script>
<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4><?php echo Yii::t('default', 'LEGLO_NATJEC_ADMIN_H1');  ?></h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="alert alert-danger"> <?php echo Yii::t('default', 'LEGLO_UZGOJNI_ADMIN_DELETE_PORUKA'); ?> </div>
              <?php $form=ActiveForm::begin(
			  		[
						'method'=>'get',
						'action'=>$currentUrl,
						'enableClientScript'=>false,
			  		]); 
			  ?>
            <?= GridView::widget([
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'tableOptions'=>['class'=>'table table-bordered table-striped table-hover responsive'],
				'columns' => [
					//['class' => 'yii\grid\SerialColumn'],
		
					//'ID',
					//'IDD',
					//'IDuser',
					[
						'value'=>function($data) use ($_MODEL_CHOOSE)
						{
							return $data->youngPigeonInList($data,$_MODEL_CHOOSE);
						},
						'format'=>'raw',
						//'htmlOptions'=>array('style'=>'width:25px;'),
						'filter'=>'<img src="/images/info.png" title="'.Yii::t('default', 'Leglo admin explanation').'" data-placement="right" class="tooltipp infoIcon">'
					],

					[
						'class' => 'yii\grid\CheckboxColumn',
						// you may configure additional properties here
					],

					[
						'attribute'=>'IDcountry',
						'value'=>function($data)
						{
							return $data->relationIDcountry->country;
						},
						'filter'=>$Pigeon->dropDownListPigeonCountry(),
					],
					'ringnumber',
					'color',
					[
						'attribute'=>'search_couple',
						'label'=>Yii::t('default', 'LEGLO_NATJEC_ATTR_PAR'),
						'value'=>function($data) use ($_MODEL_CHOOSE) //https://github.com/yiisoft/yii/issues/1883
						{
							if($_MODEL_CHOOSE=="BroodRacing")
								return $data->relationIDcoupleRacing->couplenumber;
							else
								return $data->relationIDcoupleBreeding->couplenumber;
						}
					],
					[
						'attribute'=>'firstegg',
						'value'=>function($data) use ($ExtraFunctions)//https://github.com/yiisoft/yii/issues/1883
						{
							return $ExtraFunctions->formatDate($data->firstegg);
						},
						'filter'=>Html::activeTextInput($searchModel, 'firstegg', ['class'=>'js-datepicker form-control']),
					],

					[
						'attribute'=>'hatchingdate',
						'value'=>function($data) use ($ExtraFunctions)
						{
							return $ExtraFunctions->formatDate($data->hatchingdate);
						},
						'filter'=>Html::activeTextInput($searchModel, 'hatchingdate', ['class'=>'js-datepicker form-control']),
					],
					[
						'attribute'=>'search_year',
						'label'=>Yii::t('default', 'Year'),
						'filter'=>Html::activeInput('number', $searchModel, 'search_year', ['class'=>'form-control', 'max'=>date("Y")+5] ),
						'value'=>function($data)
						{
							return date("Y", strtotime($data->hatchingdate));
						},
					],

		
					['class' => 'yii\grid\ActionColumn'],
				],
			]); ?>
			<?php
			///this is hidden submit button. Because when user wants to search and he enters data to search and hit Enter this button will  e activated, otherwise this second button will be activated and will ask me to transfer young pigeons
           // echo Html::submitButton("SUBMIT", ['style'=>'display:none']);

            echo Html::activeDropDownList($Status, 'ID', $Pigeon->dropDownListStatus(), ['prompt'=>'----------'.Yii::t('default', 'STATUS_STATUS').'----------', 'class'=>'form-control']);
			echo "<br>";
            echo Html::submitButton(Yii::t('default', 'LEGLO_UZGOJNI_ADMIN_BTN_DODAJ_UPOPIS'), ['formaction'=>$addyoungtolist_url, 'formmethod'=>'GET', 'class'=>'btn btn-success btn-cons', 'name'=>'add_to_list_of_pigeons']);
            ?>

            <?php ActiveForm::end(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
