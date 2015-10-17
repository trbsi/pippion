<meta charset="utf-8" />
<?php
$langs=include('messages/en/default.php');
$langs_hr=include('messages/hr/default.php');

$directories=
[
	'backend/controllers/',
	'backend/helpers/',
	'backend/models/',
	'backend/models/search/',
	'backend/themes/login/views/layouts/',
	'backend/user/controllers/',
	'backend/user/models/',
	'backend/user/views/admin/',
	'backend/user/views/mail/',
	'backend/user/views/profile/',
	'backend/user/views/recovery/',
	'backend/user/views/registration/',
	'backend/user/views/security/',
	'backend/user/views/settings/',	
	'backend/views/',
	'backend/views/admin-nots/',
	'backend/views/auction/',
	'backend/views/auction-rating/',
	'backend/views/breeder/',
	'backend/views/breeder-results/',
	'backend/views/brood/',
	'backend/views/couple/',
	'backend/views/couple/hatchingdiary/',
	'backend/views/found-pigeons/',
	'backend/views/layouts/',
	'backend/views/pigeon/',
	'backend/views/pigeon/pedigree/',
	'backend/views/pigeon-data/',
	'backend/views/pigeon-insider/',
	'backend/views/racing-table/',
	'backend/views/racing-table-category/',
	'backend/views/site/',
	'backend/views/status/',
	'backend/views/subscription/',
];


$files=[];


foreach($directories as $key=>$value)
{
	$scan=scandir($value);
	foreach($scan as $key2=>$value2)
	{
		if(is_dir($value2)==false)
		{
			
			$ext = pathinfo($value2, PATHINFO_EXTENSION);
			if(in_array($ext,['php', 'htm', 'html', 'txt']))
				$files[]=$value.$value2;
		}
	}
}


$taj_lang_ne_postoji=[];
$taj_lang_postoji=[];
$directory_where_is_file=[];
foreach($langs as $lang_key=>$lang_value)
{
	$line_number = false;
	$search      = $lang_key;
	$where_is_file=false;
	
	foreach($files as $key=>$file)
	{
		if ($handle = fopen($file, "r")) 
		{
		   $count = 0;
		   while (($line = fgets($handle, 4096)) !== FALSE and !$line_number) 
		   {
			  $count++;
			  $line_number = (strpos($line, $search) !== FALSE) ? $count : $line_number;
			  $where_is_file = (strpos($line, $search) !== FALSE) ? $file : $where_is_file;

		   }
		   fclose($handle);
		}
	}
	
	if($where_is_file!=false)
	{
		$directory_where_is_file[$lang_key]=$where_is_file;
	}
	
	if($line_number==false)
	{
		$taj_lang_ne_postoji[$lang_key]=$lang_value;
	}
	else
	{
		$taj_lang_postoji[$lang_key]=$lang_value;
	}

}

//unset
foreach($taj_lang_ne_postoji as $key=>$value)
{
	unset($langs[$key]);
}

foreach($langs as $k=>$v)
{
	echo '"'.$k.'" => "'.$v.'",'."\t//$directory_where_is_file[$k]"."\n";
}

/*
$a=1;
foreach($taj_lang_ne_postoji as $k=>$v)
{
	echo $a++." ".$k." => ".$v."<br>";
}
echo "----------------------------------------------------------<br>";
$b=1;

foreach($taj_lang_postoji as $k=>$v)
{
	echo "'".$k."' => '".$v."'<br>";
	$b++;
}*/