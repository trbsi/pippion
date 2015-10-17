<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php echo Html::beginForm(Url::to('/quick-insert/create'), $method = 'post', [] ); ?>
<p><span class="label label-info"><?php echo Yii::t('default', 'STATUS_STVORI_STATUS') ?></span></p>
<?php for($i=0;$i<10;$i++): ?>
<?= Html::activeTextInput( $Status, "[$i]status",['placeholder'=>($i+1).". ".Yii::t('default', 'STATUS_STATUS')] ) ?>&nbsp;
<?php endfor; ?>

<p><?= Html::submitButton(Yii::t('default', 'Create'), ['class' => 'btn btn-success btn-cons']) ?></p>
<?php echo Html::endForm() ?>