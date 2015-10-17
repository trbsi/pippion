<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\helpers\ExtraFunctions;
use yii\grid\GridView;
use backend\models\RacingTable;
use backend\models\RacingTableCategory;
use backend\models\Pigeon;

$ExtraFunctions = new ExtraFunctions;
$RacingTable = new RacingTable;
$RacingTableCategory = new RacingTableCategory;
$Pigeon = new Pigeon;
/* @var $this yii\web\View */
/* @var $model backend\models\RacingTable */

$this->title = Yii::t('default', 'See all results of a pigeon');
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Racing Tables'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'Manage RacingTable'),  'url' => ['index']];
$menuItems[] = ['label' => Yii::t('default', 'Create RacingTable'),  'url' => ['create']];
?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>

<?= $this->render('/_alert'); ?>

<style> 
/*this is for header row just to make text of link smaller so table doesn't get wide
similar to extrastyle.css "table a", but there i'm wraping text for everytable
*/
.GridView-header a 
{
	font-size:12px;
} 
/*
column where are buttons, make it little wider
*/
.ActionColumn-css
{
	min-width:90px;
}
</style>
<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
          
          	<?php 
			$form=ActiveForm::begin(
			[
				'method'=>'get', 
				'action'=>Url::to(['download-print', 'target'=>$_GET['target'], 'cid'=>(isset($_GET['cid'])) ? $_GET['cid'] : false, 'pid'=>(isset($_GET['pid'])) ? $_GET['pid'] : false ]), 
				'options'=>
				[
					'target'=>'_blank',
				],
			]); 
			?>
            <div class="alert alert-success">
            	<?= $Pigeon->printDownloadRadioChoose() ?>
            </div>
            
            <?= Html::submitButton(Yii::t('default', 'Download/print racing table'), ['class'=>'btn btn-block btn-primary', ]) ?>
          	<?php ActiveForm::end() ?>
			<?php require "_view.php"; ?>			
			
			<?php /*
			echo DetailView::widget([
				'model' => $model,
				'attributes' => [
					'ID',
					'racing_date',
					'place_of_release',
					'distance',
					'participated_competitors',
					'participated_pigeons',
					'won_place',
					'IDcategory',
					'IDuser',
					'IDpigeon',
				],
			])*/ ?>


          </div>
        </div>
      </div>
    </div>
  </div>
</div>


