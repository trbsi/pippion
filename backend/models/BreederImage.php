<?php

namespace backend\models;

use Yii;
use backend\helpers\LinkGenerator;
/**
 * This is the model class for table "{{%breeder_image}}".
 *
 * @property integer $ID
 * @property string $breeder_image_file
 * @property integer $IDuser
 * @property integer $is_profile
 *
 * @property User $iDuser
 */
class BreederImage extends \yii\db\ActiveRecord
{
	
	const PROFILE_PIC_DIR="/files/profile_pictures/";
	const NO_PROFILE_PIC_DIR="/images/avatars/no_pic.jpg";
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%breeder_image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['breeder_image_file', 'IDuser'], 'required'],
            [['IDuser', 'is_profile'], 'integer'],
            [['breeder_image_file'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
            'breeder_image_file' => Yii::t('default', 'Breeder Image File'),
            'IDuser' => Yii::t('default', 'Iduser'),
            'is_profile' => Yii::t('default', 'is it profile picture?'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDuser()
    {
        return $this->hasOne(User::className(), ['id' => 'IDuser']);
    }
	
	/*
	* return directory of profile pictures
	* $path = Yii::getAlias("@web"), Yii::getAlias("@webroot")
	* $image_name = breeder_image_file value
	*/
	public static function returnProfilePicDirectory($path, $IDuser, $image_name=NULL)
	{
		if($image_name!=NULL)
			return $path.BreederImage::PROFILE_PIC_DIR.$IDuser."/".$image_name;
		else
			return $path.BreederImage::PROFILE_PIC_DIR.$IDuser."/";
	}
	
	/*
	* return directory of no profile picture (no_pic.jpg)
	* $path = Yii::getAlias("@web"), Yii::getAlias("@webroot")
	*/
	public static function returnNoProfilePicDirectory($path)
	{
		return $path.BreederImage::NO_PROFILE_PIC_DIR;
	}
	
	/*
	* find profile picture of specific user and return path to that picture
	* $options - array of options you want to put in picture like url, class, width, height...
	*/
	public static function findUserProfilePicture($IDuser, $options=NULL)
	{
		$profilePic=BreederImage::find()->where(["IDuser"=>$IDuser, "is_profile"=>1])->one();
		if(!empty($profilePic))
		{
			$path_to_image=BreederImage::returnProfilePicDirectory(Yii::getAlias('@web'), $IDuser,$profilePic->breeder_image_file);
			if($options!=NULL)
			{
				return LinkGenerator::breederLink('<img src="'.$path_to_image.'" class="'.$options["img_class"].'" style="'.$options["img_style"].'" />', $IDuser, ['class'=>"x"]);
			}
			else
			{
				return $path_to_image;
			}
		}
		else
		{
			$path_to_image=BreederImage::returnNoProfilePicDirectory(Yii::getAlias('@web'));
			if($options!=NULL)
			{
				return LinkGenerator::breederLink('<img src="'.$path_to_image.'" class="'.$options["img_class"].'" style="'.$options["img_style"].'" />', $IDuser,  ['class'=>"x"]);
			}
			else
			{
				return $path_to_image;
			}
			
		}	
	}
}
