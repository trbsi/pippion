<?php
use yii\helpers\Url;
use yii\helpers\Html;
$this->title=Yii::$app->name . ' | '.Yii::t('default', 'Kontaktirajte nas');

?>
<?= $this->render('/_alert'); ?>
<!-- BEGIN BASIC FORM ELEMENTS-->

<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4><?php echo Yii::t('default', 'Kontaktirajte nas'); ?></h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
     <?= Html::beginForm(['/site/contact'], 'post', []) ?>

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="form-group">
                <div class="controls">
                  <input type="text" name="subject" class="form-control" placeholder="<?= Yii::t('default', 'Contact Subject') ?>" required>
                </div>
              </div>
              <div class="form-group">
                <div class="controls">
                  <textarea name="message" id="message" class="form-control" rows="4" placeholder="<?= Yii::t('default', 'Contact Enter your message') ?>" required></textarea>
                </div>
              </div>
              <div class="form-group">
                <div class="controls">
                  <button type="submit" class="btn btn-primary btn-lg btn-block" name="submit"><?php echo Yii::t('default', 'landing_contact Send') ?></button>
                </div>
              </div>
            </div>
          </div>
          <!-- row-->
        <?php echo Html::endForm() ?>
      </div>
    </div>
  </div>
</div>
<!-- END BASIC FORM ELEMENTS--> 
