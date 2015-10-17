<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\pigeon_image\models\PigeonImage */

$this->title = Yii::t('app', 'Create Pigeon Image');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pigeon Images'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pigeon-image-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
