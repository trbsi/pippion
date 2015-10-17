<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ResolveIssue */

$this->title = Yii::t('default', 'Update {modelClass}: ', [
    'modelClass' => 'Resolve Issue',
]) . ' ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Resolve Issues'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('default', 'Update');
?>
<div class="resolve-issue-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
