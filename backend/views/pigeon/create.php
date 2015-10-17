<?php

use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model backend\models\Pigeon */

$this->title = Yii::t('default', 'GOLUB_TITLE_CREATE');
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Pigeons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'GOLUB_LINK_ADMIN'),  'url' => ['index']];
?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>
<script type="text/javascript">
$(document).ready(function(e) {
	var Father_ID=$("#Father_ID");
	var Mother_ID=$("#Mother_ID");

	var new_father=$("#novi_otac");
	var exist_father=$("#postojeci_otac");

	var new_mother=$("#nova_majka");
	var exist_mother=$("#postojeca_majka");
	
	var pigeonfather = {pigeonnumber:"#pigeonfather-pigeonnumber", year:"#pigeonfather-year"};
	var pigeonmother = {pigeonnumber:"#pigeonmother-pigeonnumber", year:"#pigeonmother-year"};
	
	//initialize values of text fields that are required, because maybe user doesn't want to add new father and mother, but those fields has to be filled to submit form
	new_father.find(pigeonfather["pigeonnumber"]).val('null');
	new_father.find(pigeonfather["year"]).val('1900');
	new_mother.find(pigeonmother["pigeonnumber"]).val('null');
	new_mother.find(pigeonmother["year"]).val('1900');

	$("#dont_add_father").click(function()
	{
		new_father.hide();
		new_father.find(pigeonfather["pigeonnumber"]).val('null');
		new_father.find(pigeonfather["year"]).val('1900');

		exist_father.hide();
		Father_ID.prop('required',false);

	});
	
	//show or hide fields for adding new or existing father
	$("#radio_existing_father").click(function()
	{
		new_father.hide();
		//since Pigeon model requires npigeonnuber and year to be filled, if user doesn't fill them and choose to add existing father he won't be able to save it because yii's javascript will detect those fields are empty and will disable submit. So I add some null value, it doesn't matter because it won't be used
		new_father.find(pigeonfather["pigeonnumber"]).val('null');
		new_father.find(pigeonfather["year"]).val('1900');

		exist_father.show();
		//since I have to be sure that user has selected something in dropdownlist of existing father so I can save that to database I have to add this required attribute to select field
		Father_ID.prop('required',true);
		$("#tabs-2").find(".alert-error").removeClass("alert-error").addClass("alert-success");
	});
	

	$("#radio_new_father").click(function()
	{
		new_father.show();
		//remove added values. Read description below
		new_father.find(pigeonfather["pigeonnumber"]).val("");
		new_father.find(pigeonfather["year"]).val("");

		exist_father.hide();
		//if user doesn't want to choose existing father then set required attribute to false so it doesn't mess with submit
		Father_ID.prop('required',false);
		$("#tabs-2").find(".alert-success").removeClass("alert-success").addClass("alert-error");
	});
	
	
		
	$("#dont_add_mother").click(function()
	{
		new_mother.hide();
		new_mother.find(pigeonfather["pigeonnumber"]).val('null');
		new_mother.find(pigeonfather["year"]).val('1900');

		exist_mother.hide();
		Mother_ID.prop('required',false);

	});

	//show or hide fields for adding new or existing mother
    $("#radio_existing_mother").click(function()
	{
		new_mother.hide();
		//since Pigeon model requires npigeonnuber and year to be filled, if user doesn't fill them and choose to add existing father he won't be able to save it because yii's javascript will detect those fields are empty and will disable submit. So I add some null value, it doesn't matter because it won't be used
		new_mother.find(pigeonmother["pigeonnumber"]).val('null');
		new_mother.find(pigeonmother["year"]).val('1900');

		
		exist_mother.show();
		Mother_ID.prop('required',true);
		$("#tabs-3").find(".alert-error").removeClass("alert-error").addClass("alert-success");
	});
	
	$("#radio_new_mother").click(function()
	{
		new_mother.show();
		//remove added values. Read description below
		new_mother.find(pigeonmother["pigeonnumber"]).val("");
		new_mother.find(pigeonmother["year"]).val("");
		
		exist_mother.hide();
		Mother_ID.prop('required',false);
		$("#tabs-3").find(".alert-success").removeClass("alert-success").addClass("alert-error");
	});
	


});

</script>


<div class="page-title"> <i class="icon-custom-left"></i>
    <h3><?php echo Yii::t('default', 'GOLUB_STVORI'); ?></h3>
</div>
  <?= $this->render('_form', [
		'model'=>$model, 
		'father'=>$father, 
		'mother'=>$mother,
    ]) ?>
