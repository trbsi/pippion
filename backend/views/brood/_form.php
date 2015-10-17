<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\Pigeon;
use backend\models\CoupleRacing;
/* @var $this yii\web\View */
/* @var $model backend\models\BroodBreeding */
/* @var $form yii\widgets\ActiveForm */
$Pigeon = new Pigeon;
$CoupleRacing = new CoupleRacing;
$dropDownListPigeonCountry=$Pigeon->dropDownListPigeonCountry();
?>
<?php $form = ActiveForm::begin(); ?>
<?php 
//AKO KORISNIK ODE NA DNEVNIK LEŽENJA I IZBAERE PAR I IZLISTAJU MU SE LEGLA I STISNE NA GUMB "Dodaj novo leglo" TADA ĆE U /leglonatjecateljski/create BITI POSLAN GET[idpar] I BITI ĆE ISPISAN SAMO JEDAN PAR, TAJ KOD KOJEG ŽELI DODAT LEGLO
// NO AKO ODE SAMO NA /leglonatjecateljski/create TADA NEĆE BITI GET I MORAT ĆE BITI ISPISANI SVI PAROVI TAKO DA ĆE U FUNKCIJU KAO VRIJEDNOST POSLATI NULL 
?>

<?php
//$_GET["idcouple"] - when user list broods in couple-racing/hatching-diary(couple-breeding/hatching-diary) there is button "Add new brood" so user can add new brood for chosen couple

$fill=NULL;
if(isset($_GET["idcouple"]))
{
	$fill=(int)$_GET["idcouple"];
}

//on update IDcouple is not empty. $model0 or $model1 returns the same IDcouple
if(!empty($model0->IDcouple))
{
	$fill=$model0->IDcouple;
}

?>
<?= $CoupleRacing->chooseCouplesDropDown($_MODEL_CHOOSE, $fill); ?>
<div class="alert alert-warning"><?= Yii::t('default', 'Time format ymd'); ?></div>
<div class="table-responsive">
<table class="table table-bordered table-striped">
  <tr>
    <td>
    	<?= $form->field($model0, 'firstegg')->textInput(['class'=>'form-control js-datepicker', 'value'=>isset($model0->firstegg)?$model0->firstegg:date("Y-m-d")]) ?>
    </td>
    <td>
    	<?= $form->field($model0, 'hatchingdate')->textInput(['class'=>'form-control js-datepicker',  'value'=>isset($model0->hatchingdate)?$model0->hatchingdate:'2000-01-01']) ?>
    </td>
    <td>
		<?= $form->field($model0, '[0]IDcountry')->dropDownList($dropDownListPigeonCountry, ['class'=>'form-control']) ?>
    </td>
    <td>
    	<?= $form->field($model0, '[0]ringnumber')->textInput(['maxlength' => 20, 'class'=>'form-control']) ?>
    </td>
    <td>
    	<?= $form->field($model0, '[0]color')->textInput(['maxlength' => 30, 'class'=>'form-control']) ?>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>
		<?= $form->field($model1, '[1]IDcountry')->dropDownList($dropDownListPigeonCountry, ['class'=>'form-control']) ?>
    </td>
    <td>
    	<?= $form->field($model1, '[1]ringnumber')->textInput(['maxlength' => 20, 'class'=>'form-control']) ?>
    </td>
    <td>
    	<?= $form->field($model1, '[1]color')->textInput(['maxlength' => 30, 'class'=>'form-control']) ?>
    </td>
  </tr>
</table>
</div>
<?= Html::submitButton($model0->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $model0->isNewRecord ? 'btn btn-success btn-cons' : 'btn btn-primary btn-cons']) ?>

<?php ActiveForm::end(); ?>



