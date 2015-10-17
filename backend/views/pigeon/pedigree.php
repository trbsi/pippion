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

$this->title = Yii::t('default', 'Create pedigree');
$this->params['breadcrumbs'][] = $this->title;
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'GOLUB_LINK_ADMIN'),  'url' => ['index']];
$menuItems[] = ['label' => Yii::t('default', 'GOLUB_LINK_CREATE'),  'url' => ['create']];
$Pigeon = new Pigeon;
?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>


<div class="row">
  <div class="col-md-12">
    <div class="grid simple ">
      <div class="grid-title no-border">
        <h4>
          <?= Yii::t('default', 'Create pedigree') ?>
        </h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border">
        <div class="row-fluid">
            <!--<div class="alert alert-warning"><?php echo Yii::t('default', 'Print Download Info'); ?></div>-->
		  <?php ActiveForm::begin() ?>
          <div class="alert alert-block alert-info col-md-12 col-lg-12 col-xs-12 text-center">
          	<div class="col-md-6 col-lg-6 col-xs-12">
                <?= $Pigeon->maleFemaleDropDownChoose() ?>
            </div>
			<div class="col-md-6 col-lg-6 col-xs-12">
            	<?= $Pigeon->printDownloadRadioChoose() ?>
                
            </div>
          </div>
          <div style="display:none" id="div-male">
            <?php
            echo $Pigeon->dependentDropDownFather();
			?>
          </div>
          <div style="display:none" id="div-female">
            <?php
            echo $Pigeon->dependentDropDownMother();
			?>
          </div>
		<?= Html::submitButton(Yii::t('default', 'Create pedigree'), ['class'=>'btn btn-primary btn-block btn-large'] ) ?>
          <?php ActiveForm::end() ?>
        </div>
      </div>
    </div>
  </div>
</div>
