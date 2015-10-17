<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = Yii::t('default', 'About us');
?>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4><?php echo Yii::t('default', 'About us'); ?></h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border">
        <h3><a href="<?= Url::to(['/site/what-is-pippion#team']) ?>" target="_blank" style="text-decoration:underline"><?= Yii::t('default', 'The team') ?></a></h3>      
        <blockquote class="margin-top-20">Pippion is Registered company at Commercial Court in Osijek<br>
        Equity capital of 10,00 HRK paid in full.<br>
        Board Member: <a href="https://hr.linkedin.com/in/dariotrbovic" target="_blank"><u>Dario Trbović</u></a>, <a href="https://hr.linkedin.com/in/ivanjurlina" target="_blank"><u>Ivan Jurlina</u></a>, <a href="https://hr.linkedin.com/in/mariohribar" target="_blank"><u>Mario Hribar</u></a><br>
        Account number (IBAN): HR87 2340 0091 1107 3363 5<br>
        SWIFT CODE: PBZGHR2X </blockquote>
      </div>
    </div>
  </div>
</div>
