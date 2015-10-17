<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\CountryList;
use backend\models\BreederImage;
/* @var $this yii\web\View */
/* @var $model backend\models\Breeder */

$this->title = Yii::t('default', 'UZG_VIEW_TITLE').' | '.$model->relationIDuser->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Breeders'), /*'url' => ['index']*/];
$this->params['breadcrumbs'][] = $this->title;

?>
<?php
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'UZG_LINK_UPDATE'),  'url' => ['update', 'id' => $model->ID]];
?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>
<div class="row">
  <div class="col-md-12">
    <div class="grid simple ">
      <div class="grid-title no-border">
        <h4><?php echo Yii::t('default', 'UZG_UZGAJIVAC') ?></h4>
        <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
      </div>
      <div class="grid-body no-border">
      <a href="<?= $profile_picture?>" class="profile_pic_colorbox">
          <img src="<?= $profile_picture ?>"  alt="" data-src="<?= $profile_picture ?>" data-src-retina="<?= $profile_picture ?>" width="69" height="69" class="img-circle"  style="margin-right:15px; margin-bottom:15px;" />
      </a> 
        <h3  style="display:inline-block">
			<?php echo "<span class='semi-bold'>".$model->name_of_breeder."</span>"; ?>
            <?php if($model->verified==0) 
			{
					echo '<a href="'.Url::to('/site/verify-acc').'" target="_blank"><img src="'.Yii::getAlias('@web').'/images/not_verified.png" width="25" height="25" class="tooltipp" title="'.Yii::t('default', 'Verify Account').'"></a>';
					echo ' <a href="'.Url::to('/site/verify-acc').'" target="_blank" style="text-decoration:underline;"><strong>'.Yii::t('default', 'Verify account').'</strong></a>';
			}
				else
					echo '<img src="'.Yii::getAlias('@web').'/images/verified.png"  width="25" height="25" class="tooltipp" title="'.Yii::t('default', 'Account Verified').'">'; ?>

        </h3>
			<?= DetailView::widget([
            'model' => $model,
            'options'=>['class'=>'table table-striped table-hover'],
            'attributes' => [
                //'ID',
               // 'IDuser',
                'name_of_breeder',
                [
                    'attribute'=>'country',
                    'value'=>$model->relationCountry->country_name.' ('.$model->relationCountry->country_code.')',
                ],
                'town',
                'address',
                'tel1',
                'tel2',
                'mob1',
                'mob2',
                'email1:email',
                'email2:email',
                'fax',
                [
                    'attribute'=>'website',
                    'format'=>'raw',
                    'value'=>'<a href="'.$model->website.'" target="_blank">'.$model->website.'</a>',
                ],
                //'verified',
            ],
        ])?>
      </div>
    </div>
  </div>
</div>

