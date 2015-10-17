<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\PigeonData */

$this->title = Yii::t('default', 'PODACI_GOLUB_CREATE_TITLE');
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Pigeon Datas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'PODACI_GOLUB_LINK_ADMIN'),  'url' => ['index']];
?>

<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4><?php echo Yii::t('default', 'PODACI_GOLUB_CREATE_DATA') ?></h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12"> 
          
          <?= $this->render('_form', [
			'model' => $model,
		]) ?>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
