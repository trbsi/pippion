<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\AuctionRating */

$this->title = Yii::t('default', 'Create {modelClass}', [
    'modelClass' => 'Auction Rating',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Auction Ratings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auction-rating-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
