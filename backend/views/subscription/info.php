<?php
use backend\models\Subscription;
use backend\helpers\ExtraFunctions;
use yii\helpers\Html;
$this->title=Yii::t('default','Subscriptions');
$Subscription = new Subscription;
$ExtraFunctions = new ExtraFunctions;
?>
<?=  $this->render('/_alert'); ?>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple ">
      <div class="grid-title no-border">
        <h4>&nbsp;</h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border">
        <div class="row-fluid"> <?php echo Yii::t('default', 'Why do I need to subscribe explained', ['0'=>\Yii::$app->params["subscriptionFee"]]); ?>
          <?php $lessThanMonthSubs=$Subscription->lessThanMonthSubs() ?>
          <br>
          <br>
          <div class="col-md-12 col-sm-12 spacing-bottom">
            <div class="tiles <?= $lessThanMonthSubs ?> added-margin">
              <div class="tiles-body">
                <?=  $Subscription->subscriptionInfo(); ?>
              </div>
            </div>
          </div>
          <div class="col-md-12 col-sm-12 spacing-bottom">
            <div class="tiles <?= $lessThanMonthSubs ?> added-margin">
              <div class="tiles-body">
                <div class="heading">
                  <?= Yii::t('default', 'Bank account title') ?>
                </div>
                <?= Yii::t('default', 'Bank last payment'); ?>
                <div class="well m-t-20" style="color:black">
                  <?=	$Subscription->bankAccountSubscription(); ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="tiles white p-t-15 p-l-15 p-r-15 p-b-25">
      <h2 class="text-center"><?= Yii::$app->params['subscriptionFee'].'$/'.Yii::t('default', 'Year') ?></h2>
         <?= $Subscription->subscribeButton(Subscription::SUBSCRIPTION_TYPE_YEAR); ?>
         <br />
    </div>
  </div>
  <div class="col-md-6">
    <div class="tiles white p-t-15 p-l-15 p-r-15 p-b-25">
      <h2 class="text-center"><?= Yii::$app->params['subscriptionFeeMonthly'].'$/'.Yii::t('default', 'Month') ?></h2>
        <?= $Subscription->subscribeButton(Subscription::SUBSCRIPTION_TYPE_MONTH); ?>
    </div>
  </div>
</div>
