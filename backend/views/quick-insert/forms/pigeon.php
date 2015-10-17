<?php
use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\Pigeon;
?>

<div class="alert alert-info">
  <?= Yii::t('default', 'Pigeon country info')?>
</div>
<span class="label label-info">
<?= Yii::t('default', 'How many couples you want to insert')?>
</span>&nbsp;
<input type="number" value="1" min="1" max="200" id="number_pigeons" class="input-sm" required />
<button type="button" id="submit_pigeon_number" class="btn btn-default btn-mini">
<?= Yii::t('default', 'Submit') ?>
</button>
</p>
<?php echo Html::beginForm(Url::to('/quick-insert/create'), $method = 'post', [] ); ?>
<table class="pigeons table-striped table-hover">
  <tr>
    <th>#</th>
    <th><?=Yii::t('default', 'GOLUB_BROJ_GOLUBA') ?></th>
    <th><?= Yii::t('default', 'GOLUB_SPOL') ?>
    </th>
    <th><?= Yii::t('default', 'GOLUB_BOJA') ?></th>
    <th><?= Yii::t('default', 'GOLUB_RASA') ?></th>
    <th><?= Yii::t('default', 'GOLUB_IME') ?></th>
    <th><?= Yii::t('default', 'GOLUB_GODINA') ?></th>
    <th><?= Yii::t('default', 'GOLUB_DRZAVA') ?></th>
    <th><?= Yii::t('default', 'GOLUB_STATUS') ?></th>
    <th><?= Yii::t('default', 'GOLUB_OTAC') ?></th>
    <th><?= Yii::t('default', 'GOLUB_MAJKA') ?></th>
  </tr>
  <?php 
		(isset($_GET["number_pigeons"]))?$end=$_GET["number_pigeons"]:$end=50;
		for($i=0;$i<$end;$i++): 
  		$j=$i+1
	  ?>
  <tr>
    <td><?= $j ?></td>
    <td><?= Html::activeTextInput( $Pigeon, "[$i]pigeonnumber",['class'=>'input-sm', 'required'=>true] ) ?></td>
    <td><?= Html::activeDropDownList( $Pigeon, "[$i]sex", Pigeon::dropDownListSex(), ['class'=>'input-sm'] ) ?></td>
    <td><?= Html::activeTextInput( $Pigeon, "[$i]color", ['class'=>'input-sm'] ) ?></td>
    <td><?= Html::activeTextInput( $Pigeon, "[$i]breed",  ['class'=>'input-sm'] ) ?></td>
    <td><?= Html::activeTextInput( $Pigeon, "[$i]name", ['class'=>'input-sm'] ) ?></td>
    <td><?= Html::activeInput('number', $Pigeon, "[$i]year", ['min'=>1900, 'max'=>date("Y")+5, 'required'=>true, 'class'=>'input-sm'] ) ?></td>
    <td><?= Html::activeDropDownList( $Pigeon, "[$i]IDcountry", Pigeon::dropDownListPigeonCountry(), ['class'=>'input-sm', 'required'=>true] ) ?></td>
    <td><?= Html::activeDropDownList( $Pigeon, "[$i]IDstatus", Pigeon::dropDownListStatus(),  ['class'=>'input-sm', 'required'=>true] ) ?></td>
    <td><?= Html::textInput("father[$i]", '',['class'=>'input-sm'] ) ?></td>
    <td><?= Html::textInput("mother[$i]", '',['class'=>'input-sm'] ) ?></td>
  </tr>
  <?php endfor; ?>
</table>
<br />
<div class="form-group">
  <?= Html::submitButton(Yii::t('default', 'Create'), ['class' => 'btn btn-success btn-cons']) ?>
</div>
<?php echo Html::endForm(); ?> 