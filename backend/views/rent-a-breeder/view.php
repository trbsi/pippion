<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\RentABreeder */

$this->title = $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Rent Abreeders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rent-abreeder-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('default', 'Update'), ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('default', 'Delete'), ['delete', 'id' => $model->ID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('default', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ID',
            'IDuser',
            'IDcountry',
            'city',
            'active',
            'breeder_picture',
            'extra_info:ntext',
        ],
    ]) ?>

</div>
