<?php
$output = exec('yes | php yii migrate/up --migrationPath=@vendor/dektrium/yii2-user/migrations');
echo "<pre>$output</pre>";
?>