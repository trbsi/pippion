<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\modules\pigeon_image\models\Album;
/* @var $this yii\web\View */
/* @var $model backend\modules\pigeon_image\models\PigeonImage */

$this->title = Yii::t('default', 'Update');
?>

<div class="pigeon-image-update">
  <div class="row">
    <div class="col-md-12">
      <div class="grid simple">
        <div class="grid-title no-border">
          <h4><?= Html::encode($this->title) ?></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body no-border"> <br>
          <div class="row" >
            <div class="col-md-12 col-sm-12 col-xs-12">
            <?php echo Html::beginForm('', 'post', $options = [] ) ?>
			<?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
					[
                    	'attribute'=>'image_file',
						'format'=>'html',
						'value'=>function($data)
						{
							return '<img src='.Album::returnPathToThumbnail($data->IDalbum, $data->image_file, Yii::getAlias('@web'), $data->IDuser).' class="img-responsive img-thumbnail gallery_thumbnail">';
						}
					],
					[
						'attribute'=>'description',
						'format'=>'raw',
						'value'=>function($data)
						{
							$return=Html::textarea('description[]', $data->description, $options = ['class'=>'form-control', 'rows'=>10] );
							$return.=Html::hiddenInput('IDimage[]', $data->ID, $options = [] );
							return $return;
						}
					],
					'date_created',
                    //['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
             <?= Html::submitButton(Yii::t('default', 'Update'), ['class' =>'btn btn-success btn-cons', 'style'=>'position:fixed; bottom:10px; right:10px; z-index:1000;']) ?>
            <?php echo Html::endForm() ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
