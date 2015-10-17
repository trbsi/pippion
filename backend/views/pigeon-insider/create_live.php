<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<?= $this->render('//layouts/head'); ?>
<title><?php echo Yii::t('default', 'Pigeon Insider Maker') ?></title>
</head>

<body>
<div class="container-fluid">
  <?= $this->render('_form', array('model'=>$model, 'tabularData'=>$tabularData)); ?>
</div>
</body>
</html>
