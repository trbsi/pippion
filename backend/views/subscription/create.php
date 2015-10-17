<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Subscription */

$this->title = Yii::t('default', 'Create {modelClass}', [
    'modelClass' => 'Subscription',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Subscriptions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscription-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
