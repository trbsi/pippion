<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\AccountBalance */

$this->title = Yii::t('default', 'Create Account Balance');
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Account Balances'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-balance-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
