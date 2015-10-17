<?php

use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model backend\models\Auction */

$this->title =Yii::t('default', 'Pigeons have arrived');
if($error==true)
{
	$class="red";
	$msg=Yii::t('default', 'There was an error');
}
else
{
	$class="green";
	$msg=Yii::t('default', 'Pigeon arrived info');
}
?>

<div class="row">
  <div class="col-md-12">
    <div class="tiles <?=$class?> m-b-10">
      <div class="tiles-body">
        <div class="controller"> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        <br>
        <p>
        <span style="font-size:14px"><?= $msg ?></span>
        </p>
        <p>
        <a href="<?=Url::to(['/auction/view', 'id'=>$id])?>" class="btn btn-white btn-cons"><?= Yii::t('default', 'See auction') ?></a>
        </p>
      </div>
    </div>
  </div>
</div>
