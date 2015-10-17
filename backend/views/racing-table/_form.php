<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\Pigeon;
use backend\models\RacingTable;
use backend\models\RacingTableCategory;


/* @var $this yii\web\View */
/* @var $model backend\models\RacingTable */
/* @var $form yii\widgets\ActiveForm */

$RacingTableCategory = new RacingTableCategory;
$Pigeon = new Pigeon;
?>
<style>
.font-change td, .font-change th
{
	font-size:12px;
	font-weight:bold;
}
</style>


<?php echo Html::a(Yii::t('default', 'Add pigeon'), Url::to('/pigeon/create'), ['target'=>'_blank', 'class'=>'btn btn-default btn-small']); ?>
&nbsp;&nbsp;
<?php echo Html::a(Yii::t('default', 'Add category'), Url::to('/racing-table-category/create'), ['target'=>'_blank', 'class'=>'btn btn-default btn-small']); ?>
<br />
<br />
<br />
<?php $form = ActiveForm::begin(); ?>
<?= $form->field($model, 'IDcategory')-> dropDownList($RacingTableCategory->dropDownListCategory()) ?>

<div class="alert alert-info">
	<?= $Pigeon->maleFemaleDropDownChoose(); ?>
    <div style="display:none" id="div-male">
        <?php
        echo $Pigeon->dependentDropDownFather();
        ?>
    </div>
    <div style="display:none" id="div-female">
        <?php
        echo $Pigeon->dependentDropDownMother();
        ?>
    </div>
</div>


<div class="alert alert-warning">
<?= Yii::t('default', 'Time format ymd')?>
</div>

<div class="table-responsive">
<table class="table table-bordered table-stripeed table-hover">
  <tr class="font-change">
    <th></th>
    <th></th>
    <th></th>
    <td colspan="2" class="create-participated"><?php echo strtoupper(Yii::t('default', 'RTSudjelovalo')); ?></td>
    <td colspan="1" class="create-won" ><?php echo strtoupper(Yii::t('default', 'RTOsvojeno')); ?></td>
    <th></th>
  </tr>
  <tr class="font-change">
    <th><?php echo Yii::t('default', 'Datum'); ?></th>
    <th><?php echo Yii::t('default', 'Mjesto Pustanja'); ?></th>
    <th><?php echo Yii::t('default', 'Udaljenost'); ?></td>
    <td class="create-participated"><?php echo strtoupper(Yii::t('default', 'Sud Natjecateljsa')); ?></td>
    <td class="create-participated"><?php echo strtoupper(Yii::t('default', 'Sud Golubova')); ?></td>
    <td class="create-won"><?php echo strtoupper(Yii::t('default', 'Osv Mjesto')); ?></td>
    <th><img src="/images/x.gif"></td>
  </tr>
  <tr  class="insertAfter">
    <td>
        <?= $form->field($model, '[0]racing_date', ['template' => "{input}\n{hint}\n{error}"])->textInput(['class'=>'js-datepicker' ,'required'=>true, 'placeholder'=>Yii::t('default', 'Example: 2013-10-29')]) ?>
    </td>
    <td>
        <?= $form->field($model, '[0]place_of_release', ['template' => "{input}\n{hint}\n{error}"])->textInput(['maxlength' => 100,'required'=>true,]) ?>
    </td>
    <td>
        <?= $form->field($model, '[0]distance', ['template' => "{input}\n{hint}\n{error}"])->input('number',['style'=>'width:100px;', 'required'=>true, 'min'=>0, 'step'=>'any', 'placeholder'=>'xxx.xxx']) ?>

    </td>
    <td>
        <?= $form->field($model, '[0]participated_competitors', ['template' => "{input}\n{hint}\n{error}"])->input('number', ['style'=>'width:100px;', 'required'=>true, 'min'=>0]) ?>
    </td>
    <td>
        <?= $form->field($model, '[0]participated_pigeons', ['template' => "{input}\n{hint}\n{error}"])->input('number', ['style'=>'width:100px;', 'required'=>true, 'min'=>0]) ?>
    </td>
    <td>
        <?= $form->field($model, '[0]won_place', ['template' => "{input}\n{hint}\n{error}"])->input('number', ['style'=>'width:100px;', 'required'=>true, 'min'=>0]) ?>
    </td>
    <td></td>
  </tr>
</table>
</div>

<div class="form-group">
     <input type="button" value="<?php echo Yii::t('default', 'Dodaj Red');?>" onclick="insertRow()" class="btn btn-warning btn-cons" />
	<?= Html::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-cons' : 'btn btn-primary btn-cons']) ?>
</div>

<?php ActiveForm::end(); ?>
<?php
$jsInsertRow =
'
<tr class="insertAfter removeThisRow\'+i+\'">
  <td><div class="form-group field-racingtable-0-racing_date required">
      <input type="text" id="racingtable-\'+i+\'-racing_date" class="js-datepicker" name="RacingTable[\'+i+\'][racing_date]" required placeholder="pr.: 2013-10-29">
      <div class="help-block"></div>
    </div></td>
  <td><div class="form-group field-racingtable-0-place_of_release required">
      <input type="text" id="racingtable-\'+i+\'-place_of_release" class="form-control" name="RacingTable[\'+i+\'][place_of_release]" maxlength="100" required>
      <div class="help-block"></div>
    </div></td>
  <td><div class="form-group field-racingtable-0-distance required">
      <input type="number" id="racingtable-\'+i+\'-distance" class="form-control" name="RacingTable[\'+i+\'][distance]" style="width:100px;" required min="0" step="any" placeholder="xxx.xxx">
      <div class="help-block"></div>
    </div></td>
  <td><div class="form-group field-racingtable-0-participated_competitors required">
      <input type="number" id="racingtable-\'+i+\'-participated_competitors" class="form-control" name="RacingTable[\'+i+\'][participated_competitors]" style="width:100px;" required min="0">
      <div class="help-block"></div>
    </div></td>
  <td><div class="form-group field-racingtable-0-participated_pigeons required">
      <input type="number" id="racingtable-\'+i+\'-participated_pigeons" class="form-control" name="RacingTable[\'+i+\'][participated_pigeons]" style="width:100px;" required min="0">
      <div class="help-block"></div>
    </div></td>
  <td><div class="form-group field-racingtable-0-won_place required">
      <input type="number" id="racingtable-\'+i+\'-won_place" class="form-control" name="RacingTable[\'+i+\'][won_place]" style="width:100px;" required min="0">
      <div class="help-block"></div>
    </div></td>
  <td><a href="javascript:;" onClick="removeRow(\'+i+\')"><img src="/images/x.gif"></a></td>
</tr>

';

$jsInsertRow=str_replace(array("\n","\r","\t", "\\"),"",$jsInsertRow)
?>
<script>
var i=1;
function insertRow()
{
	var last=$(".insertAfter").last();//get the last row and insert after it
	$('<?php echo $jsInsertRow; ?>').insertAfter(last);
	i++;
		
	//date picker for input
	//it has to be inside this function because when I insert new row it has to apply this plugin to inputs with class "js-datepicker"
	//you can also find it in /js/pippion.js
	initalizeDateTimePicker();
}

function removeRow(row)
{
	$('.removeThisRow'+row).remove();
}
</script>

