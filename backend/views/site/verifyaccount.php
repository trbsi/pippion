<?php
$this->title=Yii::t('default', 'Verify account');
use yii\helpers\Url;
use yii\helpers\Html;
use backend\models\Breeder;
?>
<?= $this->render('/_alert'); ?>

<div class="row">
  <div class="col-md-12">
    <div class="panel-group" id="accordion" data-toggle="collapse">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title"> <a class="" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"> <?php echo Yii::t('default', 'Why should I verify'); ?> </a> </h4>
        </div>
        <div id="collapseTwo" class="panel-collapse collapse in">
          <div class="panel-body"> <?php echo Yii::t('default', 'Why should I verify info'); ?> </div>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"> <?php echo Yii::t('default', 'What is verification'); ?> </a> </h4>
        </div>
        <div id="collapseOne" class="panel-collapse collapse">
          <div class="panel-body"> <?php echo Yii::t('default', 'What is verification info'); ?> </div>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree"> <?php echo Yii::t('default', 'How can I verify'); ?> </a> </h4>
        </div>
        <div id="collapseThree" class="panel-collapse collapse">
          <div class="panel-body"> <?php echo Yii::t('default', 'How can I verify info'); ?> <br>
            <br />
            <a href="<?= Yii::getAlias('@web')?>/images/IDfront.jpg"  rel="group1_colorbox" class="group1_colorbox"><img src="<?= Yii::getAlias('@web')?>/images/IDfront.jpg" height="192" width="300" /></a> <a href="<?= Yii::getAlias('@web')?>/images/IDback.jpg"  rel="group1_colorbox" class="group1_colorbox"><img src="<?= Yii::getAlias('@web')?>/images/IDback.jpg" class="group1" height="192" width="300" /></a> <br />
            <br />
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row m-b-20">
  <div class="col-md-12">
    <div class="grid simple no-border">
      <div class="grid-body">
      <?= Html::beginForm(['/site/verify-acc'], 'post', ['enctype' => 'multipart/form-data']) ?>
          <label>Max 2MB</label>
          <input name="file" type="file" class="js-file-validation-image" accept="image/*" required>
          <br>
          <input type="submit" name="submit" value="Submit" class="btn btn-success btn-cons">
        <?= Html::endForm() ?>
      </div>
    </div>
  </div>
</div>
