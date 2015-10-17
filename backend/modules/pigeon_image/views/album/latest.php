<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\ListView;
use backend\modules\pigeon_image\models\Album;
use backend\helpers\ExtraFunctions;
use backend\models\Auction;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pigeon_image\models\search\PigeonImageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('default', 'Latest albums');
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
.date_created
{
	font-size: 10px;
	color: white;
	position: absolute;
	bottom: 30px;
	left:40px;
}
</style>
<?php  echo $this->render("@backend/modules/pigeon_image/views/js/colorbox_gallery_js"); ?>

<div class="pigeon-image-latest">
  <div class="row">
    <div class="col-md-12">
      <div class="grid simple">
        <div class="grid-title no-border">
          <h4><?= Html::encode($this->title) ?></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body no-border"> <br>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12"> 
              <?php
				echo ListView::widget([
					'dataProvider' => $dataProvider,
					'layout' => "{summary}\n<br>{items}\n{pager}",
					'itemView' => function ($model, $key, $index, $widget)
					{
						if(empty($model->lastAlbumPhoto->image_file))
							$lastAlbumPhoto=Yii::getAlias('@web').Auction::UPLOAD_DIR_IMAGES.ExtraFunctions::NO_PICTURE;
						else
							$lastAlbumPhoto=Album::returnPathToThumbnail($model->ID, $model->lastAlbumPhoto->image_file, Yii::getAlias('@web'), $model->IDuser);
						
						return '
						<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 text-center m-b-10">
						<a href="'.Url::to(['view', 'id'=>$model->ID]).'">
							<img src="'.$lastAlbumPhoto.'" class="img-responsive img-thumbnail m-b-10 gallery_thumbnail" >
						</a>
						<br>
						<a href="'.Url::to(['view', 'id'=>$model->ID]).'"><strong>'.$model->album.'&nbsp;<span style="font-size:10px;">('.$model->relationIDuser->username.')</span></strong></a>
						</div>
						';
					},
				]);
			
				 ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
