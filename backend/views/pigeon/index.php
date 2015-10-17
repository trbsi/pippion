<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\search\PigeonSearch;
use backend\models\Pigeon;
use yii\grid\DataColumn;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\PigeonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('default', 'GOLUB_ADMIN_LIST');
$this->params['breadcrumbs'][] = $this->title;
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'GOLUB_LINK_ADMIN'),  'url' => ['index']];
$menuItems[] = ['label' => Yii::t('default', 'GOLUB_LINK_CREATE'),  'url' => ['create']];

$PigeonSearch=new PigeonSearch;
$Pigeon = new Pigeon;
?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>
<?= $this->render('/_alert'); ?>
<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4><?php echo Yii::t('default', 'GOLUB_ADMIN_LIST'); ?></h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
          <?php 
		  if( isset($_SESSION['show_frompedigree']) && $_SESSION['show_frompedigree']==false )
		  {
			  $text=Yii::t('default', 'GOLUB_ADMIN_PRIKAZI_IZ_RODOVNIKA');
			  $url=['/pigeon/index','show_frompedigree'=>'true'];
			  $class='btn btn-primary btn-block';
			}
		  else
		  {
			  $text=Yii::t('default', 'GOLUB_ADMIN_SAKRIJ_IZ_RODOVNIKA');
			  $url=['/pigeon/index','show_frompedigree'=>'false'];
			   $class='btn btn-info btn-block';
			}
		  
		  echo Html::a($text, $url, ['class'=>$class, 'style'=>'margin-bottom:20px;'] );  
		  ?>
          <i class="fa fa-info-circle"></i>
          <div class="label label-info"><?= Yii::t('default', 'STATUS_IZ_RODOVNIKA');?>:</div>
          <?= Yii::t('default', 'STATUS_IZ_RODOVNIKA_EXPLAIN');?>
          <br /><br />
          	
            <?php $form = ActiveForm::begin([
				//'id'=>'ajaxform',
				'method'=>'get',
				//'action'=>Url::to('/pigeon/ajax-delete'),
				//'options' => ['onSubmit'=>'return areYouSure()'],
			]) ?>
			
            <div class="table-responsive">
            <?= GridView::widget([
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'tableOptions'=>['class'=>'table table-hover table-bordered table-striped'],
				'columns' => [
					//['class' => 'yii\grid\SerialColumn'],
		
					//'ID',
					//'IDuser',
					'pigeonnumber',
					[
						'attribute'=>'searchfather',
						'label'=>Yii::t('default', 'GOLUB_OTAC'),
						'value'=>function($data)
						{
							if(!empty($data->relationPigeonListIDpigeon[0]->relationIDfather))
							{
								//[2010] 16000
								$tmp=$data->relationPigeonListIDpigeon[0]->relationIDfather;
								$return="[$tmp->year] $tmp->pigeonnumber";
								return $return;
							}
							else
								return "XXX";
						},
						'filter'=>Html::activeTextInput($searchModel, 'searchfather'),
					],
					[
						'attribute'=>'searchmother',
						'label'=>Yii::t('default', 'GOLUB_MAJKA'),
						'value'=>function($data)
						{
							if(!empty($data->relationPigeonListIDpigeon[0]->relationIDmother))
							{
								//[2010] 16000
								$tmp=$data->relationPigeonListIDpigeon[0]->relationIDmother;
								$return="[$tmp->year] $tmp->pigeonnumber";
								return $return;
							}
							else
								return "XXX";
						},
						'filter'=>Html::activeTextInput($searchModel, 'searchmother'),
					],
					[
						'attribute'=>'sex',
						'value'=>function($data)
						{
							if($data->sex=="X")
								return Yii::t('default', 'GOLUB_SPOL_M');
							else if($data->sex=="Y")
								return Yii::t('default', 'GOLUB_SPOL_Z');
							else
								return "?";
						},
						'filter'=>$Pigeon->dropDownListSex(),
					],
					
					//'color',
					//'breed',
					//'name',
					'year',
					[
						'attribute'=>'IDcountry',
						'value'=>function($data)
						{
							return $data->relationIDcountry->country;
						},
						'filter'=>$Pigeon->dropDownListPigeonCountry(),
						
					],
					[
						'attribute'=>'IDstatus',
						'value'=>function($data)
						{
							return $data->relationIDstatus->status;
						},
						'filter'=>$Pigeon->dropDownListStatus(),
					],
		
					[
						'class' => 'yii\grid\ActionColumn',
						'buttons'=>[
							'delete' => function ($url, $model, $key) {
								return  Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, ['onClick'=>'return areYouSure()']);
							}
						],
					],
					[
						'class' => 'yii\grid\CheckboxColumn',
					],

				],
			]); ?>
            </div>
			<?php
            echo '<div class="label label-important">'.Yii::t('default', 'GOLUB_ADMIN_DELETE').":</div>&nbsp;";
			///this is hidden submit button. Because when user wants to search and he enters data to search and hit Enter this button will  e activated, otherwise this second button will be activated and will ask me to delete pigeon
			//you cannot user formmethod=post because CSRF cannot be verified since main  ActiveForm is GET and csrf won't be generated
            echo Html::submitButton(Yii::t('default', 'GOLUB_ADMIN_DELETE_GOLUB'), ['class'=>'btn btn-success  btn-con', 'style'=>'display:none;'])."&nbsp;";
            echo Html::submitButton(Yii::t('default', 'GOLUB_ADMIN_DELETE_GOLUB'), ['class'=>'btn btn-success  btn-con', 'name'=>'ajax_delete_pigeon', 'onClick'=>'return areYouSure()', 'formaction'=>Url::to('/pigeon/ajax-delete'), 'formmethod'=>'get'])."&nbsp;";

            echo Html::submitButton(Yii::t('default', 'GOLUB_ADMIN_DELETE_OCA'), ['class'=>'btn btn-success  btn-con', 'name'=>'ajax_delete_father', 'onClick'=>'return areYouSure()', 'formaction'=>Url::to('/pigeon/ajax-delete'), 'formmethod'=>'get'])."&nbsp;";

            echo Html::submitButton(Yii::t('default', 'GOLUB_ADMIN_DELETE_MAJKU'), ['class'=>'btn btn-success  btn-con', 'name'=>'ajax_delete_mother', 'onClick'=>'return areYouSure()', 'formaction'=>Url::to('/pigeon/ajax-delete'), 'formmethod'=>'get'])."&nbsp;";

            echo Html::submitButton(Yii::t('default', 'GOLUB_ADMIN_DELETE_BOTH'), ['class'=>'btn btn-success  btn-con', 'name'=>'ajax_delete_both', 'onClick'=>'return areYouSure()', 'formaction'=>Url::to('/pigeon/ajax-delete'), 'formmethod'=>'get'])."&nbsp;";
			
            ?>
             <?php ActiveForm::end(); ?>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
