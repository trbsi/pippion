<?php
namespace backend\helpers;
use Yii;

class Mysqli
{
	
	public function connectMysqli()
	{
		$database="thettaco_pippion_v2";
		$username = "thettaco_mojgolu";
		$password = "BLAHhdJ876dNDk30HJdb40$%";
		$hostname = "127.0.0.1"; 
		
		/*$username = "root";
		$password = "";
		$hostname = "localhost"; 
		$database="pippion_migrate";*/
		
		$mysqli=mysqli_connect($hostname, $username, $password, $database);	
		mysqli_set_charset ( $mysqli , "utf8");
		
		return $mysqli;
	}
}
?>