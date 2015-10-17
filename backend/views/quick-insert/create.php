<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<?= $this->render('//layouts/head'); ?>
<title><?php echo Yii::t('default', 'Quick insert') ?></title>
</head>

<body>
<div class="container-fluid">
  <?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\AdminNots */

$this->title = Yii::t('default', 'Quick insert');
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Quick insert'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
  <div class="quick-inert-create">
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple">
          <div class="grid-title no-border">
            <h4>
              <?= Html::encode($this->title) ?>
            </h4>
            <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
          </div>
          <div class="grid-body no-border"> <br>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <?= $this->render('/_alert'); ?>
                <div class="alert alert-info">
                  <?= Yii::t('default', 'Quick insert info') ?>
                </div>
                <?= $this->render('_form', 
					[
						'Pigeon'=>$Pigeon, 
						'Status'=>$Status, 
						'CoupleRacing'=>$CoupleRacing, 
						'CoupleBreeding'=>$CoupleBreeding ,
						'BroodRacing' => $BroodRacing,
						'BroodBreeding' => $BroodBreeding,
					]) ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
