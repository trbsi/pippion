<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Breeder */

$this->title = Yii::t('default', 'UZG_UPDATE_TITLE');
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Breeders'), /*'url' => ['index']*/];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('default', 'UZG_LINK_UPDATE');
?>
<?php
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'UZG_LINK_VIEW'),  'url' => ['view', 'id' => $model->IDuser]];
?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>
<script>
$(document).ready(function(e) 
{
    var name_of_breeder=$("#breeder-name_of_breeder");
	var town=$("#breeder-town");
	var address=$("#breeder-address");
	var tel1=$("#breeder-tel1");
	var email1=$("#breeder-email1");
	
	if(name_of_breeder.val()=="-")
		name_of_breeder.val("");
		
	if(town.val()=="-")
		town.val("");
		
	if(address.val()=="-")
		address.val("");
		
	if(tel1.val()=="-")
		tel1.val("");
		
	if(email1.val()=="-")
		email1.val("");
});
</script>

<!-- BEGIN BASIC FORM ELEMENTS-->
<div class="row">
    <div class="col-md-12">
      <div class="grid simple">
        <div class="grid-title no-border">
          <h4><?php echo Yii::t('default', 'UZG_UPDATE_UZGAJIVAC')." "; echo $model->name_of_breeder; ?></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body no-border"> <br>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
				<?php
                if(isset($_GET['err']))
                {
                    echo '<div class="alert alert-error">'.Yii::t('default', 'Niste ispunili obavezan polja').'</div>';
                }
                ?>

				<?= $this->render('_form', [
                    'model' => $model,
                ]) ?>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<!-- END BASIC FORM ELEMENTS-->	 


