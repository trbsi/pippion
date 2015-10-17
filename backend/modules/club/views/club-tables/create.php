<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\club\models\ClubTables */

$this->title = $model->club." | ".Yii::t('default', 'Add table');
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Club Tables'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="club-tables-create">
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
              <div class="form-group">
                <?= $this->render('_form', [
					'ClubTables' => $ClubTables,
				]) ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
