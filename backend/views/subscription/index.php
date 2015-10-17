<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\SubscriptionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('default', 'Subscriptions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscription-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('default', 'Create {modelClass}', [
    'modelClass' => 'Subscription',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'ID',
            'IDuser',
            'start_date',
            'end_date',
            'price',
			[
				'class' => 'yii\grid\DataColumn', 
				'header'=>'Renew Sub',
				'format'=>'html',
				'value'=>function($data)
				{
					return '<a href="'.Url::to(['/subscription/index', 'bank_payment'=>$data->order_id]).'" class="btn btn-primary">Bank paid</a>';
				}
			],
            // 'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
