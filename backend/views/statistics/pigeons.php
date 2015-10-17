<?php
use backend\models\Statistics;
use yii\helpers\Url;
$this->title = Yii::t('default', 'Pigeon Statistics');
?>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4>
          <?= Yii::t('default', 'My pigeons'); ?>
        </h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
              <h4>
                <?= Yii::t('default', 'Sex'); ?>
              </h4>
              <img src="<?= Url::to(['/statistics/pigeons-statistics', 'type'=>Statistics::TYPE_PIGEON_SEX]); ?>"  class="center-block"/>
              <hr />
              <h4>
                <?= Yii::t('default', 'Year'); ?>
              </h4>
              <img src="<?= Url::to(['/statistics/pigeons-statistics', 'type'=>Statistics::TYPE_PIGEON_YEAR]); ?>"  class="center-block"/>
              <hr />
              <h4>
                <?= Yii::t('default', 'Country'); ?>
              </h4>
              <img src="<?= Url::to(['/statistics/pigeons-statistics', 'type'=>Statistics::TYPE_PIGEON_COUNTRY]); ?>"  class="center-block"/>
              <hr />
              <h4>
                <?= Yii::t('default', 'Status'); ?>
              </h4>
              <img src="<?= Url::to(['/statistics/pigeons-statistics', 'type'=>Statistics::TYPE_PIGEON_STATUS]); ?>"  class="center-block"/>
              <hr />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
