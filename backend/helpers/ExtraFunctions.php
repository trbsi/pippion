<?php
namespace backend\helpers;
use Yii;
use backend\models\Breeder;
use backend\models\Pigeon;
use backend\models\Auction;
use backend\models\Subscription;
use yii\helpers\Url;

class ExtraFunctions
{
	
	const NO_PICTURE = 'no_picture.jpg';
	const NO_EYE = 'no_eye.jpg';
	public $actual_link;
	
	/*
	* constructor
	*/
	function __construct() 
	{
		$this->actual_link='http'.(isset($_SERVER['HTTPS']) ? 's' : '').'://'."{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		
	}

	public static function pippionFullUrl()
	{
		return 'http'.(isset($_SERVER['HTTPS']) ? 's' : '').'://'.$_SERVER['HTTP_HOST'];
	}
	
	/*
	* get path for no_picture
	* $pic = no_picture.jpg or no_eye.jpg
	*/
	public static function pathNoPicture($pic)
	{
		if($pic==ExtraFunctions::NO_PICTURE)
			return Yii::getAlias('@web')."/images/".ExtraFunctions::NO_PICTURE;
		else
			return Yii::getAlias('@web')."/images/".ExtraFunctions::NO_EYE;
	}
	
	/*
	* download file (pedigree, hatching diary, racing table) or any other
	* $html - what to put inside file
	*/
	public function downloadFile($html)
	{
		$path=Yii::getAlias('@webroot')."/temp/".mt_rand().".html";
		$file=fopen($path, 'w');
		fwrite($file, $html);
		fclose($file);
		
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.basename($path).'"');
		header('Content-Length: ' . filesize($path));
		readfile($path);
	}
		
	/*
	* check if breeder is verified
	* $string - string to to set as flash message
	*/
	public function isBreederVerified($string)
	{
		$is_breeder_verified_cookie=ExtraFunctions::getCookie(\Yii::$app->params['is_breeder_verified_cookie']);
		if(empty($is_breeder_verified_cookie) || $is_breeder_verified_cookie==NULL)
		{
			$verified=Breeder::find()->where(['IDuser'=>Yii::$app->user->getId()])->one();
			if($verified->verified==0) //nije verificiran
			{
				ExtraFunctions::setCookie(\Yii::$app->params['is_breeder_verified_cookie'], 0);
				Yii::$app->session->setFlash('danger', Yii::t('default','You have to be verified to access', ['0' => $string]));
				$url=Url::to(['/site/verify-acc']);
				//http://www.yiiframework.com/doc-2.0/yii-web-response.html#redirect()-detail
				return Yii::$app->getResponse()->redirect($url);		
			}
			else
				ExtraFunctions::setCookie(\Yii::$app->params['is_breeder_verified_cookie'], 1);
		}
		else if($is_breeder_verified_cookie==1)
			return true;
		else if($is_breeder_verified_cookie==0)
			return false;
	}
	
	/*
	* Set timezone
	* Setlanguage
	*/
	public function beforeActionTimeLanguage()
	{
		
		//SET COOKIE FOR A LANGUAGE
		if(isset($_GET['lang']))
		{
			if(in_array ( $_GET['lang'] , array('hr','en','nl', 'fr', 'ca', 'da', 'es', 'it', 'pt', 'pt-BR', 'ru', 'vi', 'hu', 'de', 'zh-cn') ))
			{
				setcookie("lang",  $_GET['lang'], time() + 31104000, "/"); // 86400 = 1 day
				//\Yii::$app->language = $_COOKIE['lang'];
				//Get full url
				$actual_link = $this->actual_link;
				//remove query string (everything after "lang" word)
				//first search for "?lang" if there is not there search "&lang"
				$strstr=strstr($actual_link, '?lang', true);
				$actual_link=empty($strstr) ? strstr($actual_link, '&lang', true) : $strstr;
				header("Location:$actual_link");
				die();
			}
		}
		
		if(isset($_COOKIE['lang']))
		{
			\Yii::$app->language =$_COOKIE['lang'];
		}
		else
		{
			\Yii::$app->language ="en";
		}

		//set UTC as default timezone, for auctions and any other time or timer on site
		date_default_timezone_set('UTC');
	}
	
	/*
	* check if user has entered required data of him as breeder
	*/
	public function beforeActionBreederData()
	{

		if($_SERVER['REQUEST_URI']!="/index.php" && $_SERVER['REQUEST_URI']!="/")
		{
					
			//https://www.facebook.com/groups/yiitalk/permalink/10153037463372150/
			$action=Yii::$app->controller->action->id;
			$controller=Yii::$app->controller->id;
			$url=$controller."/".$action;
			$public_actions=['site/error', 'breeder/update', 'user/logout', 'user/login', 'site/contact', 'breeder/profile', 
					'site/what-is-pippion', 'site/auth-connect', 'site/captcha', 'site/server-time', 'user/security/login',
					'site/password-reset-reminder', 'subscription/ipn', 'pigeon-insider/create'];
			
			//If he didn't open any of these urls then show error	
			//if user is not guest check for breeder data
			if(!Yii::$app->user->isGuest && !in_array($url,$public_actions))
			{
				$returnData=$this->didUserEnterRequiredData();
				if($returnData==false)
				{
					$url=Url::to(['/breeder/update', 'err'=>'emptybreederdata', 'id'=>Yii::$app->user->getId()]);
					//http://www.yiiframework.com/doc-2.0/yii-web-response.html#redirect()-detail
					return Yii::$app->getResponse()->redirect($url);
				}
			}
		}

	}
	
	/*
	* Check if user has entered required information about him as breeder
	*/
	protected function didUserEnterRequiredData()
	{
		//check for cookie
		$breeder_data=ExtraFunctions::getCookie(\Yii::$app->params['breeder_data_cookie']);	
		if(empty($breeder_data) || $breeder_data==NULL)
		{
			$x=Breeder::find()->where(['IDuser'=>Yii::$app->user->getId()])->one();
			if(empty($x->name_of_breeder) || $x->name_of_breeder=='-' || empty($x->country) || empty($x->town) || $x->town=="-" || empty($x->address) || $x->address=="-" || empty($x->email1) || $x->email1=="-" || ((empty($x->tel1) || $x->tel1=="-") && (empty($x->mob1) || $x->mob1=="-")) )
			{
				//set cookie, breeder didn't fill out his data 
				ExtraFunctions::setCookie(\Yii::$app->params['breeder_data_cookie'], 0);	
				return false;
				
			}
			else
			{
				//set cookie, breeder didn't fill out his data 
				ExtraFunctions::setCookie(\Yii::$app->params['breeder_data_cookie'], 1);
				return true;
			}
		}
		else if($breeder_data==0)
			return false;
		else if($breeder_data==1)
			return true;
	}
	
	
	/*
	* recurzive function check if file exists on upload
	* $target_file = $target_dir.basename($FILE_NAME);
	* $target_dir = Yii::getAlias('@webroot').$UPLOAD_DIR;
	* $FILE_NAME - already changed file name inside upload function: mt_rand()
	* return target_file with target directory and file name
	*/
	private static function fileExists($target_file, $target_dir, $FILE_NAME)
	{
		if (file_exists($target_file)) 
		{
			//rename
			$temp = explode(".",$FILE_NAME);
			$FILE_NAME=mt_rand().'.'.end($temp);
			$target_file = $target_dir.basename($FILE_NAME);
			return self::fileExists($target_file, $target_dir, $FILE_NAME);
		}
		
		return ['target_file'=>$target_file, 'FILE_NAME'=>$FILE_NAME];
	}

	/*
	* for uploading single image or pedigree in Create() and Update()
	* $model -> update or create
	* $render - render update or create view
	* $UPLOAD_DIR = Yii::getAlias('@web') because you have inside function $target_dir = Yii::getAlias('@webroot').$UPLOAD_DIR;
	* $FILE_FIELD - column in database where I save  name of uploaded image
	* $IMAGE_SIZE - max size for image/file in bytes
	* DON'T FORGET TO PUT 'options'=>["enctype"=>"multipart/form-data"] in <form>
	*/
	public static function uploadImage($_FILES_NAME,$_FILES_TMP_NAME,$_FILES_SIZE, $FILES_ERROR, $model, $render, $UPLOAD_DIR, $FILE_FIELD, $IMAGE_SIZE)
	{
		$allowedImageTypes=["jpg", "jpeg", "png", "gif", "JPG", "JPEG", "PNG"];
		$allowedFileTypes=["pdf"];
		
		$target_dir = Yii::getAlias('@webroot').$UPLOAD_DIR;
		$oldName=$_FILES_NAME;
		
		//rename
		$temp = explode(".",$_FILES_NAME);
		$FILE_NAME=mt_rand().'.'.end($temp);
		$target_file = $target_dir.basename($FILE_NAME);

		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		
		//if it is picture extension then check for image size to make sure it is really image
		if(in_array($imageFileType,$allowedImageTypes))
		{
			// Check if image file is a actual image or fake image
			$check = getimagesize($_FILES_TMP_NAME);
			if($check !== false) 
			{
				$uploadOk = 1;
			} 
			else 
			{
				$uploadOk = 0;
			}		
		}
		
		
		// Check if file already exists
		$fileExists=self::fileExists($target_file, $target_dir, $FILE_NAME);

		// Check file size
		if ($_FILES_SIZE > $IMAGE_SIZE)  
		{
			$uploadOk = 0;
			$max_size=round(Pigeon::MAX_IMAGE_SIZE_PIGEON_EYE_IMAGE/1024/1024);
			Yii::$app->session->addFlash('danger', Yii::t('default','Image is too big', ['0'=>($max_size)]));
		}
		 
		// Allow certain file formats
		if(!in_array($imageFileType,$allowedImageTypes) && !in_array($imageFileType, $allowedFileTypes)) 
		{
			$uploadOk = 0;
		}
		
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) 
		{
			Yii::$app->session->addFlash('danger', Yii::t('default','Image was not uploaded, try again', ['0'=>$oldName]));
		} 
		// if everything is ok, try to upload file
		else
		{ 
			if (move_uploaded_file($_FILES_TMP_NAME, $fileExists["target_file"])) 
			{
				//first delete old picture
				if($render=="update" && ($model->$FILE_FIELD!=ExtraFunctions::NO_PICTURE && $model->$FILE_FIELD!=ExtraFunctions::NO_EYE))
				{
					unlink($target_dir.$model->$FILE_FIELD);
				}
				Yii::$app->session->addFlash('success', Yii::t('default','Set flash for success', ['0'=>$oldName]));

			}
			else
			{
				Yii::$app->session->addFlash('danger', Yii::t('default','Image was not uploaded, try again', ['0'=>$oldName]));
				$uploadOk=0;
			}
		}	
		
		return  ['FILE_NAME'=>$fileExists["FILE_NAME"], 'uploadOk'=>$uploadOk];
	}

	//http://www.w3bees.com/2013/02/multiple-file-upload-with-php.html
	public static function multipleImageUpload($_FILES_NAME, $_FILES_ERROR, $_FILES_SIZE, $_FILES_TMP_NAME, $maxFilesUploadAllowed=NULL)
	{
		$valid_formats = array("jpg", "png", "gif", "pdf", "bmp", "jpeg");
		$max_file_size = Auction::IMAGE_SIZE; //2mb
		$path = Yii::getAlias('@webroot').Auction::UPLOAD_DIR_IMAGES; // Upload directory
		$count = 1;
		if($maxFilesUploadAllowed==NULL)
			$maxFilesUploadAllowed=10;
			
		$failed=[]; $success=[]; $images=NULL;
		
		if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
		{
			// Loop $_FILES to exeicute all files
			foreach ($_FILES_NAME as $f => $name) 
			{    
				$originalName=$name;
				if ($_FILES_ERROR[$f] == 4) 
				{
					continue; // Skip file if any error found
				}	       
				if ($_FILES_ERROR[$f] == 0) 
				{	           
					if ($_FILES_SIZE[$f] > $max_file_size) 
					{
						$failed[] = Yii::t('default', 'File is too large', ['0'=>$name]);
						continue; // Skip large files
					}
					elseif( ! in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats) )
					{
						$failed[] = Yii::t('default', 'Not valid format', ['0'=>$name]);
						continue; // Skip invalid file formats
					}
					else
					{ 
						//rename
						$temp = explode(".",$name);
						$name = mt_rand().'.'.end($temp);

						// Check if file already exists
						/*
						* $target_file = $target_dir.basename($FILE_NAME);
						* $target_dir = Yii::getAlias('@webroot').$UPLOAD_DIR;
						* $_FILES_NAME - original file name
						*/
						$fileExists=self::fileExists($path.$name, $path, $name);
						
						//user can upload maximum 10 images
						if($count>$maxFilesUploadAllowed)
							continue;
						// No error found! Move uploaded files 
						else if(move_uploaded_file($_FILES_TMP_NAME[$f], $fileExists["target_file"]))
						{
							$count++; // Number of successfully uploaded file
							$success[]=$originalName;
							$images[]=$fileExists["FILE_NAME"];
						}
					}
				}//if ($_FILES['files']['error'][$f] == 0) 
			}//foreach
		}//if isset($_POST)
		
		if(!empty($failed))
		{
			$implode=implode("<br>", $failed);
			Yii::$app->session->addFlash('danger', Yii::t('default','Set flash for failed', ['0'=>$implode]));

		}
		if(!empty($success))
		{
			$implode=implode("<br>", $success);
			Yii::$app->session->addFlash('success', Yii::t('default','Set flash for success', ['0'=>$implode]));

		}
		
		return $images;
	}


	/**
	* upload any file type
	* $UPLOAD_DIR = Yii::getAlias('@web') because you have inside function $target_dir = Yii::getAlias('@webroot').$UPLOAD_DIR;
	* $IMAGE_SIZE - max size for image/file in bytes
	* DON'T FORGET TO PUT 'options'=>["enctype"=>"multipart/form-data"] in <form>
	*/
	public static function uploadAnyFile($_FILES_NAME,$_FILES_TMP_NAME,$_FILES_SIZE, $FILES_ERROR, $UPLOAD_DIR, $IMAGE_SIZE)
	{
		$target_dir = Yii::getAlias('@webroot').$UPLOAD_DIR;
		$oldName=$_FILES_NAME;
		
		//rename
		$temp = explode(".",$_FILES_NAME);
		$FILE_NAME=mt_rand() . '.' .end($temp);
		$target_file = $target_dir . basename($FILE_NAME);

		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		
		// Check if file already exists
		$fileExists=self::fileExists($target_file, $target_dir, $FILE_NAME);
		
		// Check file size
		if ($_FILES_SIZE > $IMAGE_SIZE)  
		{
			$uploadOk = 0;
			$max_size=round(Pigeon::MAX_IMAGE_SIZE_PIGEON_EYE_IMAGE/1024/1024);
			Yii::$app->session->addFlash('danger', Yii::t('default','Image is too big', ['0'=>($max_size)]));
		}
		
		
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) 
		{
			Yii::$app->session->addFlash('danger', Yii::t('default','Image was not uploaded, try again', ['0'=>$oldName]));
		} 
		// if everything is ok, try to upload file
		else
		{ 
			if (move_uploaded_file($_FILES_TMP_NAME, $fileExists["target_file"])) 
			{
				Yii::$app->session->addFlash('success', Yii::t('default','Set flash for success', ['0'=>$oldName]));

			}
			else
			{
				Yii::$app->session->addFlash('danger', Yii::t('default','Image was not uploaded, try again', ['0'=>$oldName]));
				$uploadOk=0;
			}
		}	
		
		return  ['FILE_NAME'=>$fileExists["FILE_NAME"], 'uploadOk'=>$uploadOk];
	}
	
	
	/**
	* SHARE BUTTONS
	*/
	public function shareButtons()
	{
		echo
		'
			<!-- SHARE -->
			<style>
			.share-buttons{
			list-style: none;
			padding:0;
			
			}
			
			.share-buttons li{
				display: inline;
			}
			</style>
		<ul class="share-buttons">
			<li><a href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fwww.pippion.com%2Fsite%2Fwhat-is-pippion&t=Pippion%20%7C%20World\'s%20leading%20pigeon%20breeding%2Fracing%20application" target="_blank"><img src="/images/share_btns/Facebook.png"></a></li>
			<li><a href="https://twitter.com/intent/tweet?source=https%3A%2F%2Fwww.pippion.com%2Fsite%2Fwhat-is-pippion&text=Pippion%20%7C%20World\'s%20leading%20pigeon%20breeding%2Fracing%20application:%20https%3A%2F%2Fwww.pippion.com%2Fsite%2Fwhat-is-pippion" target="_blank" title="Tweet"><img src="/images/share_btns/Twitter.png"></a></li>
			<li><a href="https://plus.google.com/share?url=https%3A%2F%2Fwww.pippion.com%2Fsite%2Fwhat-is-pippion" target="_blank" title="Share on Google+"><img src="/images/share_btns/Google.png"></a></li>
			<li><a href="http://www.tumblr.com/share?v=3&u=https%3A%2F%2Fwww.pippion.com%2Fsite%2Fwhat-is-pippion&t=Pippion%20%7C%20World\'s%20leading%20pigeon%20breeding%2Fracing%20application&s=" target="_blank" title="Post to Tumblr"><img src="/images/share_btns/Tumblr.png"></a></li>
			<li><a href="http://pinterest.com/pin/create/button/?url=https%3A%2F%2Fwww.pippion.com%2Fsite%2Fwhat-is-pippion&media=https://www.pippion.com/images/pippion_logo.jpg&description=Pippion%20-%20Pigeon%20Racing%20and%20Breeding%20Management%20Software%2C%20Auctions%20and%20Classified%20Ads%20for%20Pigeon%20Breeders%2C%20Racers%20And%20Fanciers.%20Everything%20You%20Need%20From%20Hatching%2C%20Pairing%20To%20Racing%20And%20Selling" target="_blank" title="Pin it"><img src="/images/share_btns/Pinterest.png"></a></li>
			<li><a href="http://www.reddit.com/submit?url=https%3A%2F%2Fwww.pippion.com%2Fsite%2Fwhat-is-pippion&title=Pippion%20%7C%20World\'s%20leading%20pigeon%20breeding%2Fracing%20application" target="_blank" title="Submit to Reddit"><img src="/images/share_btns/Reddit.png"></a></li>
			<li><a href="http://www.linkedin.com/shareArticle?mini=true&url=https%3A%2F%2Fwww.pippion.com%2Fsite%2Fwhat-is-pippion&title=Pippion%20%7C%20World\'s%20leading%20pigeon%20breeding%2Fracing%20application&summary=Pippion%20-%20Pigeon%20Racing%20and%20Breeding%20Management%20Software%2C%20Auctions%20and%20Classified%20Ads%20for%20Pigeon%20Breeders%2C%20Racers%20And%20Fanciers.%20Everything%20You%20Need%20From%20Hatching%2C%20Pairing%20To%20Racing%20And%20Selling&source=https%3A%2F%2Fwww.pippion.com%2Fsite%2Fwhat-is-pippion" target="_blank" title="Share on LinkedIn"><img src="/images/share_btns/LinkedIn.png"></a></li>
		</ul>			
			<!-- SHARE -->
		';
	}

	/*
	* provjerava koliko ima datoteka u folderu za wallpapers, pamti i uzima random broj između 1 i tog broja
	* služi za slučajni izbor cover fotki za profil i za login nstranici
	*/
	public static function Wallpapers()
	{
		$i = 0; 
		$dir = $_SERVER['DOCUMENT_ROOT'].'/images/wallpapers/';
		$files=scandir($dir);
		foreach($files as $key=>$value)
		{
			$ext = pathinfo($dir.$value,PATHINFO_EXTENSION);
			if($ext=="jpg")
				$i++; 
		}
		return rand(1,$i).".jpg";
	}
	
	/*
	* Format any date as "m.d.Y. H:i:s"
	*/
	public static function formatDate($time, $custom=NULL)
	{
		$date = new \DateTime($time);

		if($custom!=NULL)
		{
			if($custom=="ymd")
				$return=$date->format('d.m.Y');
			else if($custom=="ymd-his")
				$return=$date->format('d.m.Y H:i:s');
		}
		else
		{
			if($date->format('H:i:s')=='00:00:00')
				$return=$date->format('d.m.Y');
			else
				$return=$date->format('d.m.Y H:i:s');
		}
			
		return $return;
	}
	
	/*
	* prikaži dropdown listu za izbor jezika u headeru
	*/
	public function prikaziJezike()
	{
		echo '<select onChange="izaberiJezik(this)" class="form-control">
				  <option value="-">Language</option>
				  <option value="en">English</option>
				  <option value="hr">Hrvatski</option>
				  <option value="hu">Magyar</option>
				  <option value="de">Deutsch</option>
				  <option value="nl">Dutch</option>
				  <option value="zh-cn">中国</option>
				  <option value="fr">Français</option>
				</select>';
	}
	
	/*
	* return date depends on desired format
	*/
	public static function currentTime($format)
	{
		if($format=="ymd")
			$return = date("Y-m-d");
		if($format=="ymd-his")
			$return = date("Y-m-d H:i:s");			
		return $return;
	}
	
	/*
	* return 2 types of time:
	* 1. is Y-m-d 00:00:00 (beginning of the day) 
	* 2. is Y-m-d 23:59:59 (end of the day)
	* or if no time is set return NULL
	* mostly for search model is used
	* $time -> Y-m-d- H:i:s
	*/
	public function between_0_24($time, $timeofday)
	{		
		$date = new \DateTime($time);

		if($time==NULL || empty($time))
			$return=NULL;
		else if($timeofday=="beginning")
			$return=$date->format('Y-m-d 00:00:00');
		else
			$return=$date->format('Y-m-d 23:59:59');
			
		return $return;
	}
	
	/*
	* return 2 types of time:
	* 1. is yyyy-01-01 (beginning of the year) 
	* 2. is yyyy-12-31 (end of the year)
	* or if no time is set return NULL
	* mostly for search model is used in RacingTableSearch
	* $time -> year (2004, 2014...)
	*/
	public function between_1_365($time, $timeofyear)
	{		
		if($time==NULL || empty($time))
			$return=NULL;
		else if($timeofyear=="beginning")
			$return=$time.'-01-01';
		else
			$return=$time.'-12-31';
			
			
			
		return $return;
	}
	
	//PARA: Date Should In YYYY-MM-DD Format
	//RESULT FORMAT:
	// %R for sign +/-
	// '%y Year %m Month %d Day %h Hours %i Minute %s Seconds'        =>  1 Year 3 Month 14 Day 11 Hours 49 Minute 36 Seconds
	// '%y Year %m Month %d Day'                                    =>  1 Year 3 Month 14 Days
	// '%m Month %d Day'                                            =>  3 Month 14 Day
	// '%d Day %h Hours'                                            =>  14 Day 11 Hours
	// '%d Day'                                                        =>  14 Days
	// '%h Hours %i Minute %s Seconds'                                =>  11 Hours 49 Minute 36 Seconds
	// '%i Minute %s Seconds'                                        =>  49 Minute 36 Seconds
	// '%h Hours                                                    =>  11 Hours
	// '%a Days                                                        =>  468 Days
	//	$date_1=start date (lower), $date_2=end date (higher)
	//	if $date_1 is lower and date_2 is higher you will get positive calculation
	//////////////////////////////////////////////////////////////////////
	public static function dateDifference($date_1 , $date_2, $format)
	{
		//difference between current time and end time
		if($format=="y")
			$differenceFormat = '%y';
		else if($format=="sign-m")
			$differenceFormat = '%R %m';
		else if($format=="m-dhm")
			$differenceFormat = '%R %m '.Yii::t('default', 'EF Month').' %d '.Yii::t('default', 'EF Day').' %h '.Yii::t('default', 'EF Hour').' %i '.Yii::t('default', 'EF Minute').'';
		else if($format=="ymd-hm")
			$differenceFormat = '%R %y '.Yii::t('default', 'EF Year').' %m '.Yii::t('default', 'EF Month').' %d '.Yii::t('default', 'EF Day').' %h '.Yii::t('default', 'EF Hour').' %i '.Yii::t('default', 'EF Minute').'';
		else if($format=="sign")
			$differenceFormat = '%R';
		else if($format=="md-hm")
			$differenceFormat = '%R %m '.Yii::t('default', 'EF Month').' %d '.Yii::t('default', 'EF Day').' %h '.Yii::t('default', 'EF Hour').' %i '.Yii::t('default', 'EF Minute').'';
		else if($format=="d")
			$differenceFormat = '%R%d';
		
		$datetime1 = date_create($date_1);
		$datetime2 = date_create($date_2);
		
		$interval = date_diff($datetime1, $datetime2);

		//if($interval->format("%R")=="-")
		//	return "Negative value";
		return $interval->format($differenceFormat);
	}
	
	/*
	* Get current date and than add 1 year to current date and return both
	* mainly for subscriptions
	* $amount - how many years or months to add
	* $subscription_type - is it Month or Year to add
	*/
	public static function subscriptionDates($amount, $subscription_type)
	{
		if($subscription_type==Subscription::SUBSCRIPTION_TYPE_MONTH)
			$type_tmp="M";
		else
			$type_tmp="Y";
		$date = new \DateTime;
		$today = $date->format('Y-m-d H:i:s');
		$date->add(new \DateInterval("P$amount$type_tmp"));
		$todayplus = $date->format('Y-m-d H:i:s');
		
		return ['today'=>$today, 'todayplus'=>$todayplus];
	}


	/**
	* when someone sends email to me
	* $both - do you want to send email to adminEmail and adminEmail2?
	*/
	public static function sendEmail($sender_name, $sender_email, $subject, $message, $both=false, $loadAutoLoader=true)
	{
		if($loadAutoLoader==true)
			require_once Yii::getAlias('@common').'/phpmailer/PHPMailerAutoload.php';

		//Create a new PHPMailer instance
		$mail = new \PHPMailer;
		$mail->CharSet = 'UTF-8';
		//Set who the message is to be sent from
		$mail->setFrom($sender_email, $sender_name);
		//Set who the message is to be sent to
		$mail->addAddress(Yii::$app->params['adminEmail'], "Pippion");
		if($both==true)
			$mail->addAddress(Yii::$app->params['adminEmail2'], "Pippion");
		//Set the subject line
		$mail->Subject = $subject;
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$mail->msgHTML($message);
		$mail->send();
	}	
	
	/**
	* send email to someojne from me
	* $loadAutoLoader - whether to load PHPMailerAutoload.php when I try to send multiple emails on one page. PHPMailerAutoload needs to be loaded only one
	*/
	public static function sendEmailToSomeone($send_to_email, $send_to_name, $subject, $message, $loadAutoLoader=true)
	{
		if($loadAutoLoader==true)
			require_once Yii::getAlias('@common').'/phpmailer/PHPMailerAutoload.php';
	
		//Create a new PHPMailer instance
		$mail = new \PHPMailer;
		$mail->CharSet = 'UTF-8';
		//Set who the message is to be sent from
		$mail->setFrom("dario.trbovic@pippion.com", "[Pippion] Dario Trbovic");
		//Set who the message is to be sent to
		$mail->addAddress($send_to_email, $send_to_name);
		//Set the subject line
		$mail->Subject = $subject;
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$mail->msgHTML($message);
		$mail->send();
	}	
	
	/*
	* twitter and facebook buttons for like and follow
	*/
	public static function twitterFacebookButton()
	{
		ob_start();
		?>
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3&appId=375971292568983";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
		
        <div class="fb-like" data-href="https://www.facebook.com/PippionCom" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>		
        <br /><br />
		<a href="https://twitter.com/PippionCom" class="twitter-follow-button" data-show-count="false">Follow @PippionCom</a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
        <?php
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
	
	/*
	* On user registration inser breeder data, subscription, send email to me and to regitered user
	*/
	public static function onUserRegistration($data)
	{
		//insert into mg_breeder, create breeder's profile
		$breeder=new Breeder;
		$breeder->IDuser=$data->id;
		$breeder->name_of_breeder="-"; //important to be "-" because it cannot be empty and and I use it to check in ExtraFunction -> beforeActionBreederData()
		$breeder->country=98; 
		$breeder->town="-"; 
		$breeder->address="-"; 
		$breeder->tel1="-"; 
		$breeder->email1="-"; 
		$breeder->save();

		//INSERT SUBSCRIPTION
		$date = new \DateTime();
		$today=$date->format('Y-m-d H:i:s');
		$date->add(new \DateInterval('P15Y'));//add 1 month
		$plus60days=$date->format('Y-m-d H:i:s');
		//save it
		$sub=new Subscription;
		$sub->IDuser=$data->id;
		$sub->start_date=$today;
		$sub->end_date=$plus60days;
		$sub->price=0;
		$sub->status='free_trial';
		$sub->save();
		
		//send me new email about registration
		require_once Yii::getAlias('@common').'/phpmailer/PHPMailerAutoload.php';

		//Create a new PHPMailer instance
		$mail = new \PHPMailer;
		//Set who the message is to be sent from
		$mail->setFrom(Yii::$app->params['adminEmail'], 'New user');
		//Set who the message is to be sent to
		//$mail->addAddress(Yii::$app->params['adminEmail'], 'New user');
		$mail->addAddress(Yii::$app->params['adminEmail2'], 'New user');
		//Set the subject line
		$mail->Subject = "New user";
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$mail->msgHTML("<strong>New user has registered</strong><br>".$data->username."<br>".$data->email);
		$mail->send();
		
		$mail2 = new \PHPMailer;
		//SEND EMAIL TO NEW USERS
		$mail2->setFrom(Yii::$app->params['adminEmail'], 'Dario Trbovic');
		//Set who the message is to be sent to
		//$mail->addAddress(Yii::$app->params['adminEmail'], 'New user');
		$mail2->addAddress($data->email, $data->username);
		//Set the subject line
		$mail2->Subject = "Welcome to Pippion :)";
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$mail2->msgHTML("
		Dear $data->username<br><br>
		Thank you very much for joining Pippion. If you have any questions about Pippion or anything related to it, let us know. We will respond as soon as possible. :)<br><br>
		
		Regards,<br><br>
		Team Pippion.
		<br><br>
		Dario Trbovic, CEO (Web Developer) - dario.trbovic@pippion.com<br><br>
		Ivan Jurlina, Business Executive - ivan.jurlina@pippion.com<br><br>
		Mario Hribar, CTO (Android Developer) - mario.hribar@pippion.com<br><br>
		https://www.pippion.com
		");
		$mail2->send();
	}
	
	/*
	* share buttons for anything you want to enable to share
	* $urls - url to share on facebook, twitter e.g.Url::to(['/auction/view','id'=>$data->ID])
	* $text - array of text to share on facebook or twitter e.g. see this auction
	*/
	public static function shareAnythingButtons($url, $text)
	{
		return
		'
			<ul class="share-buttons">
			  <li><a href="https://www.facebook.com/sharer/sharer.php?u='.ExtraFunctions::pippionFullUrl().$url.'&t=" title="Share on Facebook" target="_blank"><img src="/images/share_btns/Facebook.png"></a></li>
			  <li><a href="https://twitter.com/intent/tweet?source='.ExtraFunctions::pippionFullUrl().$url.'&text='.$text["twitter"].'" target="_blank" title="Tweet"><img src="/images/share_btns/Twitter.png"></a></li>
			</ul>		
		';
	}
	
	/*
	* PayPal buy now button
	* $sandbox - is it sandbox button
	*/
	public static function payPalBuyNow($sandbox, $item_name, $amount, $currency, $custom=NULL, $ipn_url=NULL, $return_url=NULL)
	{
		if($sandbox==true)
			$url='https://www.sandbox.paypal.com/cgi-bin/webscr';
		else
			$url='https://www.paypal.com/cgi-bin/webscr';
			
		$return=NULL;
		$return.='<p><form action="'.$url.'" method="post">';
		$return.='
		<!-- Identify your business so that you can collect the payments. -->
		<input type="hidden" name="business" value="'.\Yii::$app->params['paypalEmail'].'">
		
		<!-- Specify a Buy Now button. -->
		<input type="hidden" name="cmd" value="_xclick">
		
		<!-- Specify details about the item that buyers will purchase. -->
		<input type="hidden" name="item_name" value="'.$item_name.'">
		<input type="hidden" name="amount" value="'.$amount.'">
		<input type="hidden" name="currency_code" value="'.$currency.'">
		
		<!-- Display the payment button. -->
		<input type="image" name="submit" border="0"
		src="https://www.paypalobjects.com/webstatic/en_US/btn/btn_paynow_cc_144x47.png"
		alt="PayPal - The safer, easier way to pay online" style="border:none;" >
		<img alt="" border="0" width="1" height="1"
		src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" style="border:none;" >';
		if(!empty($custom))
			$return.='<input type="hidden" name="custom" value="'.$custom.'">';

		if(!empty($ipn_url))
			$return.='<input type="hidden" name="notify_url" value="'.$ipn_url.'">';
			
		if(!empty($return_url))
			$return.='<input type="hidden" name="return" value="'.$return_url.'">';
			
		$return.='</form></p>';
		return $return;
	}
	
	/*
	* set cookie
	*/
	public static function setCookie($cookie_name, $cookie_value)
	{
		$cookies = Yii::$app->response->cookies;

		// add a new cookie to the response to be sent
		$cookies->add(new \yii\web\Cookie([
			'name' => $cookie_name,
			'value' => $cookie_value,
			'expire' => time() + (60*60*24*365*10) //current time + 10 years
		]));
	}
	
	/*
	* get cookie
	*/
	public static function getCookie($cookie_name)
	{
		// get the cookie collection (yii\web\CookieCollection) from the "request" component
		$cookies = Yii::$app->request->cookies;
		
		// an alternative way of getting the "language" cookie value
		if (($cookie = $cookies->get($cookie_name)) !== null) 
		{
			return $cookie->value;
		}
		else 
			return NULL;

	}
}