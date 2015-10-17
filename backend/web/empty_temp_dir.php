<?php
//http://stackoverflow.com/questions/7332804/run-a-php-file-in-a-cron-job-using-cpanel
//->/usr/bin/php -q /home/thettaco/public_html/PIPPION.COM/backend/web/empty_temp_dir.php >/dev/null 2>&1
$dir=dirname(__FILE__)."/temp/"; /*/home/thettaco/public_html/PIPPION.COM/backend/web + /temp/*/
$handle = scandir($dir);
foreach($handle as $key=>$value)
{
	if($value=="." || $value==".." || $value=="index.html")
		continue;
	if(is_dir($value)==true)
		continue;
	unlink($dir.$value);
}
?>