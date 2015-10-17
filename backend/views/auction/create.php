<?php

use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model backend\models\Auction */

$this->title =Yii::t('default', 'Sell pigeons');
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Auctions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'My auctions'),  'url' => ['index']];
$menuItems[] = ['label' => Yii::t('default', 'All auctions'),  'url' => ['opened']];
?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>

<div class="current_time_fixed"> 
    <strong><?= Yii::t('default', 'Time') ?> (UTC):</strong> <br />
    <div class="showUTCTime" style="display:inline-block"><script>startTime()</script></div>
</div>

<!-- BEGIN BASIC FORM ELEMENTS-->
<div class="row">
    <div class="col-md-12">
      <div class="grid simple">
        <div class="grid-title no-border">
          <h4><?php echo Yii::t('default', 'Create Auction') ?></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body no-border"> <br>
            <p class="alert alert-danger">
                <?php echo Yii::t('default', 'Naplacivanje aukcije'); ?>
                <br />
                <a href="<?= Url::to(['/auction/rules#percentage-take']) ?>" target="_blank"><strong><?= Yii::t('default', 'Commission')?></strong></a>
            </p>
                <?= $this->render('_form', [
					'model' => $model,
					'pigeon' => $pigeon,
				]) ?>

            <?php //echo $this->renderPartial('_form', array('model'=>$model, 'slika'=>$slika, 'golub'=>$golub)); ?>
        </div>
      </div>
    </div>
  </div>
<!-- END BASIC FORM ELEMENTS-->	 


