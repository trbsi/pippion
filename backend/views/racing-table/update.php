<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\RacingTable */

$this->title = Yii::t('default', 'Update current result');
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Racing Tables'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('default', 'Update');
?>
<?php
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'View this table'),  'url' => ['view', 'target'=>'both', 'pid'=>$model->IDpigeon, 'cid'=>$model->IDcategory]];
$menuItems[] = ['label' => Yii::t('default', 'Manage RacingTable'),  'url' => ['index']];
$menuItems[] = ['label' => Yii::t('default', 'Create RacingTable'),  'url' => ['create']];

?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4><?php echo Yii::t('default', 'Update Racing Result'); ?></h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12"> 
          <?= $this->render('_form_update', [
			'model' => $model,
		]) ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
