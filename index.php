<?php
$backend="http://backend.".$_SERVER['HTTP_HOST'];
$frontend="http://frontend.".$_SERVER['HTTP_HOST'];
header("Location:$backend");
?>