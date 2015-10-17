<?php
//http://www.phpied.com/simultaneuos-http-requests-in-php-with-curl/
/*
MAKNI CAPTCHU  za registraciju
UREDI $url
*/
$hostname = "127.0.0.1";
$username = "root";
$password = "";
$database = "pippion";
$mysqli = new mysqli($hostname, $username, $password, $database);
$url = 'http://backend.localhost.com/user/registration/register';
$queryFilms = "SELECT * FROM mg_users ORDER BY id ASC";
$resultFilms = $mysqli->query($queryFilms);
$data = array(array(),array());
$i=0;
$j=1;
while ($rowFilms = $resultFilms->fetch_assoc()) 
{
	$fields_string="";
	//if($rowFilms['username']=="admin")
		//$pass="nemaboljeg";
	if($rowFilms['username']=="dalmasi")
		$pass="18511zkv";
	else if($rowFilms['username']=="dinamovac1961")
		$pass="durodoric1961";
	else
		$pass=mt_rand();
		
		echo $j++.".".$rowFilms['username']."<br> ";
	//echo $rowFilms['username'];
	//set POST variables
	/*$fields = array(
							'register-form[username]' => $rowFilms['username'],
							'register-form[email]' => $rowFilms['email'],
							'register-form[password]' => $pass,
					);
	
	//set POST variables
	
	//url-ify the data for the POST
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string, '&');
	
	
	//open connection
	$ch = curl_init();
	
	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
	
	//execute post
	$result = curl_exec($ch);
	
	//close connection
	curl_close($ch);*/
	
	$data[$i]['url']  =$url;
	$data[$i]['post']['register-form[username]']=$rowFilms['username'];
	$data[$i]['post']['register-form[email]']=$rowFilms['email'];
	$data[$i]['post']['register-form[password]']=$pass;
	multiRequest($data);
	$i++;
}




function multiRequest($data, $options = array()) 
{
 
 
  // array of curl handles
  $curly = array();
  // data to be returned
  $result = array();
 
  // multi handle
  $mh = curl_multi_init();
 
  // loop through $data and create curl handles
  // then add them to the multi-handle
  foreach ($data as $id => $d) 
  {
 
    $curly[$id] = curl_init();
 
   // $url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
	$url = 'http://backend.localhost.com/user/registration/register';
    curl_setopt($curly[$id], CURLOPT_URL,            $url);
    curl_setopt($curly[$id], CURLOPT_HEADER,         0);
    curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);
 
    // post?
    if (is_array($d)) {
      if (!empty($d['post'])) {
        curl_setopt($curly[$id], CURLOPT_POST,       1);
        curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $d['post']);
      }
    }
 
    // extra options?
    if (!empty($options)) {
      curl_setopt_array($curly[$id], $options);
    }
 
    curl_multi_add_handle($mh, $curly[$id]);
  }
 
  // execute the handles
  $running = null;
  do {
    curl_multi_exec($mh, $running);
  } while($running > 0);
 
 
  // get content and remove handles
  foreach($curly as $id => $c) {
    $result[$id] = curl_multi_getcontent($c);
    curl_multi_remove_handle($mh, $c);
  }
 
  // all done
  curl_multi_close($mh);
 
  return $result;
}
