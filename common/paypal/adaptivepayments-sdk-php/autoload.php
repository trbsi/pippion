<?php
require_once Yii::getAlias('@common').'/paypal/Configuration.php';
//auto load adaptive payments
if(file_exists(Yii::getAlias('@vendor').'/paypal/adaptivepayments-sdk-php/vendor/autoload.php'))
{
	require Yii::getAlias('@vendor').'/paypal/adaptivepayments-sdk-php/vendor/autoload.php';
}
?>