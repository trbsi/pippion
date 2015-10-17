<?php

namespace backend\modules\pigeon_image\models;


use Yii;
use dektrium\user\models\User;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "{{%pigeon_image_album}}".
 *
 * @property integer $ID
 * @property string $album
 * @property integer $IDuser
 * @property integer $IDalbum
 * @property string $date_created
 *
 * @property PigeonImage[] $pigeonImages
 * @property User $iDuser
 */
class Album extends \yii\db\ActiveRecord
{
	public $album_dir;
	public $thumbnail_dir;
	
	public function __construct()
    {
		$this->album_dir="/files/albums/";
		$this->thumbnail_dir="/thumbnail/";
    }
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pigeon_image_album}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['album', 'IDuser', 'date_created'], 'required'],
            [['IDuser', 'IDclub'], 'integer'],
            [['date_created'], 'safe'],
            [['album'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
            'album' => Yii::t('default', 'Album'),
            'IDuser' => Yii::t('default', 'Iduser'),
            'IDclub' => Yii::t('default', 'Club'),
            'date_created' => Yii::t('default', 'Date Created'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPigeonImages()
    {
        return $this->hasMany(Image::className(), ['IDalbum' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDuser()
    {
        return $this->hasOne(User::className(), ['id' => 'IDuser']);
    }
	
	/*
	* get last photo for the album
	*/
	public function getLastAlbumPhoto()
	{
		return $this->hasOne(Image::className(), ['IDalbum' => 'ID'])->orderBy('ID DESC');
	}
	
	/*
	* remove all files within folder and folder itself
	* http://stackoverflow.com/questions/1334398/how-to-delete-a-folder-with-contents-using-php
	*/
	public static function removeFilesAndFolder($path)
	{
		if (is_dir($path) === true)
		{
			$files = array_diff(scandir($path), array('.', '..'));
	
			foreach ($files as $file)
			{
				self::removeFilesAndFolder(realpath($path) . '/' . $file);
			}
	
			return rmdir($path);
		}
	
		else if (is_file($path) === true)
		{
			return unlink($path);
		}
	
		return false;
	}
	
	/*
	* return path to picture
	* $IDalbum - ID of an album
	* $picture - picture name
	* $path - relative or absolute (@webroot or @web)
	*/
	public static function returnPathToPicture($IDalbum, $picture, $path, $IDuser)
	{
		$Album = new Album;
		$return=$path.$Album->album_dir.$IDuser."/".$IDalbum."/".$picture;
		return $return;
	}
	
	/*
	* return path to thumbnail picture
	* $IDalbum - ID of an album
	* $picture - picture name
	* $path - relative or absolute (@webroot or @web)
	*/
	public static function returnPathToThumbnail($IDalbum, $picture, $path, $IDuser)
	{
		$Album = new Album;
		$return=$path.$Album->album_dir.$IDuser."/".$IDalbum.$Album->thumbnail_dir.$picture;
		return $return;
	}
	
	/*
	* return path to picture
	* $IDalbum - ID of an album
	* $path - relative or absolute (@webroot or @web)
	*/
	public static function returnAlbumDirectory($IDalbum, $path, $IDuser)
	{
		$Album = new Album;
		$return=$path.$Album->album_dir.$IDuser."/".$IDalbum."/";
		return $return;
	}
	
	/*
	* find album model 
	*/
    public static function findAlbumModel($id)
    {
        if (($model = Album::find()->where(['ID'=>$id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
