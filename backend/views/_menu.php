<?php
use yii\widgets\Menu;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
?>
<style>
/* ne stavlja  mi u mobilnoj verziji one crte za mali menu na gumbu desno*/
.navbar-toggle .icon-bar
{
	border:1px solid #888;
}
</style>
<?php
/*
Generate bootstrap top menu
you just have to render this file and pass $menuItems parameters:
<?php
$menuItems = [];
$menuItems[] = ['label' => Yii::t('default', 'Update'),  'url' => ['update', 'id' => $model->ID]];
?>
<?= $this->render('/_menu',['menuItems'=>$menuItems]); ?>

*/
NavBar::begin([
	'brandLabel' => '<i class="fa fa-gear"></i>',
	'brandUrl' => Yii::$app->homeUrl,
	'options' => [
		//'class' => 'navbar-default',
		'style'=>'background:white',
	],
	'innerContainerOptions'=>['class'=>'none'] //without it it fucks cotainer and scrollbar-x shows in content so I can scroll left-right
]);

echo Nav::widget([
	'options' => ['class' => 'nav navbar-nav'],
	'items' => $menuItems,
]);
NavBar::end();
?>