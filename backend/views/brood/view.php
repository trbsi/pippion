<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\helpers\ExtraFunctions;
use backend\models\CoupleRacing;
/* @var $this yii\web\View */
/* @var $model backend\models\BroodBreeding */

$ExtraFunctions = new ExtraFunctions;
$CoupleRacing = new CoupleRacing;

if($_MODEL_CHOOSE=="BroodRacing")
{
	$this->title = Yii::t('default', 'LEGLO_NATJEC_VIEW_TITLE');
}
else if($_MODEL_CHOOSE=="BroodBreeding")
{
	$this->title = Yii::t('default', 'LEGLO_UZGOJNI_VIEW_TITLE');
}

$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Brood Breedings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'LEGLO_NATJEC_LINK_UPDATE'),  'url' => ['update', 'id' => $model->ID]];
$menuItems[] = ['label' => Yii::t('default', 'LEGLO_NATJEC_LINK_DELETE'),  'url' => ['delete', 'id' => $model->ID], 
				'linkOptions'=>
					[
						'data' => 
						[
							'confirm' => Yii::t('default', 'Are you sure you want to delete this item?'),
							'method' => 'post',
						],
					]
				];
$menuItems[] = ['label' => Yii::t('default', 'LEGLO_NATJEC_LINK_CREATE'),  'url' => ['create']];
$menuItems[] = ['label' => Yii::t('default', 'LEGLO_NATJEC_LINK_ADMIN'),  'url' => ['index']];
?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4>&nbsp;</h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12"> 
          	<div class="table-responsive">
                <table class="table table-bordered" align="center">
                  <thead>
                    <tr>
                      <th colspan="5" align="center"><span style="font-size:16px"><?php echo Yii::t('default', 'LEGLO_NATJEC_VIEW_H1'); ?> - <?= 	$CoupleRacing->formatCouple($model, $_MODEL_CHOOSE);; ?></span></th>
                    </tr>
                    <tr>
                      <th><?= Yii::t('default', 'LEGLO_NATJEC_ATTR_PRVO_JAJE') ?></th>
                      <th><?= Yii::t('default', 'LEGLO_NATJEC_ATTR_DATUM_LEZENJA') ?></th>
                      <th><?= Yii::t('default', 'LEGLO_NATJEC_ATTR_DRZAVA') ?></th>
                      <th><?=  Yii::t('default', 'LEGLO_NATJEC_ATTR_BROJ_PRSTENA') ?></th>
                      <th><?= Yii::t('default', 'LEGLO_NATJEC_ATTR_BOJA') ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td align="center"><?= $ExtraFunctions->formatDate($model->firstegg); ?></td>
                      <td align="center"><?= $ExtraFunctions->formatDate($model->hatchingdate); ?></td>
                      <td align="center"><?= $model->relationIDcountry->country ?></td>
                      <td align="center"><?= $model->ringnumber ?></td>
                      <td align="center"><?= $model->color ?></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td></td>
                      <td align="center"><?= $model1->relationIDcountry->country ?></td>
                      <td align="center"><?= $model1->ringnumber ?></td>
                      <td align="center"><?= $model1->color ?></td>
                    </tr>
                  </tbody>
                </table>
          	</div><!-- table-responsive -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php /* echo DetailView::widget([
        'model' => $model,
		'view'=>'_view',
        'attributes' => [
            'ID',
            /*'IDD',
            'IDuser',
            'IDcouple',
            'firstegg',
            'hatchingdate',
            'IDcountry',
            'ringnumber',
            'color',
        ],
    ])*/ ?>
