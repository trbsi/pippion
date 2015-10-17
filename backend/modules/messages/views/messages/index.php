<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\messages\models\search\MessagesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('default', 'Messages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="messages-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('default', 'Create Messages'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'ID',
            'user_one',
            'user_two',
            'user_one_read',
            'user_two_read',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
