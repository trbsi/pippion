<?php
use yii\helpers\Url;
?>
<div class="row">
    <div class="col-xs-12">
        <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
            <?php if (in_array($type, ['success', 'danger', 'warning', 'info'])): ?>
                <div class="alert alert-<?= $type ?>">
                    <?= $message ?>
					<br /><br />
                     <a href="<?= Url::to(['/user/security/login']); ?>" class="btn btn-cons btn-success btn-block"><?= Yii::t('default', 'Sign in to Pippion')?></a>

                </div>
            <?php endif ?>
        <?php endforeach ?>
    </div>
</div>