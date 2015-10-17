<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use backend\helpers\ExtraFunctions;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AdminNotsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = Yii::t('default', 'Admin Nots');
//$this->params['breadcrumbs'][] = $this->title;
?>
<?php \yii\widgets\Pjax::begin(); ?>
<?= ListView::widget([
	'dataProvider' => $adminNotsDataProvider,
	'itemOptions' => [/*'class' => 'item'*/],
	'itemView' => function ($model, $key, $index, $widget) 
	{
		$ef = new ExtraFunctions;
		$return = 
		'
			<div class="notification-messages info">
			  <div class="message-wrapper">
				<div class="heading">'.Html::encode($model->title).'</div>
				<div class="description">'.Html::encode($model->body).'</div>
			  </div>
			  <div class="date pull-right">'.Html::encode($ef->formatDate($model->date_t)).'</div>
			  <div class="clearfix"></div>
			</div>		
		';
		return $return; 
		//Html::a(Html::encode($model->title), ['view', 'id' => $model->ID]);
	},
]) ?>
<?php \yii\widgets\Pjax::end(); ?>
<?php /*

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('default', 'Create {modelClass}', [
    'modelClass' => 'Admin Nots',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
*/
?>
