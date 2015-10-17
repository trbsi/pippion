<?php
use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\Pigeon;
?>
<script>
$(document).ready(function(e) {
	$("#submit_number_of_couples").click(function()
	{
		var temp_no=$("#number_of_couples").val();
		if(temp_no>200)
			temp_no=200;
		else if(temp_no<1)
			temp_no=1;
			
		window.location="<?= Url::to(["/quick-insert/create", 'couple'=>$_GET_couple]) ?>&number_of_couples="+temp_no;
	});
    
});
</script>

<p><a href="<?= Url::to('/quick-insert/create')?>" class="btn btn-cons btn-default"><?= Yii::t('default', 'Reset') ?></a></p>

<p>
<span class="label label-info">
<?= Yii::t('default', 'How many couples you want to insert')?>
</span>&nbsp;
<input type="number" value="1" min="1" max="200" id="number_of_couples" class="input-sm" required />
<button type="button" id="submit_number_of_couples" class="btn btn-default btn-mini">
<?= Yii::t('default', 'Submit') ?>
</button>
</p>

<?php echo Html::beginForm('', 'post', [] ); ?>
<table class="table table-striped table-hover">
  <tr>
    <th>#</th>
    <th><?= Yii::t('default', 'Male') ?></th>
    <th><?= Yii::t('default', 'Female') ?></th>
    <th><?= Yii::t('default', 'PAR_NATJEC_ATTR_BROJ_PARA') ?></th>
    <th><?= Yii::t('default', 'Year') ?></th>
  </tr>
  <?php 
	$end=(isset($_GET["number_of_couples"])) ? $_GET["number_of_couples"] : 50;
	for($i=0;$i<$end;$i++): 
	$j=$i+1
  ?>
  <tr>
    <td><?= $j ?></td>
    <td><?= Html::activeDropDownList($Couple, "[$i]male",Pigeon::dropDownListOnlyMaleOnlyFemale(Pigeon::MALE_PIGEON), ['placeholder'=>Yii::t('default', 'LEGLO_UZGOJNI_ATTR_BROJ_PRSTENA'), 'class'=>'form-control'] ) ?></td>
    <td><?= Html::activeDropDownList($Couple, "[$i]female",Pigeon::dropDownListOnlyMaleOnlyFemale(Pigeon::FEMALE_PIGEON), ['placeholder'=>Yii::t('default', 'LEGLO_UZGOJNI_ATTR_BROJ_PRSTENA'), 'class'=>'form-control'] ) ?></td>
    <td><?= Html::activeTextInput($Couple, "[$i]couplenumber",['class'=>'form-control', 'required'=>true] ) ?></td>
    <td><?= Html::activeInput('number', $Couple, "[$i]year",['class'=>'form-control', 'required'=>true, 'min'=>1900, 'max'=>date("Y")+5] ) ?></td>
  </tr>
<?php endfor; ?>

</table>
<br>
<?= Html::submitButton(Yii::t('default', 'Create'), ['class' => 'btn btn-success btn-cons']) ?>
<?php echo Html::endForm() ?>