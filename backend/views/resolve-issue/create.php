<?php

use yii\helpers\Html;
use backend\helpers\LinkGenerator;

/* @var $this yii\web\View */
/* @var $model backend\models\ResolveIssue */

$this->title = Yii::t('default', 'Resolve an issue');
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Resolve Issues'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="resolve-issue-create">
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
            	<?= LinkGenerator::auctionLink(Yii::t('default', 'See auction'), $id, ['class'=>'btn btn-primary']); ?>
                <br /> <br />
              <?= $this->render('_form', [
				'model' => $model,
				'modelResolveIssueReply' => $modelResolveIssueReply,
			]) ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
