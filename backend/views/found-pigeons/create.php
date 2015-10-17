<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\FoundPigeons */

$this->title = Yii::t('default', 'Add found pigeon');

$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Found Pigeons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'List Found Pigeons'),  'url' => ['public']];
$menuItems[] = ['label' => Yii::t('default', 'List of pigeons Ive found'),  'url' => ['index']];

?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>
<?= $this->render('/_alert'); ?>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4><?php echo Yii::t('default', 'Add found pigeon to the list'); ?></h4>
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
