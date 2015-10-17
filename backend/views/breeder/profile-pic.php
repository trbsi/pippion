<?php
//model - BreederImage
use backend\helpers\ExtraFunctions;
use backend\models\Breeder;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = Yii::t('default', 'Profile picture');

?>
<?= $this->render('/_alert'); ?>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4><?= Yii::t('default', 'Profile picture'); ?></h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12"> 
          	<div class="alert alert-info"><?= Yii::t('default', 'Upload profile picture info'); ?></div>
			<?php $form = ActiveForm::begin(['options'=>["enctype"=>"multipart/form-data"]]); ?>
        
            <p><?= Html::fileInput("breeder_image_file", $value = null, $options = [] ); ?></p>
        
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-cons' : 'btn btn-primary btn-cons']) ?>
            </div>
        
            <?php ActiveForm::end(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
