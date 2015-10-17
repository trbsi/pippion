<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Breeder */

$this->title = Yii::t('default', 'Create {modelClass}', [
    'modelClass' => 'Breeder',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Breeders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="breeder-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
