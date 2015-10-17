<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use backend\helpers\ExtraFunctions;
use backend\models\Auction;
use backend\modules\pigeon_image\models\Album;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pigeon_image\models\search\PigeonImageAlbumSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('default', 'Albums');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4><?= Yii::t('default', 'Albums') ?> </h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <p>
              <?= Html::a(Yii::t('default', 'Create'), ['create'], ['class' => 'btn btn-success btn-cons btn-block']) ?>
            </p>
            </div>
            <?php
			echo ListView::widget([
				'dataProvider' => $dataProvider,
				'layout' => "{summary}\n<br>{items}\n{pager}",
				'itemView' => function ($model, $key, $index, $widget) use ($club_page)
				{
					if(empty($model->lastAlbumPhoto->image_file))
						$lastAlbumPhoto=Yii::getAlias('@web').Auction::UPLOAD_DIR_IMAGES.ExtraFunctions::NO_PICTURE;
					else
						$lastAlbumPhoto=Album::returnPathToThumbnail($model->ID, $model->lastAlbumPhoto->image_file, Yii::getAlias('@web'), $model->IDuser);
					if($club_page!=NULL)
						$urlToAlbum=Url::to(['view', 'id'=>$model->ID, 'club_page'=>$club_page]);
					else
						$urlToAlbum=Url::to(['view', 'id'=>$model->ID]);
					
					return '
					<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 text-center m-b-10">
						<a href="'.$urlToAlbum.'"><img src="'.$lastAlbumPhoto.'" class="img-responsive img-thumbnail m-b-10 gallery_thumbnail" ></a>
						<br>
						'.ExtraFunctions::formatDate($model->date_created, "ymd-his").'
						<br>
						<a href="javscript:;"><strong>'.$model->album.'</strong></a>
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
<?php // echo $this->render('_search', ['model' => $searchModel]); ?>
