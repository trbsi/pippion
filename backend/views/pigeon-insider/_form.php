<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>
<script>
$(document).ready(function(e) 
{
	var hidden=false;
	$("#remove_competition").click(function()
	{
		$("#hide_competition").val("true");
		$(".hide-competition").each(function(index, element) 
		{
			$(element).css("display","none");
        });
	});
	
	$("#pigeon_insider_showcase_btn").click(function()
	{
		$("#pigeon_insider_showcase").toggle();	
	});
    
});
</script>
<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4><?php echo Yii::t('default', 'Pigeon Insider Maker'); ?></h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> 
      	<br>
        <button class="btn btn-block btn-info" type="button" id="pigeon_insider_showcase_btn"><span class="pull-left"><i class="fa fa-question"></i></span>What is Pigeon Insider?</button>
        <div id="pigeon_insider_showcase" class="text-center" style="display:none;"><img src="/images/pigeon_insider_almasi.jpg" class="img-responsive center-block" /></div>
		<br />
		<?php 
        if($tabularData!=false)
			echo '<div class="alert text-center"><a href="/temp/'.$tabularData["imgname"].'" target="_blank" class="btn btn-danger btn-cons">Your image</a></div>'; 
        ?>
        <br />
        <div class="row">
          <?php $form = ActiveForm::begin(['options'=>["enctype"=>"multipart/form-data"]]); ?>
        	<input type="hidden" name="hide_competition" id="hide_competition" value="false" />
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
              <div class="controls"> <?= $form->field($model, 'pigeon_name') ?> </div>
            </div>
          </div>
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
              <div class="controls"> <?= $form->field($model, 'pigeon_number') ?> </div>
            </div>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group"> 
              <div class="controls"> <?= $form->field($model, 'breeder_name') ?> </div>
            </div>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group"> 
              <div class="controls">  <?= $form->field($model, 'address') ?> </div>
            </div>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group"> 
              <div class="controls"> <?= $form->field($model, 'tel') ?></div>
            </div>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group"> 
              <div class="controls">  <?= $form->field($model, 'mob') ?> </div>
            </div>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
              <div class="controls"> <?= $form->field($model, 'email') ?> </div>
            </div>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group"> 
              <div class="controls"> <?= $form->field($model, 'web') ?> </div>
            </div>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
              <label class="form-label"><strong><?php echo Yii::t('default', 'GOLUB_GOLUB'); ?></strong></label> <br />
				<?php echo Yii::t('default', 'Picture size') ?> 450x450 (jpg,jpeg,png)
              <div class="controls">
                <input name="pigeon_pic" type="file" class="js-file-validation-image" accept="image/*">
              </div>
            </div>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
              <label class="form-label"><strong><?php echo Yii::t('default', 'The eye'); ?></strong></label> <br />
				<?php echo Yii::t('default', 'Picture size') ?> 250x250 (jpg,jpeg,png)
              <div class="controls">
                <input name="eye_pic" type="file" class="js-file-validation-image" accept="image/*">
              </div>
            </div>
          </div>
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover">
                <tr>
                  <th><?php echo Yii::t('default', 'Osv Mjesto'); ?></th>
                  <th><?php echo Yii::t('default', 'Mjesto Pustanja'); ?></th>
                  <th><?php echo Yii::t('default', 'Udaljenost'); ?> (km)</th>
                  <th><?php echo Yii::t('default', 'Total pigeons'); ?></th>
                  <th class="hide-competition"><?php echo Yii::t('default', 'Competition'); ?> <a href="javascript:;" id="remove_competition"><img src="/images/x.gif" /></a></th>
                </tr>
                <?php
					for($i=0;$i<10;$i++)
					{
						echo '
						  <tr> 
							<td>'.$form->field($model, "[$i]place", ['inputOptions'=>['value'=>$tabularData?$tabularData['place'][$i]:'']])->input('number').'</td>
							<td>'.$form->field($model,"[$i]release_place", ['inputOptions'=>['value'=>$tabularData?$tabularData['release_place'][$i]:'']]).'</td>
							<td>'.$form->field($model,"[$i]distance", ['inputOptions'=>['value'=>$tabularData?$tabularData['distance'][$i]:'']])->input('number').'</td>
							<td>'.$form->field($model,"[$i]total_pigeons", ['inputOptions'=>['value'=>$tabularData?$tabularData['total_pigeons'][$i]:'']])->input('number').'</td>
							<td class="hide-competition">'.$form->field($model,"[$i]competition", ['inputOptions'=>['value'=>$tabularData?$tabularData['competition'][$i]:'']]).'</td>
						  </tr>
						';
					}
				  ?>
                <tr>
                  <td><?= Html::submitButton(Yii::t('default', 'Submit'), ['class' => 'btn btn-primary btn-cons', 'name'=>'pigeon_insider_submit']) ?></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
              </table>
            </div>
          </div>
          <?php ActiveForm::end(); ?>
          
        </div>
      </div>
    </div>
  </div>
</div>
