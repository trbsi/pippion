<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use backend\helpers\LinkGenerator;
use backend\helpers\ExtraFunctions;
use backend\models\Pigeon;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $model backend\models\Pigeon */

$this->title = Yii::t('default', 'GOLUB_VIEW_TITLE');
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Pigeons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'Update'),  'url' => ['update', 'id' => $model->ID]];
$menuItems[] = ['label' => Yii::t('default', 'GOLUB_LINK_CREATE'),  'url' => ['create']];
$menuItems[] = ['label' => Yii::t('default', 'GOLUB_LINK_DELETE'),  'url' => ['delete', 'id' => $model->ID],
					'linkOptions'=>
					[
						'data' => 
						[
							'confirm' => Yii::t('default', 'Are you sure you want to delete this item?'),
							'method' => 'post',
						],
					]
				];
$menuItems[] = ['label' => Yii::t('default', 'GOLUB_LINK_ADMIN'),  'url' => ['index']];
?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>
<?= $this->render('/_alert'); ?>

<div class="row">
  <div class="col-md-12">
    <div class="grid simple ">
      <div class="grid-title no-border">
        <h4>&nbsp;</h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border">
        <h3 class="text-center"> <?php echo "[".$model->relationIDcountry->country."/".$model->year."]&nbsp;".$model->pigeonnumber."/".$model->getSex($model->sex); ?> </h3>
       
        <?= Html::beginForm(Url::to(['/pigeon/pedigree']), 'post', ["class"=>"text-center m-b-20", "target"=>"_blank" ] ) ?>
        <?= Pigeon::printDownloadRadioChoose() ?>
        <input type="submit" value="<?= Yii::t('default', 'Pedigree') ?>" class="btn btn-primary btn-xs btn-mini" />
        <input type="hidden" name="user" value="<?= Yii::$app->user->getId(); ?>">
        <input type="hidden" name="IDpigeon" value="<?= $model->ID; ?>">
        <br /><br />
        <?= Html::endForm(); ?>
        <div class="table-responsive">
          <table class="table table-bordered">
            <tr>
              <th><?= Yii::t('default', 'GOLUB_STATUS') ?></th>
              <th><?= Yii::t('default', 'GOLUB_BOJA') ?></th>
              <th><?= Yii::t('default', 'GOLUB_RASA') ?></th>
              <th><?= Yii::t('default', 'GOLUB_IME') ?></th>
            </tr>
            <tr>
              <td><?= LinkGenerator::statusLink($model->relationIDstatus->status, $model->IDstatus, ['style'=>'text-decoration:underline']);  ?></td>
              <td><?= $model->color ?></td>
              <td><?= $model->breed ?></td>
              <td><?= $model->name ?></td>
            </tr>
          </table>
        </div>
        <table class="table" style="margin-bottom:50px;">
          <tr>
            <td class="text-center"><img src="<?= Yii::getAlias('@web') ?>/images/male.png" class="img-responsive center-block" style="max-height:30px" /></td>
            <td class="text-center"><img src="<?= Yii::getAlias('@web') ?>/images/female.png" class="img-responsive center-block" style="max-height:30px" /></td>
          </tr>
          <tr>
            <td class="text-center"><?php
				$father=$model->getParents($model->ID,'X'); 
				echo LinkGenerator::pigeonLink($father["pigeonnumber"], $father["IDparent"], ['style'=>'text-decoration:underline']); 
				?></td>
            <td class="text-center"><?php
				$mother=$model->getParents($model->ID,'Y'); 
				echo LinkGenerator::pigeonLink($mother["pigeonnumber"], $mother["IDparent"], ['style'=>'text-decoration:underline']); 
				?></td>
          </tr>
        </table>
        <div class="col-md-12">
          <ul class="nav nav-tabs" id="tab-01">
            <li class="active"><a href="#pigeon_image">
              <?= Yii::t('default', 'Picture of a pigeon'); ?>
              </a></li>
            <li><a href="#results">
              <?= Yii::t('default', 'Results'); ?>
              </a></li>
            <li><a href="#couple">
              <?= Yii::t('default', 'Partners'); ?>
              </a></li>
            <li><a href="#offsprings">
              <?= Yii::t('default', 'Offsprings'); ?>
              </a></li>
          </ul>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
          <div class="tab-content">
            <div class="tab-pane active" id="pigeon_image">
              <div class="row">
                <div class="col-md-6">
                	<?php 
					if($model->pigeon_image==ExtraFunctions::NO_PICTURE)
						$image_path=ExtraFunctions::pathNoPicture(ExtraFunctions::NO_PICTURE);
					else
						$image_path=Pigeon::returnPathToPigeonImageEye(Yii::getAlias('@web'), $model->IDuser).$model->pigeon_image;
					?>
					<a href="<?= $image_path ?>" target="_blank">
                    <img src="<?= $image_path ?>" class="img-responsive img-thumbnail"/>
                    </a>                  
                </div>
                <div class="col-md-6">
                	<?php 
					if($model->eye_image==ExtraFunctions::NO_EYE)
						$image_path=ExtraFunctions::pathNoPicture(ExtraFunctions::NO_EYE);
					else
						$image_path=Pigeon::returnPathToPigeonImageEye(Yii::getAlias('@web'), $model->IDuser).$model->eye_image;
					?>
					<a href="<?= $image_path ?>" target="_blank">
					<img src="<?= $image_path ?>" class="img-responsive img-thumbnail"/>     
                    </a>             
                </div>
              </div>
            </div>
            <div class="tab-pane" id="results">
              <div class="row">
                <div class="col-md-12">
                  <div class="table-responsive">
                    <?php Pjax::begin([]) ?>
                    <?= GridView::widget([
					'dataProvider' => $pigeonDataProvider,
					'columns' => [
						'pigeondata',
						'year',
					],
					]); ?>
                    <?php Pjax::end() ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="couple">
              <div class="row">
                <div class="col-md-6">
                  <h4><strong>
                    <?= Yii::t('default', 'Racing couple') ?>
                    </strong></h4>
                  <div class="table-responsive">
                    <?php Pjax::begin([]) ?>
                    <?= GridView::widget([
					'dataProvider' => $racingPartnersDataProvider,
					'columns' => [
						[
							'attribute'=>'couplenumber',
							'format'=>'html',
							'value'=>function($data)
							{
								return "[$data->year]&nbsp;".$data->couplenumber;
							}
						],
						[
							'label'=>Yii::t('default', 'Pigeon number'),
							'format'=>'html',
							'value'=>function($data) use ($model)
							{
								//if current pigeon's ID is the same as ID of male in CoupleRacing then show female pigeon
								if($model->ID==$data->male)
									$pigeon="[".$data->relationFemale->relationIDcountry->country."/".$data->relationFemale->year."]&nbsp;".$data->relationFemale->pigeonnumber;
								else
									$pigeon="[".$data->relationMale->relationIDcountry->country."/".$data->relationMale->year."]&nbsp;".$data->relationMale->pigeonnumber;
									
								return $pigeon;
							}
						],
					],
					]); ?>
                    <?php Pjax::end() ?>
                  </div>
                </div>
                <div class="col-md-6">
                  <h4><strong>
                    <?= Yii::t('default', 'Breeding couple') ?>
                    </strong></h4>
                  <div class="table-responsive">
                    <?php Pjax::begin([]) ?>
                    <?= GridView::widget([
					'dataProvider' => $breedingPartnersDataProvider,
					'columns' => [
						[
							'attribute'=>'couplenumber',
							'format'=>'html',
							'value'=>function($data)
							{
								return "[$data->year]&nbsp;".$data->couplenumber;
							}
						],
						[
							'label'=>Yii::t('default', 'Pigeon number'),
							'format'=>'html',
							'value'=>function($data) use ($model)
							{
								//if current pigeon's ID is the same as ID of male in CoupleRacing then show female pigeon
								if($model->ID==$data->male)
									$pigeon="[".$data->relationFemale->relationIDcountry->country."/".$data->relationFemale->year."]&nbsp;".$data->relationFemale->pigeonnumber;
								else
									$pigeon="[".$data->relationMale->relationIDcountry->country."/".$data->relationMale->year."]&nbsp;".$data->relationMale->pigeonnumber;
									
								return $pigeon;
							}
						],
					],
					]); ?>
                    <?php Pjax::end() ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="offsprings">
              <div class="row">
                <div class="col-md-6">
                  <h4><strong>
                    <?= Yii::t('default', 'Racing broods') ?>
                    </strong></h4>
                  <div class="table-responsive">
                    <?php Pjax::begin([]) ?>
                    <?= GridView::widget([
					'dataProvider' => $offspringsRacingDataProvider,
					'columns' => [
						[
							'label'=>Yii::t('default', 'PAR_NATJEC_ATTR_BROJ_PARA'),
							'format'=>'html',
							'value'=>function($data)
							{
								return "[".$data->relationIDcoupleRacing->year."]&nbsp;".$data->relationIDcoupleRacing->couplenumber;
							}
						],
						[
							'attribute'=>'ringnumber',
							'format'=>'html',
							'value'=>function($data)
							{
								return "[".$data->relationIDcountry->country."]&nbsp;".$data->ringnumber;
							}
						],
						'hatchingdate',

					],
					]); ?>
                    <?php Pjax::end() ?>
                  </div>
                </div>
                <div class="col-md-6">
                  <h4><strong>
                    <?= Yii::t('default', 'Breeding broods') ?>
                    </strong></h4>
                  <div class="table-responsive">
                    <?php Pjax::begin([]) ?>
                    <?= GridView::widget([
					'dataProvider' => $offspringsBreedingDataProvider,
					'columns' => [
						[
							'label'=>Yii::t('default', 'PAR_NATJEC_ATTR_BROJ_PARA'),
							'format'=>'html',
							'value'=>function($data)
							{
								return "[".$data->relationIDcoupleBreeding->year."]&nbsp;".$data->relationIDcoupleBreeding->couplenumber;
							}
						],
						[
							'attribute'=>'ringnumber',
							'format'=>'html',
							'value'=>function($data)
							{
								return "[".$data->relationIDcountry->country."]&nbsp;".$data->ringnumber;
							}
						],
						'hatchingdate',

					],
					]); ?>
                    <?php Pjax::end() ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
