<?php
use yii\helpers\Html;
?>
<div class="form-group"> <br />
  <h3>
    <?= Yii::t('default', 'Image') ?>
  </h3>
  <strong><?php echo Yii::t('default', 'Maximum mb per image', ['0'=>\Yii::$app->params['maxImageSizeOnPippion']]) ?></strong>
  <div class="controls"> <?php echo Html::fileInput('auction_images[]',null, ['class'=>'form-control js-file-validation-image', 'multiple'=>true, 'accept'=>'image/*']); ?> </div>
</div>
