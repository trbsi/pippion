<?php

use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model backend\models\Club */

$this->title = $model->club." | ".Yii::t('default', 'Weather');
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Clubs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple">
      <div class="grid-title no-border">
        <h4>
          <?= Html::encode($this->title) ?>
        </h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border"> <br>
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
              <?php
				if(isset($_COOKIE['lang']))
				{
					if($_COOKIE['lang']=="hr")
						$link="http://freemeteo.com.hr/vrijeme/komarom/dnevna-prognoza/3dan/?gid=3049519&language=croatian&country=hungary";
					else
						$link="http://freemeteo.com.hr/weather/komarom/daily-forecast/day3/?gid=3049519&language=english&country=hungary";
				}
				else
				{
					$link="http://freemeteo.com.hr/weather/komarom/daily-forecast/day3/?gid=3049519&language=english&country=hungary";
				}
				?>
				<iframe src="<?=$link?>" style="width:100%; min-height:500px" frameborder="0"></iframe>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
