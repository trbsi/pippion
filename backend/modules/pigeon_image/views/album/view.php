<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use backend\helpers\ExtraFunctions;
use yii\grid\GridView;
use yii\widgets\ListView;
use backend\modules\pigeon_image\models\Album;
use backend\helpers\LinkGenerator;
use backend\modules\pigeon_image\models\Image;

/* @var $this yii\web\View */
/* @var $model backend\modules\pigeon_image\models\PigeonImageAlbum */

$this->title = "[".Yii::t('default','Album')."] ".$model->album;
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Pigeon Image Albums'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<style>
/*choose file button show inline*/
.bootstrap-filestyle {
	display:inline-block;
}
</style>
<?php  echo $this->render("@backend/modules/pigeon_image/views/js/colorbox_gallery_js"); ?>
<script>
$(document).ready(function(e) 
{
	<?php if($canEdit==true): ?>
    $('#fileupload').fileupload(
	{
        // Uncomment the following to send cross-domain cookies:
        //xhrFields: {withCredentials: true},
        url: '<?= Url::to(["/pigeon-image/album/upload-files"]); ?>?id=<?= $model->ID; ?>',
		maxFileSize: <?= Image::MAX_IMAGE_SIZE ?>,
		acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
    });
	<?php endif; ?>
		
});

//center content in image-lightbox.php
//http://jsfiddle.net/giobongio/RyFje/1/
function centerContent()
{
	var box_wrapper = $('.box_wrapper');
	var image_box = $('.image_box');
	var current_image = $('.current_image');
	alert(box_wrapper.height()-current_image.height());
	current_image.css("top", (box_wrapper.height()-current_image.height())/2);
}
</script>


<div class="pigeon-image-album-view">
  <div class="pigeon-image-album-update">
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple">
          <div class="grid-title no-border">
            <h4>
              <?= Html::encode($this->title);  ?> - <?= LinkGenerator::breederLink($model->relationIDuser->username, $model->IDuser) ?> 
            </h4>
            <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
          </div>
          <div class="grid-body no-border"> <br>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <h1></h1>
                <p>
                <?php if($canEdit==true): ?>
                  <?= Html::a(Yii::t('default', 'Update album'), ['update', 'id' => $model->ID], ['class' => 'btn btn-primary btn-cons']) ?>
                  <?= Html::a(Yii::t('default', 'Delete album'), ['delete', 'id' => $model->ID], [
                        'class' => 'btn btn-danger btn-cons',
                        'data' => [
                            'confirm' => Yii::t('default', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                  <?= Html::a(Yii::t('default', 'Edit image description'), ['image/update', 'IDalbum' => $model->ID], ['class' => 'btn btn-success btn-cons']) ?>
                  <?php endif; ?>
                </p>
                <?php 
				if($canEdit==true)
					include Yii::getAlias('@webroot').Yii::$app->params['MULTIPLE_UPLOAD_FILE_UPLOAD_DIRECTORY']; 
				?>
                <?= Html::beginForm(Url::to(["delete-images", 'id'=>$model->ID]), 'post', ['onSubmit'=>'return areYouSure()'] ); ?>
                <?php
				//$model = Image
                echo ListView::widget([
                    'dataProvider' => $dataProviderImages,
                    'layout' => "{summary}\n<br>{items}\n{pager}",
                    'itemView' => function ($model, $key, $index, $widget) use ($canEdit)
                    {
                        $photo=Album::returnPathToPicture($model->IDalbum, $model->image_file, Yii::getAlias('@web'), $model->IDuser);
                        $return='
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 text-center m-b-10">
                        	<a href="javascript:;" class="colorbox_gallery" data-idimage="'.$model->ID.'" data-idalbum="'.$model->IDalbum.'">
								<img src="'.Album::returnPathToThumbnail($model->IDalbum, $model->image_file, Yii::getAlias('@web'), $model->IDuser).'" class="img-responsive img-thumbnail m-b-10 gallery_thumbnail">
							</a>
						<br>';
						if($canEdit==true)
                        	$return.='<input type="checkbox" name="delete_image[]" value="'.$model->ID.'">';
                        $return.='</div>';
						return $return;
                    },
                ]);
    
                 ?>
                 <div style="clear:both"></div>
                <?php 
				if($canEdit==true)
					echo Html::submitButton(Yii::t('default', 'Delete selected pictures'), ['class'=>'btn btn-danger btn-cons'] ); 
				?>
                  <?= Html::endForm(); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
