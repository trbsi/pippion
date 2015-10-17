<?php
use yii\helpers\Html;
?>
<div class="row">
<div class="col-md-12">
  <div class="grid solid red">
    <div class="grid-title">
      <h4><?php echo Yii::t('default', 'Bid failed'); ?></h4>
      <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
    </div>
    <div class="grid-body">
		<p>
			<?php
			//http://www.yiiframework.com/doc-2.0/yii-base-model.html#getErrors()-detail
			foreach($model->getErrors() as $attribute)
			{
				foreach($attribute as $error)
				{
					echo $error."<br>";
				}
			}
			?>
            <br>
            <?php echo Html::a(Yii::t('default', 'Go back'), ['view', 'id'=>$model->IDauction], ['class'=>'btn btn-cons btn-white']) ?>
		</p>
    </div>
  </div>
</div>
</div>
