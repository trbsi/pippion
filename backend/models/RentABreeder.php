<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%rent_a_breeder}}".
 *
 * @property integer $ID
 * @property integer $IDuser
 * @property integer $IDcountry
 * @property string $city
 * @property integer $active
 * @property string $breeder_picture
 * @property string $extra_info
 *
 * @property CountryList $iDcountry
 * @property User $iDuser
 */
class RentABreeder extends \yii\db\ActiveRecord
{
	
	const IMAGE_SIZE = 4194304; //in bytes - 4mb
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rent_a_breeder}}';
    }

	public function scenarios()
    {
        return [
            'update' => ['IDuser', 'IDcountry', 'city', 'extra_info', 'active'],
            'create' => ['IDuser', 'IDcountry', 'city', 'extra_info', 'active', 'breeder_picture',],
        ];
    }
	 /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDuser', 'IDcountry', 'city', 'breeder_picture', 'extra_info'], 'required'],
            [['IDuser', 'IDcountry', 'active'], 'integer'],
            [['extra_info'], 'string'],
            [['city'], 'string', 'max' => 30],
            [['breeder_picture'], 'file', 'extensions' => ['png', 'jpg', 'jpeg'], 'maxSize' => RentABreeder::IMAGE_SIZE],
			['date_created', 'default', 'value' => date("Y-m-d H:i:s")],
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
            'IDcountry' => Yii::t('default', 'Country'),
            'city' => Yii::t('default', 'City'),
            'active' => Yii::t('default', 'Active'),
            'breeder_picture' => Yii::t('default', 'Picture'),
            'extra_info' => Yii::t('default', 'Information'),
			'date_created'=> Yii::t('default', 'Date created only')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDcountry()
    {
        return $this->hasOne(CountryList::className(), ['ID' => 'IDcountry']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDuser()
    {
        return $this->hasOne(User::className(), ['id' => 'IDuser']);
    }
	
	/*
	* return directory of rent-a-breeder images
	* $path = Yii::getAlias('@webroot') or Yii::getAlias('@web')
	*/
	public static function rentABreederImageDir($path)
	{
		return $path."/files/rent_a_breeder/";	
	}
}
