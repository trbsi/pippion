<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Auction */

$this->title = Yii::t('default', 'Update your auction');
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Auctions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('default', 'Update');
?>
<?php
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'View auction'),  'url' => ['view', 'id' => $model->ID]];
$menuItems[] = ['label' => Yii::t('default', 'Create auction'),  'url' => ['create',]];
$menuItems[] = ['label' => Yii::t('default', 'My auctions'),  'url' => ['index']];
$menuItems[] = ['label' => Yii::t('default', 'All auctions'),  'url' => ['opened']];
?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4><?php echo Yii::t('default', 'Update your auction'); ?></h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <?= $this->render('_form_update', [
				'model' => $model,
				'images' => $images,
				'pigeon'=>$pigeon,
			]) ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
