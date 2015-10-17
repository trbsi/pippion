<?php
use backend\assets\AppAsset;
use backend\helpers\ExtraFunctions;
use yii\helpers\Html;


/* @var $this \yii\web\View */
/* @var $content string */
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- BEGIN CORE CSS FRAMEWORK -->
    <link href="/assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen"/>
    <link href="/assets/plugins/boostrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/plugins/boostrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/css/animate.min.css" rel="stylesheet" type="text/css"/>
    <!-- END CORE CSS FRAMEWORK -->
    <!-- BEGIN CSS TEMPLATE -->
    <link href="/assets/css/style.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/css/responsive.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/css/custom-icon-set.css" rel="stylesheet" type="text/css"/>
    <!-- END CSS TEMPLATE -->
	<link rel="shortcut icon" href="<?= Yii::getAlias('@web')?>/images/favicon.ico">
    
    <!-- END CONTAINER -->
    <!-- BEGIN CORE JS FRAMEWORK-->
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="/assets/plugins/boostrapv3/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/pace/pace.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="/assets/plugins/jquery-lazyload/jquery.lazyload.min.js" type="text/javascript"></script>
    <script src="/assets/js/login_v2.js" type="text/javascript"></script>
    <!-- BEGIN CORE TEMPLATE JS -->
    <!-- END CORE TEMPLATE JS -->

</head>

<?php
$ef=new ExtraFunctions;
$rand=$ef->Wallpapers();
?>
<body class="error-body no-top lazy"  data-original="<?php echo Yii::getAlias('@web'); ?>/images/wallpapers/<?php echo $rand ?>"  style="background-image: url('<?php echo Yii::getAlias('@web'); ?>/images/wallpapers/<?php echo $rand ?>'); background-size:cover; background-repeat:no-repeat;">

<?php $this->beginBody() ?>
		<?= $content ?>            

    

<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-50922557-1', 'pippion.com');
ga('require', 'displayfeatures');
ga('send', 'pageview');

</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>





