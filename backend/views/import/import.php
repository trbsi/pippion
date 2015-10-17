<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\search\PigeonSearch;
use backend\models\Pigeon;
use yii\grid\DataColumn;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\PigeonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Import";
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('/_alert'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4><?php echo $this->title; ?></h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
			<?= Html::beginForm('', 'post', ['enctype'=>'multipart/form-data' ,'id'=>'import_data_form'] ) ?>
            <p><?= $screenshots ?></p>
            
            <p><strong><?= Yii::t('default', 'Pigeon Planner Database') ?></strong></p>
            <?= Html::fileInput('database', null, ['id'=>'database_file'] ); ?>
            <br>
            <?= Html::submitButton(Yii::t('default', 'Submit'), ['class'=>'btn btn-cons btn-primary submit_file_btn', 'name'=>'submit']); ?>
			<?= Html::endForm() ?>
            <script>
			$('#import_data_form').submit(function() 
			{
				$(".submit_file_btn").hide();
			});
			<?= $allowedFile ?>
            </script>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
