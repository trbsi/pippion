<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\AdminNots */

$this->title = Yii::t('default', 'Create {modelClass}', [
    'modelClass' => 'Admin Nots',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Admin Nots'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-nots-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
