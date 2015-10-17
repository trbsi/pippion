<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\pigeon_image\models\Comment */

$this->title = Yii::t('default', 'Create Comment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('default', 'Comments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
