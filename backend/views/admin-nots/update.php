<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\AdminNots */

$this->title = Yii::t('default', 'Update {modelClass}: ', [
    'modelClass' => 'Admin Nots',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Admin Nots'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('default', 'Update');
?>
<div class="admin-nots-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
