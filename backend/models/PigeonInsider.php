<?php
namespace backend\models;
use yii\base\Model;
use Yii;

class PigeonInsider extends Model
{
	
	public $pigeon_name;
	public $pigeon_number;
	public $breeder_name;
	public $address;
	public $tel;
	public $mob;
	public $email;
	public $web;
	public $place;
	public $competition;
	public $release_place;
	public $distance;
	public $total_pigeons;
	
	public function attributeLabels()
    {
        return [
			'pigeon_name'=>Yii::t('default', 'Pigeon name'),
			'pigeon_number'=>Yii::t('default', 'Pigeon number'),
			'breeder_name'=>Yii::t('default', 'Breeder name'),
			'address'=>Yii::t('default', 'Address'),
			'tel'=>Yii::t('default', 'UZG_TEL'),
			'mob'=>Yii::t('default', 'UZG_MOB'),
			'email'=>Yii::t('default', 'Email'),
			'web'=>Yii::t('default', 'UZG_WEBSITE'),
			'place'=>Yii::t('default', 'Osv Mjesto'),
			'competition'=>Yii::t('default', 'Competition'),
			'release_place'=>Yii::t('default', 'Mjesto Pustanja'),
			'distance'=>Yii::t('default', 'Udaljenost'),
			'total_pigeons'=>Yii::t('default', 'Total pigeons'),
        ];
    }
	
	public function rules()
	{
		return [
			// the name, email, subject and body attributes are required
			[['place','competition','release_place','distance','total_pigeons'], 'string'],
			[['pigeon_name','pigeon_number','breeder_name','address','tel','mob','email','web'], 'string'],
		];
	}
	
	/*
	* Function for creating pigeon insider
	*/
	private function pigeonInsiderCheckImageFile($file, $imgname, $imageSaveDestination)
	{
		$FILE_NAME=$_FILES[$file]["name"];
		$FILE_TYPE=$_FILES[$file]["type"];
		$FILE_ERROR=$_FILES[$file]["error"];
		$FILE_TMP_NAME=$_FILES[$file]["tmp_name"];
		//SAVING PICTURES
		$allowedExts = array("jpeg", "jpg", "png");
		$temp = explode(".", $FILE_NAME);
		$extension = end($temp);
		
		if( (($FILE_TYPE == "image/jpeg") || ($FILE_TYPE == "image/jpg") || ($FILE_TYPE == "image/pjpeg") || ($FILE_TYPE == "image/x-png")	|| ($FILE_TYPE == "image/png")) && in_array($extension, $allowedExts)	) 
		{
		  if ($FILE_ERROR > 0) 
		  {
			echo "Return Code: " . $FILE_ERROR . "<br>";
		  } 
		  else 
		  {
			  
			/*echo "Upload: " . $_FILES[$file]["name"] . "<br>";
			echo "Type: " . $_FILES[$file]["type"] . "<br>";
			echo "Size: " . ($_FILES[$file]["size"] / 1024) . " kB<br>";
			echo "Temp file: " . $_FILES[$file]["tmp_name"] . "<br>";*/
			if (file_exists($imageSaveDestination . $FILE_NAME)) 
			{
			   $FILE_NAME =mt_rand().".".$extension ;
			} 
			
			$newName=$file."_".$imgname.".".$extension;
			move_uploaded_file($FILE_TMP_NAME,  $imageSaveDestination.$newName);
			//echo "Stored in: " . "upload/" . $_FILES[$file]["name"];
			
			//check image dimensions
			list($width, $height) = getimagesize($imageSaveDestination . $newName);
			if($file=='pigeon_pic')
			{
				$maxWidth = $maxHeight = 450;
			}
			else
			{
				$maxWidth = $maxHeight = 280; 
			}
			$ratio = 0;  // Used for aspect ratio
			
			// Check if the current width is larger than the max
			if($width > $maxWidth)
			{
				$ratio = $maxWidth / $width;   // get ratio for scaling image
				$newwidth=$maxWidth; // Set new width
				$newheight=$height * $ratio;  // Scale height based on ratio
			}
			
			// Check if current height is larger than max
			else if($height > $maxHeight)
			{
				$ratio = $maxHeight / $height; // get ratio for scaling image
				$newheight=$maxHeight;   // Set new height
				$newwidth=$width * $ratio;    // Scale width based on ratio
			}
			else
			{
				$newwidth=$width;    // Scale width based on ratio
				$newheight=$height;  // Scale height based on ratio
			}

			return array("width"=>$width, "height"=>$height, "newwidth"=>$newwidth, "newheight"=>$newheight, "ext"=>$extension, "newName"=>$newName);
		  }
		} 
		else 
		{
		  echo "Invalid file";
		  die();
		}
	}
	
