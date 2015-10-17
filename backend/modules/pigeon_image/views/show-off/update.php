<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\pigeon_image\models\PigeonImage */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Pigeon Image',
]) . ' ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pigeon Images'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="pigeon-image-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
