<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\widgets\Menu;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

/** @var dektrium\user\models\User $user */
$user = Yii::$app->user->identity;
$networksVisible = count(Yii::$app->authClientCollection->clients) > 0;

?>
<?php
//IZMJENA CIJELOG
	    NavBar::begin([
			'brandLabel' => '<i class="fa fa-gear"></i>',
			'brandUrl' => Yii::$app->homeUrl,
			'options' => [
				'class' => 'navbar-default',
				'style'=>'background:white',
			],
			'innerContainerOptions'=>['class'=>'none']
		]);
		$menuItems = [
			['label' => 'Home', 'url' => ['/index.php']],
		];
		if (Yii::$app->user->isGuest) 
		{
			$menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
		} 
		else 
		{
			//$menuItems[] = ['label' => Yii::t('user', 'Profile'),  'url' => ['/user/settings/profile']];
			$menuItems[] = ['label' => Yii::t('user', 'Account'),  'url' => ['/user/settings/account']];
			$menuItems[] = ['label' => Yii::t('user', 'Networks'), 'url' => ['/user/settings/networks'], 'visible' => $networksVisible];
			$menuItems[] = [
				'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
				'url' => ['/site/logout'],
				'linkOptions' => ['data-method' => 'post']
			];

		}
		echo Nav::widget([
			'options' => ['class' => 'nav navbar-nav', 'style'=>'margin:;'],
			'items' => $menuItems,
		]);
		NavBar::end();
?>
<?php
/* OLD LINKS
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <img src="http://gravatar.com/avatar/<?= $user->profile->gravatar_id ?>?s=24" class="img-rounded" alt="<?= $user->username ?>"/>
            <?= $user->username ?>
        </h3>
    </div>
    <div class="panel-body">
        <?= Menu::widget([
            'options' => [
                'class' => 'nav nav-pills nav-stacked'
            ],
            'items' => [
                ['label' => Yii::t('user', 'Profile'),  'url' => ['/user/settings/profile']],
                ['label' => Yii::t('user', 'Account'),  'url' => ['/user/settings/account']],
                ['label' => Yii::t('user', 'Networks'), 'url' => ['/user/settings/networks'], 'visible' => $networksVisible],
            ]
        ]) ?>
    </div>
</div>*/
?>