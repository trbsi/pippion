<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use backend\helpers\ExtraFunctions;
use backend\models\BreederImage;
use backend\models\ResolveIssueReply;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model backend\models\ResolveIssue */

$this->title = Yii::t('default', 'Issue');
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Resolve Issues'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$IDuser=Yii::$app->user->getId();
?>
<style>
.form-actions-custom
{
	margin-right: -26px !important;
    margin-left: -26px !important;
	background-color: #f6f7f8;
    border: 0px;
    padding: 19px 20px 20px;
}
</style>
<?= $this->render('/_alert'); ?>

<div class="resolve-issue-view">
  <div class="row">
    <div class="col-md-12">
      <div class="grid simple no-border">
        <div class="grid-title no-border descriptive clickable">
          <h4 class="semi-bold">
            <?= Html::encode($this->title) ?>
          </h4>
           <p><a href="<?= Url::to(['/auction/view', 'id'=>$model->IDauction])?>" target="_blank" class="btn btn-primary"><?= Yii::t('default', 'View auction')?></a>
            </p>

          <p><span class="text-success bold">
            <?= Yii::t('default', 'Issue')." #".$model->ID ?>
            </span> -
            <?= Yii::t('default', 'Date Created UTC').": ".ExtraFunctions::formatDate($model->date_created, "ymd-his")?>
          </p>
          <!--<div class="actions"> <a class="view" href="javascript:;"><i class="fa fa-search"></i></a> <a class="remove" href="javascript:;"><i class="fa fa-times"></i></a> </div>-->
        </div>
        <div class="grid-body  no-border">
            <?php 
            echo ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_view_replies',
            ]);
            ?>
            
			<?php if($model->resolved==0): ?>
            <?php echo Html::beginForm(Url::to(['update', 'id'=>$model->ID]), $method = 'post', $options = [] )?>
            <div class="form-group">
              <label class="form-label"><i class="fa fa-reply"></i></label>
              <div class="input-with-icon  right"> <i class=""></i>
                <?= Html::activeTextInput($ResolveIssueReply, 'message', $options = ['class'=>'form-control'] ) ?>
              </div>
            </div>
            <?php echo Html::submitButton( Yii::t('default','Submit'), $options = ['class'=>'btn btn-cons btn-success'] ) ?>
            <?php echo Html::endForm() ?>
            
            <?php else:?>
            <div class="alert alert-info"><?= Yii::t('default', 'Case is closed') ?></div>
            <?php endif ?>
            
            
           <?php
		    //if 3 days passed he cannot reply anymore, if last IDuser is from seller then seller can close it, the same is with buyer
			if(
			(ExtraFunctions::dateDifference($lastReply->date_created, ExtraFunctions::currentTime("ymd-his"), "d") > 3 
			&& 
			$lastReply->IDuser==$IDuser
			&& $model->resolved==0)
			||
			(Yii::$app->user->identity->getIsAdmin() && $model->resolved==0)
			)
			{
				echo Html::beginForm(Url::to(['close']), $method = 'post', $options = ['onsubmit'=>'return areYouSure()'] );
				echo Html::submitButton( Yii::t('default','Close the case'), $options = ['class'=>'btn btn-cons btn-danger'] );
				echo Html::hiddenInput('IDresolveIssue', $model->ID, $options = [] );
				echo Html::hiddenInput('IDuser', $lastReply->IDuser, $options = [] );
				echo Html::endForm();
			}
			

			?>
        </div>
      </div>
    </div>
  </div>
</div>
