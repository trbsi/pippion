<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\messages\models\Messages */

$this->title = Yii::t('default', 'Create Messages');
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="messages-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
