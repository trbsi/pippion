<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pigeon_image\models\search\PigeonImageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('default', 'Pigeon Images');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pigeon-image-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('default', 'Create Pigeon Image'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'ID',
            'IDuser',
            'image_file',
            'IDalbum',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
