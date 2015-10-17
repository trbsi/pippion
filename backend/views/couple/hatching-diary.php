<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use backend\models\Pigeon;
use backend\models\CoupleRacing;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\CoupleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

if($_MODEL_CHOOSE=="CoupleRacing")
{
	$this->title=Yii::t('default', 'PAR_NATJEC_DNEVNIK_LEZENJA_TITLE');
	$url_view=Url::to(['/brood-racing/view']);
	$url_update=Url::to(['/brood-racing/update']);
	$url_hatchingdiary=Url::to(['/couple-racing/hatching-diary']);

}
else if($_MODEL_CHOOSE=="CoupleBreeding")
{
	$this->title=Yii::t('default', 'PAR_UZGOJNI_DNEVNIK_LEZENJA_TITLE');
	$url_view=Url::to(['/brood-breeding/view']);
	$url_update=Url::to(['/brood-breeding/update']);
	$url_hatchingdiary=Url::to(['/couple-breeding/hatching-diary']);
}
$this->params['breadcrumbs'][] = $this->title;

$Pigeon = new Pigeon;
$CoupleRacing = new CoupleRacing;
?>
<?= $this->render('/_alert'); ?>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4>
          <?= Yii::t('default', 'PAR_NATJEC_DNEVNIK_LEZENJA_BREADCRUMB')?>
        </h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <?php $form=ActiveForm::begin(['method'=>'post', 'action'=>$url_hatchingdiary]) ?>
            <div class="alert alert-success">
              <?= $Pigeon->printDownloadRadioChoose() ?>
              <div class="form-group">
                <?= Html::input('number', 'printdownload_year', 2014, ['class'=>'form-control'] ) ?>
              </div>
              <div class="form-group">
                <?= Html::submitButton(Yii::t('default', 'PAR_UZGOJNI_DNEVNIK_LEZENJA_IZRADI_BTN'), ['class'=>'btn btn-cons btn-primary btn-large btn-block'] ) ?>
              </div>
            </div>
            <?php ActiveForm::end() ?>
              <div style="border:1px solid #888; margin:10px 0;"></div>
             <div class="alert alert-danger"><?= Yii::t('default', 'Enter year and click on blue button to get all couple listed for this year.')?></div>
             <?php 
			 //importatn to have action here because witout it everytime I submit form it just picks old url with old parameters and adds new
			 $form2=ActiveForm::begin(['method'=>'get', 'action'=>$url_hatchingdiary]); 
			 ?>
            <?php echo $CoupleRacing->chooseCouplesDropDown($_MODEL_CHOOSE, isset($_GET["IDcouple"])?$_GET["IDcouple"]:NULL); ?>
            <?= Html::submitButton(Yii::t('default', 'Submit'), ['class'=>'btn btn-cons btn-primary']); ?>
            <?php ActiveForm::end() ?>
           <div style="border:1px solid #888;  margin:10px 0;"></div>
           <div class="table-responsive">
             <?php
			
			 if(isset($dataProvider) && isset($coupleDetails))
			 {
				 echo $coupleDetails;
				 echo
					'<table class="table table-hover table-striped table-bordered ">
						<thead>
							<tr>
								<th>'.Yii::t('default', 'LEGLO_UZGOJNI_ATTR_PRVO_JAJE').'</th>
								<th>'.Yii::t('default', 'LEGLO_UZGOJNI_ATTR_DATUM_LEZENJA').'</th>
								<th>'.Yii::t('default', 'LEGLO_UZGOJNI_ATTR_DRZAVA').'</th>
								<th>'.Yii::t('default', 'LEGLO_UZGOJNI_ATTR_BROJ_PRSTENA').'</th>
								<th>'.Yii::t('default', 'LEGLO_UZGOJNI_ATTR_BOJA').'</th>
								<th></th>
							</tr>
						</thead>';
						
				 Pjax::begin(['id' => 'hatching-diary-list']);
				 echo ListView::widget([
					'dataProvider' => $dataProvider,
					'itemOptions' => ['class' => 'item'],
					'itemView' => function ($model, $key, $index, $widget) use ($url_view, $url_update)
					{
						$return = NULL;
				
						//$x is for color and ringnumber to seperate. for example: 1-236,236-5
						$separate=function($x)
						{
							$x=explode(",", $x);
							
							$array=[];
							foreach($x as $key=>$value)
							{
								 $array[]=(empty($value)) ? '&#10006;' : $value;
							}
							
							return implode("<br>", $array);
						};
						
						//$pigeonCountryTable=PigeonCountry::getTableSchema();
						$return.= 
						'<tr>
							<td align="center">'.$model->firstegg.'</td>
							<td align="center">'.$model->hatchingdate.'</td>
							<td align="center">'.$model->group_concat_country.'</td>
							<td align="center">'.$separate($model->ringnumber).'</td>
							<td align="center">'.$separate($model->color).'</td>
							<td align="center">
								'.Html::a('<i class="fa fa-eye"></i>', [$url_view, 'id'=>$model->ID], ['target'=>'_blank']).'
								'.Html::a('<i class="fa fa-pencil"></i>', [$url_update, 'id'=>$model->ID], ['target'=>'_blank']).'
							</td>
						  </tr>';
												
						return $return;
					},
				]);//end of ListView
				 Pjax::end(); //doesn't work because of table
				echo '</table>';
			 }
			 ?>
            </div><!--table-responsive-->
          </div><!-- col-md-12 col-sm-12 col-xs-12-->
        </div><!-- row-->
      </div>
    </div>
  </div>
</div>
