<?php

namespace backend\modules\pigeon_image\models;

use Yii;
use dektrium\user\models\User;
/**
 * This is the model class for table "{{%pigeon_image}}".
 *
 * @property integer $ID
 * @property integer $IDuser
 * @property string $image_file
 * @property integer $IDalbum
 * @property string $date_created
 *
 * @property PigeonImageAlbum $iDalbum
 * @property User $iDuser
 * @property PigeonImageComment[] $pigeonImageComments
 * @property PigeonImageLike[] $pigeonImageLikes
 */
class Image extends \yii\db\ActiveRecord
{
	
	const MAX_IMAGE_SIZE=5000000; //5mb
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pigeon_image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
		
			[['IDuser', 'image_file', 'IDalbum', 'date_created'], 'required'],
            [['IDuser', 'IDalbum'], 'integer'],
            [['description'], 'string', 'max' => 1000],
            [['date_created'], 'safe'],
            [['image_file'], 'string', 'max' => 200]

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
            'IDuser' => Yii::t('default', 'Iduser'),
            'image_file' => Yii::t('default', 'Image File'),
            'IDalbum' => Yii::t('default', 'Idalbum'),
			'description' => Yii::t('default', 'Description'),
            'date_created' => Yii::t('default', 'Date Created'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDalbum()
    {
        return $this->hasOne(Album::className(), ['ID' => 'IDalbum']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDuser()
    {
        return $this->hasOne(User::className(), ['id' => 'IDuser']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPigeonImageComments()
    {
        return $this->hasMany(PigeonImageComment::className(), ['IDimage' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPigeonImageLikes()
    {
        return $this->hasMany(PigeonImageLike::className(), ['IDimage' => 'ID']);
    }
	
	/*
	* find image by its id
	*/
	public static function findImageById($IDimage, $IDalbum)
	{
		$return=Image::find()->where(['ID'=>$IDimage, 'IDalbum'=>$IDalbum, 'IDuser'=>Yii::$app->user->getID()])->one();
		return $return;
	}
}
