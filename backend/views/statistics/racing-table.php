<?php
use backend\models\Statistics;
use backend\models\Pigeon;
use backend\models\RacingTableCategory;
use backend\models\RacingTable;
use yii\helpers\Url;
use yii\helpers\Html;

$Pigeon = new Pigeon;
$this->title = Yii::t('default', 'Pigeon Statistics');
?>
<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4>
          <?= Yii::t('default', 'Racing Table'); ?>
        </h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <h4></h4>
            <div class="alert alert-success">
				<?= RacingTable::dependingOfCategoryDropDownRacingTable() ;?>
            </div>
            <?php if($sub_title!=NULL): ?>
			<h4><strong><?= $sub_title; ?></strong></h4>
            <br />
			<?php endif; ?>
            
            <?php if(isset($_GET["racing_table_cat"]) && isset($_GET["pigeon_number"])): ?>
            <div class="table-responsive">
            	<img src="<?= Url::to(['/statistics/racing-table-statistics', 'racing_table_cat'=>$_GET["racing_table_cat"], 'pigeon_number'=>$_GET["pigeon_number"]]); ?>" />
            </div>
            <?php endif; ?>
            
            <hr />
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
