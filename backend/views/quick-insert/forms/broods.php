<?php
use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\Pigeon;
use backend\models\CoupleRacing;
?>

<script>
$(document).ready(function(e) {
	$("#submit_number_of_broods").click(function()
	{
		var temp_no=$("#number_of_broods").val();
		if(temp_no>200)
			temp_no=200;
		else if(temp_no<1)
			temp_no=1;
			
		window.location="<?= Url::to(["/quick-insert/create", 'brood'=>$_GET_brood]) ?>&number_of_broods="+temp_no;
	});
    
});
</script>

<p><a href="<?= Url::to('/quick-insert/create')?>" class="btn btn-cons btn-default">
  <?= Yii::t('default', 'Reset') ?>
  </a></p>
<p> <span class="label label-info">
  <?= Yii::t('default', 'How many couples you want to insert')?>
  </span>&nbsp;
  <input type="number" value="1" min="1" max="200" id="number_of_broods" class="input-sm" required />
  <button type="button" id="submit_number_of_broods" class="btn btn-default btn-mini">
  <?= Yii::t('default', 'Submit') ?>
  </button>
</p>


<?php echo Html::beginForm('', 'post', [] ); ?>

<table class="table table-bordered table-striped table-hover">
<?php 
	$end=(isset($_GET["number_of_broods"])) ? $_GET["number_of_broods"] : 20;
	for($i=0;$i<$end;$i++): 
	$j=2*$i;
	$k=2*$i+1;
	$x=$i+1;
?>
  <tr>
    <td colspan="5">
    	<p><strong><?= $x.". ".Yii::t('default', 'NAV_DODAJ_LEGLO') ?></strong></p>
    	<?= Yii::t('default', 'LEGLO_NATJEC_ATTR_PAR') ?><br /><?= Html::activeDropDownList($Brood, "[$j]IDcouple" ,CoupleRacing::dropDownListAllCouples($_GET_brood), ['class'=>'form-control']) ?>
    </td>
  </tr>
  <tr>
    <td><?= Yii::t('default', 'LEGLO_NATJEC_ATTR_PRVO_JAJE') ?><br /><?= Html::activeTextInput($Brood, "[$j]firstegg",['class'=>'form-control js-datepicker', 'value'=>date("Y-m-d")]) ?></td>
    <td><?= Yii::t('default', 'LEGLO_NATJEC_ATTR_DATUM_LEZENJA') ?> <br /><?= Html::activeTextInput($Brood, "[$j]hatchingdate", ['class'=>'form-control js-datepicker', 'value'=>date("Y-m-d")]) ?></td>
    <td><?= Yii::t('default', 'LEGLO_NATJEC_ATTR_DRZAVA') ?> <br /><?= Html::activeDropDownList($Brood, "[$j]IDcountry",$Pigeon->dropDownListPigeonCountry(), ['class'=>'form-control']) ?></td>
    <td><?= Yii::t('default', 'LEGLO_NATJEC_ATTR_BROJ_PRSTENA') ?> <br /><?= Html::activeTextInput($Brood, "[$j]ringnumber", ['maxlength' => 20, 'class'=>'form-control']) ?></td>
    <td><?= Yii::t('default', 'LEGLO_NATJEC_ATTR_BOJA') ?> <br /><?= Html::activeTextInput($Brood, "[$j]color", ['maxlength' => 30, 'class'=>'form-control']) ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><?= Yii::t('default', 'LEGLO_NATJEC_ATTR_DRZAVA') ?> <br /><?= Html::activeDropDownList($Brood, "[$k]IDcountry", $Pigeon->dropDownListPigeonCountry(), ['class'=>'form-control']) ?></td>
    <td><?= Yii::t('default', 'LEGLO_NATJEC_ATTR_BROJ_PRSTENA') ?> <br /><?= Html::activeTextInput($Brood, "[$k]ringnumber", ['maxlength' => 20, 'class'=>'form-control']) ?></td>
    <td><?= Yii::t('default', 'LEGLO_NATJEC_ATTR_BOJA') ?> <br /><?= Html::activeTextInput($Brood, "[$k]color", ['maxlength' => 30, 'class'=>'form-control']) ?></td>
  </tr>

<?php endfor; ?>
</table>
<br />
<?= Html::submitButton(Yii::t('default', 'Create'), ['class' => 'btn btn-success btn-cons']) ?>

<?php echo Html::endForm(); ?>