	public function createPigeonInsider($model)
	{
		//-----------------------SETTINGS------------------------
		//header("Content-Type: image/png");
		//mjera za fontova u pixelima, sve je to otprilike
		$podaciFont=12;
		$naslovFont=12;
		
		//header text
		$txt_place=Yii::t('default', 'Osv Mjesto');
		$txt_competition=Yii::t('default', 'Competition');
		$txt_place_of_release=Yii::t('default', 'Mjesto Pustanja');
		$txt_distance=Yii::t('default', 'Udaljenost')." (km)";
		$txt_total_pigeons=Yii::t('default', 'Total pigeons');
		
		$imgname="pigeon_insider_".mt_rand().".png";
		$imgname_noext="pigeon_insider_".mt_rand();
		$imageSaveDestination=$_SERVER['DOCUMENT_ROOT']."/temp/";
		$pigeonName='"'.$model->pigeon_name.'"';
		$pigeon_number='['.$model->pigeon_number.']';
		$font = $_SERVER['DOCUMENT_ROOT'].'/assets/fonts/OpenSans-Light.ttf';
		$hide_competition=$_POST["hide_competition"];
		//-----------------------SETTINGS------------------------
		
		//SAVING FILES
		if(isset($_FILES["pigeon_pic"]["name"]) && !empty($_FILES["pigeon_pic"]["name"]))
			$pigeon_pic_info=$this->pigeonInsiderCheckImageFile("pigeon_pic", $imgname_noext, $imageSaveDestination);
		else
			$pigeon_pic_info=false;
			
		if(isset($_FILES["eye_pic"]["name"]) && !empty($_FILES["eye_pic"]["name"]))
			$eye_pic_info=$this->pigeonInsiderCheckImageFile("eye_pic", $imgname_noext, $imageSaveDestination);
		else
			$eye_pic_info=false;
		
		// Create the image
		$src_im = imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT'].'/images/insider_template.jpg')
			or die("Cannot Initialize new GD image stream");
		 
		// Allocate a background color of image.
		$bg_color = imagecolorallocate($src_im,255,255,255);
		 
		$black = imagecolorallocate($src_im,0,0,0);
		$white = imagecolorallocate($src_im,255,255,255);
		$gray = imagecolorallocate($src_im,200,200,200);
		
		//pigeon name
		imagettftext($src_im, 16, 0, 45, 30, $black, $font, $pigeon_number);
		imagettftext($src_im, 16, 0, 45+strlen($pigeon_number)*$naslovFont, 30, $black, $font, $pigeonName);
		
		
		//GET DATA
		$place=array();
		$competition=array();
		$release_place=array();
		$distance=array();
		$total_pigeons=array();
		for($i=0;$i<10;$i++)
		{
			$model->attributes=$_POST['PigeonInsider'][$i];
			array_push($place, $model->place);
			array_push($competition, $model->competition);
			array_push($release_place, $model->release_place);
			array_push($distance, $model->distance);
			array_push($total_pigeons, $model->total_pigeons);
		}
		
		//go trought all submitted data and get biggest strlen for each column
		$maxtotalpigeons=strlen($txt_total_pigeons);
		for($i=0;$i<count($total_pigeons);$i++)
		{
			if(strlen($total_pigeons[$i])>$maxtotalpigeons)
			{
				$maxtotalpigeons=strlen($total_pigeons[$i]);
			}
		}
		
		$maxrelease_place=strlen($txt_place_of_release);
		for($i=0;$i<count($release_place);$i++)
		{
			if(strlen($release_place[$i])>$maxrelease_place)
			{
				$maxrelease_place=strlen($release_place[$i]);
			}
		}
		
		$maxdistance=strlen($txt_distance);
		for($i=0;$i<count($distance);$i++)
		{
			if(strlen($distance[$i])>$maxdistance)
			{
				$maxdistance=strlen($distance[$i]);
			}
		}
		
		
		/*
		osvojeno mjestu na natjecateljskom letu
		mjesto mpuštanja golubova
		udaljenost km
		broj golubova koji je sudjelovao na letu.
		konkurencija u kojoj se natječe (klub, udruga klubova, udruga više udruga klubova, nacionalni ili internacionalni let)
		*/
		//CALCULATE TEXT MOVEMENT
		$move0=90;
		$move1=$maxrelease_place*$podaciFont;
		$move2=$maxdistance*$podaciFont;
		$move3=$maxtotalpigeons*$podaciFont+30;
		$y=100;
		
		//header
		imagettftext($src_im, 12, 0, 45, $y, $black, $font, $txt_place);
		imagettftext($src_im, 12, 0, 45+$move0, $y, $black, $font, $txt_place_of_release);
		imagettftext($src_im, 12, 0, 45+$move0+$move1, $y, $black, $font, $txt_distance);
		imagettftext($src_im, 12, 0, 45+$move0+$move1+$move2, $y, $black, $font, $txt_total_pigeons);
		if($hide_competition=="false")
			imagettftext($src_im, 12, 0, 45+$move0+$move1+$move2+$move3, $y, $black, $font, $txt_competition);

		imageline ($src_im , 45, $y+5, 955, $y+5, $black);
		
		//other data
		for($i=0;$i<count($competition);$i++)
		{
			if(empty($place[$i]) && empty($competition[$i]) && empty($release_place[$i]) && empty($distance[$i]) && empty($total_pigeons[$i]) )
				continue;
			$y+=30;
			imagettftext($src_im, 12, 0, 45, $y, $black, $font, $place[$i]);
			imagettftext($src_im, 12, 0, 45+$move0, $y, $black, $font, $release_place[$i]);
			imagettftext($src_im, 12, 0, 45+$move0+$move1, $y, $black, $font, $distance[$i]);
			imagettftext($src_im, 12, 0, 45+$move0+$move1+$move2, $y, $black, $font, $total_pigeons[$i]);
			if($hide_competition=="false")
				imagettftext($src_im, 12, 0, 45+$move0+$move1+$move2+$move3, $y, $black, $font, $competition[$i]);

			imageline ($src_im , 45, $y+5, 955, $y+5, $gray);
		}
		
		imagettftext($src_im, 12, 0, 610, 480+280+35, $black, $font, $model->breeder_name);
		imagettftext($src_im, 12, 0, 610, 480+280+65, $black, $font, $model->address);
		imagettftext($src_im, 12, 0, 610, 480+280+95, $black, $font, Yii::t('default', 'UZG_TEL')." ".$model->tel." / ".Yii::t('default', 'UZG_MOB')." ".$model->mob);
		imagettftext($src_im, 12, 0, 610, 480+280+125, $black, $font, $model->email);
		imagettftext($src_im, 12, 0, 610, 480+280+155, $black, $font, $model->web);

		imagepng($src_im,$_SERVER['DOCUMENT_ROOT'].'/temp/'.$imgname);

	
		//merge pigeon pic
		$dest = imagecreatefrompng($imageSaveDestination.$imgname);
		//first, picture of the pigeon
		if($pigeon_pic_info!=false && ($pigeon_pic_info["ext"]=="jpeg" || $pigeon_pic_info["ext"]=="jpg"))
			$src_temp = imagecreatefromjpeg($imageSaveDestination.$pigeon_pic_info["newName"]);
		else if($pigeon_pic_info!=false && ($pigeon_pic_info["ext"]=="png"))
			$src_temp = imagecreatefrompng($imageSaveDestination.$pigeon_pic_info["newName"]);
			
		if($pigeon_pic_info!=false)
			imagecopyresized($dest, $src_temp, 40, 480, 0, 0, $pigeon_pic_info["newwidth"], $pigeon_pic_info["newheight"], $pigeon_pic_info["width"], $pigeon_pic_info["height"]);

		//second, picture of the eye
		if($eye_pic_info!=false && ($eye_pic_info["ext"]=="jpeg" || $eye_pic_info["ext"]=="jpg"))
			$src_temp = imagecreatefromjpeg($imageSaveDestination.$eye_pic_info["newName"]);
		else if($eye_pic_info!=false && ($eye_pic_info["ext"]=="png"))
			$src_temp = imagecreatefrompng($imageSaveDestination.$eye_pic_info["newName"]);
			
		if($eye_pic_info!=false)
			imagecopyresized($dest, $src_temp, 610, 480, 0, 0, $eye_pic_info["newwidth"], $eye_pic_info["newheight"], $eye_pic_info["width"], $eye_pic_info["height"]);


		// Output the image to the browser in PNG format
		imagepng($dest,$imageSaveDestination.$imgname);
		
		imagedestroy($src_im);	
		imagedestroy($dest);	
		
		return array('imgname'=>$imgname,'place'=>$place, 'competition'=>$competition, 'release_place'=>$release_place,	'distance'=>$distance, 'total_pigeons'=>$total_pigeons);

	}


}
?>