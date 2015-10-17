<?php

use yii\helpers\Html;
use backend\models\RacingTableCategory;
use backend\models\RacingTable;

/* @var $this yii\web\View */
/* @var $model backend\models\RacingTableCategory */

$this->title = Yii::t('default', 'Add category for racing table');
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Racing Table Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'Manage RacingTableCategory'),  'url' => ['index']];
?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4><?php echo Yii::t('default', 'Create RacingTableCategory') ?></h4>
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
