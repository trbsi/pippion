<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\BreederResults */

$this->title = Yii::t('default', 'UZGAJIVAC_REZ_UPDATE_TITLE');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Breeder Results'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

$menuItems=[];
$menuItems[] = ['label' => Yii::t('default', 'UZGAJIVAC_REZ_LINK_ADMIN'),  'url' => ['index']];
$menuItems[] = ['label' => Yii::t('default', 'UZGAJIVAC_REZ_LINK_CREATE'),  'url' => ['create']];
$menuItems[] = ['label' => Yii::t('default', 'UZGAJIVAC_REZ_LINK_VIEW'),  'url' => ['view', 'id' => $model->ID]];

?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>

<div class="row">
    <div class="col-md-12">
      <div class="grid simple">
        <div class="grid-title no-border">
          <h4><?php echo Yii::t('default', 'UZGAJIVAC_REZ_UPDATE_H1'); ?></h4>
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
