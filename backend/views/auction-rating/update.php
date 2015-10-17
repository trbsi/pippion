<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\AuctionRating */

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Auction Ratings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('default', 'Update');
?>
<?php
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'View auction'),  'url' => ['/auction/view', 'id' => $model->IDauction]];
?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4><strong><?php echo $title_h4; ?></strong></h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="table-responsive">
			<?= $this->render('_form', [
                'model' => $model,
            ]) ?>

    		</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
