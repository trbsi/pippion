<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\CoupleRacing;
use backend\models\BroodRacing;

/* @var $this yii\web\View */
/* @var $model backend\models\MANY DIFFERENT */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
table.pigeons tr td, table.pigeons tr th {
	white-space: pre-wrap;
	padding: 3px 3px;
}
table.pigeons input, table.pigeons select, table.pigeons tr th {
	max-width:80px;
}
</style>
<script>
$(document).ready(function(e) {
	$("#submit_pigeon_number").click(function()
	{
		var temp_no=$("#number_pigeons").val();
		if(temp_no>200)
			temp_no=200;
		else if(temp_no<1)
			temp_no=1;
			
		window.location="<?= Url::to(["/quick-insert/create"]) ?>?number_pigeons="+temp_no;
	});
    
});

function showDiv(x)
{
	if(x=="status")
	{
		$("#div_status").toggle();
	}
	else if(x=="pigeon")
	{
		$("#div_pigeon").toggle();
	}
	else if(x=="couples")
	{
		$("#div_couples").toggle();
	}
	else if(x=="broods")
	{
		$("#div_broods").toggle();
	}
}

function coupleChoose(element)
{
	window.location="<?= Url::to(["/quick-insert/create"]) ?>?couple="+$(element).val();
}

function broodChoose(element)
{
	window.location="<?= Url::to(["/quick-insert/create"]) ?>?brood="+$(element).val();
}

</script>
<div class="admin-nots-form">
  <p> 
  	<a href="<?= Url::to(["/site/contact"])?>" class="btn btn-small btn-primary" target="_blank"><?= Yii::t('default', 'Kontaktirajte nas')?> </a>
  	<a href="<?= Url::to(["/index.php"])?>" class="btn btn-small btn-success" ><?= Yii::t('default', 'Back to app')?> </a>
  </p>
  <hr />
  
  <p><a href="javascript:;" onclick="showDiv('status')" class="label label-danger"><?= Yii::t('default', 'Click to add pigeon status') ?></a></p>
  <div style="display:none" id="div_status">
	  <?= $this->render('forms/status', ['Status'=>$Status]) ?>
  </div>
  <hr />
  
  <p><a href="javascript:;" onclick="showDiv('pigeon')" class="label label-danger"><?= Yii::t('default', 'Click to add pigeons') ?></a></p>
  <?php  $style=(isset($_GET["number_pigeons"])) ? "" : 'style="display:none"'; ?>
  <div <?= $style ?>  id="div_pigeon">
	  <?= $this->render('forms/pigeon', ['Pigeon'=>$Pigeon]) ?>
  </div>
  <hr />
  
  <p><a href="javascript:;" onclick="showDiv('couples')" class="label label-danger"><?= Yii::t('default', 'Click to add couples') ?></a></p>
  <div <?php echo (isset($_GET["couple"])) ? "" : 'style="display:none"' ?> id="div_couples">
  	<p>
  	  <label><input type="radio" name="couple" value="<?= CoupleRacing::CoupleRacingName ?>" onchange="coupleChoose(this)" /> <?= Yii::t('default', 'Racing couple') ?></label>
  	  <label><input type="radio" name="couple" value="<?= CoupleRacing::CoupleBreedingName ?>" onchange="coupleChoose(this)"/> <?= Yii::t('default', 'Breeding couple') ?></label>
  	  <br />
    </p>
  </div>
	<?php 
    if(isset($_GET["couple"]))
    {
        $_GET_couple=$_GET["couple"];
        if($_GET_couple==CoupleRacing::CoupleRacingName)
		{
            $Couple=$CoupleRacing;
			$BroodName=Yii::t('default', 'Racing couple');
		}
        else if($_GET_couple==CoupleRacing::CoupleBreedingName)
		{
            $Couple=$CoupleBreeding;
			$BroodName=Yii::t('default', 'Breeding couple');
		}
		else
		{ 
			$Couple=NULL;
			$BroodName=NULL;
		}
			
		echo '<h2>'.$BroodName.'</h2>';	
    	if($Couple!=NULL)
     	   echo $this->render('forms/couple', ['Couple'=>$Couple, '_GET_couple'=>$_GET_couple]);	
    }
    ?>
  
  <hr />
  
  <p><a href="javascript:;" onclick="showDiv('broods')" class="label label-danger"><?= Yii::t('default', 'Click to add broods') ?></a></p>
  <div <?php echo (isset($_GET["brood"])) ? "" : 'style="display:none"' ?> id="div_broods">
  	<p>
  	  <label><input type="radio" name="brood" value="<?= BroodRacing::BroodRacingName ?>" onchange="broodChoose(this)" /> <?= Yii::t('default', 'Racing broods') ?></label>
  	  <label><input type="radio" name="brood" value="<?= BroodRacing::BroodBreedingName ?>" onchange="broodChoose(this)"/> <?= Yii::t('default', 'Breeding broods') ?></label>
  	  <br />
    </p>
  </div>
	<?php 
    if(isset($_GET["brood"]))
    {
        $_GET_brood=$_GET["brood"];
        if($_GET_brood==BroodRacing::BroodRacingName)
		{
            $Brood=$BroodRacing;
			$BroodName=Yii::t('default', 'Racing broods');
		}
        else if($_GET_brood==BroodRacing::BroodBreedingName)
		{
            $Brood=$BroodBreeding;
			$BroodName=Yii::t('default', 'Breeding broods');
		}
		else 
		{
			$Brood=NULL;
			$BroodName=NULL;
		}
		
		echo '<h2>'.$BroodName.'</h2>';	
    	if($Brood!=NULL)
     	   echo $this->render('forms/broods', ['Brood'=>$Brood, 'Pigeon'=>$Pigeon, '_GET_brood'=>$_GET_brood]);	
    }
    ?>
  <hr />
  
  
</div>
