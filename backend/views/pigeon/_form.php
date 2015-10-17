<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\models\Pigeon;
/* @var $this yii\web\View */
/* @var $model backend\models\Pigeon */
/* @var $form yii\widgets\ActiveForm */

$Pigeon = new Pigeon;
$dropDownListPigeonCountry=$Pigeon->dropDownListPigeonCountry();
$dropDownListStatus=$Pigeon->dropDownListStatus();
?>
<?php $form = ActiveForm::begin(['options'=>["enctype"=>"multipart/form-data"]]); ?>

<div class="row">
  <div class="col-md-12">
    <ul class="nav nav-tabs" id="tab-01">
      <li class="active"><a href="#tabs-1"><?php echo Yii::t('default', 'GOLUB_GOLUB'); ?></a></li>
      <li><a href="#tabs-2"><?php echo Yii::t('default', 'GOLUB_OTAC'); ?></a></li>
      <li><a href="#tabs-3"><?php echo Yii::t('default', 'GOLUB_MAJKA'); ?></a></li>
    </ul>
    <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
    <div class="tab-content">
      <div class="tab-pane active" id="tabs-1">
        <div class="row column-seperation">
          <div class="col-md-12">
            <?= $form->field($model, 'IDcountry')->dropDownList($dropDownListPigeonCountry) ?>
            <?= $form->field($model, 'pigeonnumber')->textInput(['maxlength' => 40]) ?>
            <?= $form->field($model, 'year')->textInput(['maxlength' => 4])->input('number', ['max'=>date('Y')+5, 'min'=>1900]) ?>
            <?= $form->field($model, 'color')->textInput(['maxlength' => 40]) ?>
            <?= $form->field($model, 'sex')->dropDownList($Pigeon->dropDownListSex()); ?>
            <?= $form->field($model, 'breed')->textInput(['maxlength' => 50]) ?>
            <a href="<?= Url::to(['/status/create'])?>" class="label label-info" target="_blank"><?= Yii::t('default', 'STATUS_LINK_CREATE') ?></a>
            <?= $form->field($model, 'IDstatus')->dropDownList($dropDownListStatus) ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => 50]) ?>
            <p>
				<?=  Yii::t('default', 'Picture of a pigeon') ?>
                <?= Html::fileInput('pigeon_image', $value = null, $options = [] ) ?>
            </p>
            <p>
				<?=  Yii::t('default', 'The eye') ?>
                <?= Html::fileInput('eye_image', $name=null, $value = null, $options = [] ) ?>
            </p>

            <div class="form-group"> <span class="label label-important"><?php echo Yii::t('default', 'Important') ?></span> <span class="label label-important"><?php echo Yii::t('default', 'Save pigeon mother and father') ?></span> <br />
              <br />
              <?= Html::submitButton($model->isNewRecord ? Yii::t('default', 'Create') : Yii::t('default', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-block' : 'btn btn-primary btn-block']) ?>
            </div>
          </div>
        </div>
      </div>
      <div class="tab-pane" id="tabs-2">
        <div class="row">
          <div class="col-md-12">
              <div class="radio radio-primary alert alert-info" style="margin-bottom:20px;">
                <input id="dont_add_father" type="radio" name="radio_father" value="dont_add_father" checked="checked" >
                <label for="dont_add_father"><?php echo Yii::t('default', 'Dont add');?></label>
                
                <input id="radio_existing_father" type="radio" name="radio_father" value="radio_existing_father" >
                <label for="radio_existing_father"><?php echo Yii::t('default', 'Dodaj postojeceg oca');?></label>
                
                <input id="radio_new_father" type="radio" name="radio_father" value="radio_new_father" >
                <label for="radio_new_father"><?php echo Yii::t('default', 'Add new father');?></label>
              </div>
            
            <div id="postojeci_otac" style="display:none;"> <!-- jQuery id -->
				<?= $Pigeon->dependentDropDownFather() ?>
            </div>
            <!-- postojeci_otac -->
            
            <div id="novi_otac" style="display:none;"><!-- jQuery id -->
              <?= $form->field($father, 'IDcountry')->dropDownList($dropDownListPigeonCountry) ?>
              <?= $form->field($father, 'pigeonnumber')->textInput(['maxlength' => 40]) ?>
              <?= $form->field($father, 'year')->textInput(['maxlength' => 4])->input('number', ['max'=>date('Y')+5, 'min'=>1900]) ?>
              <?= $form->field($father, 'color')->textInput(['maxlength' => 40]) ?>
              <?= $form->field($father, 'sex')->dropDownList(['X'=>Yii::t('default', 'GOLUB_SPOL_M')]); ?>
              <?= $form->field($father, 'breed')->textInput(['maxlength' => 50]) ?>
              <a href="<?= Url::to(['/status/create'])?>" class="label label-info" target="_blank"><?= Yii::t('default', 'STATUS_LINK_CREATE') ?></a>
              <?= $form->field($father, 'IDstatus')->dropDownList($dropDownListStatus) ?>
              <?= $form->field($father, 'name')->textInput(['maxlength' => 50]) ?>
                <p>
                    <?=  Yii::t('default', 'Picture of a pigeon') ?>
                    <?= Html::fileInput('pigeon_image_father', $value = null, $options = [] ) ?>
                </p>
                <p>
                    <?=  Yii::t('default', 'The eye') ?>
                    <?= Html::fileInput('eye_image_father', $name=null, $value = null, $options = [] ) ?>
                </p>

            </div>
            <!-- novi_otac--> 
            
          </div>
        </div>
      </div>
      <div class="tab-pane" id="tabs-3">
        <div class="row">
          <div class="col-md-12">
            <div class="radio radio-primary alert alert-info" style="margin-bottom:20px;">
                <input id="dont_add_mother" type="radio" name="radio_mother" value="dont_add_mother"  checked="checked">
                <label for="dont_add_mother"><?php echo Yii::t('default', 'Dont add');?></label>
                
                <input id="radio_existing_mother" type="radio" name="radio_mother" value="radio_existing_mother" >
                <label for="radio_existing_mother"><?php echo Yii::t('default', 'Dodaj postojecu majku');?></label>
                
                <input id="radio_new_mother" type="radio" name="radio_mother" value="radio_new_mother" >
                <label for="radio_new_mother"><?php echo Yii::t('default', 'Add new mother');?></label>
              </div>
            <div id="postojeca_majka" style="display:none;">
				<?= $Pigeon->dependentDropDownMother() ?>
            </div>
            <!--postojeca_majka -->
            <div id="nova_majka" style="display:none;"><!-- jQuery id -->
              <?= $form->field($mother, 'IDcountry')->dropDownList($dropDownListPigeonCountry) ?>
              <?= $form->field($mother, 'pigeonnumber')->textInput(['maxlength' => 40]) ?>
              <?= $form->field($mother, 'year')->textInput(['maxlength' => 4])->input('number', ['max'=>date('Y')+5, 'min'=>1900]) ?>
              <?= $form->field($mother, 'color')->textInput(['maxlength' => 40]) ?>
              <?= $form->field($mother, 'sex')->dropDownList(['Y'=>Yii::t('default', 'GOLUB_SPOL_Z')]); ?>
              <?= $form->field($mother, 'breed')->textInput(['maxlength' => 50]) ?>
              <a href="<?= Url::to(['/status/create'])?>" class="label label-info" target="_blank"><?= Yii::t('default', 'STATUS_LINK_CREATE') ?></a>
              <?= $form->field($mother, 'IDstatus')->dropDownList($dropDownListStatus) ?>
              <?= $form->field($mother, 'name')->textInput(['maxlength' => 50]) ?>
                <p>
                    <?=  Yii::t('default', 'Picture of a pigeon') ?>
                    <?= Html::fileInput('pigeon_image_mother', $value = null, $options = [] ) ?>
                </p>
                <p>
                    <?=  Yii::t('default', 'The eye') ?>
                    <?= Html::fileInput('eye_image_mother', $name=null, $value = null, $options = [] ) ?>
                </p>
            </div>
            <!-- nova_majka--> 
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<?php ActiveForm::end(); ?>
