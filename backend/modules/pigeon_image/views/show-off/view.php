<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\pigeon_image\models\PigeonImage */

$this->title = $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pigeon Images'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pigeon-image-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->ID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ID',
            'IDuser',
            'image_file',
            'IDalbum',
        ],
    ]) ?>

</div>
