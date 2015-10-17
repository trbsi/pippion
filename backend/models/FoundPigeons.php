<?php

namespace backend\models;

use Yii;
use dektrium\user\models\User;
/**
 * This is the model class for table "{{%found_pigeons}}".
 *
 * @property integer $ID
 * @property integer $IDcountry
 * @property string $pigeonnumber
 * @property string $sex
 * @property string $year
 * @property integer $IDuser
 * @property integer $country
 * @property string $city
 * @property string $address
 * @property string $zip
 * @property string $image_file
 * @property boolean $returned
 * @property string $date_created
 *
 * @property PigeonCountry $iDcountry
 * @property User $iDuser
 * @property CountryList $country0
 */
class FoundPigeons extends \yii\db\ActiveRecord
{
	//full path backend/web/images/found_pigeons/
	const UPLOAD_DIR = '/images/found_pigeons/';
	const IMAGE_SIZE = 5000000; //5mb

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%found_pigeons}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDcountry', 'pigeonnumber', 'year','sex', 'IDuser', 'country', 'city', 'address', 'zip'], 'required'],
            [['IDcountry', 'IDuser', 'country'], 'integer'],
            [['year', 'date_created'], 'safe'],
            [['returned'], 'integer'],
            [['pigeonnumber'], 'string', 'max' => 40],
            [['image_file'], 'string', 'max' => 100],
            [['sex'], 'string', 'max' => 1],
            [['city'], 'string', 'max' => 50],
			[['zip'], 'string', 'max' => 15],
            [['address'], 'string', 'max' => 100],
			[['image_file'], 'file', 'extensions' => ['png', 'jpg', 'gif', 'jpeg'], 'maxSize' => FoundPigeons::IMAGE_SIZE],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
            'IDcountry' => Yii::t('default', 'Country'),
            'pigeonnumber' => Yii::t('default', 'Pigeon number'),
            'sex' => Yii::t('default', 'Sex'),
            'year' => Yii::t('default', 'Year'),
            'IDuser' => Yii::t('default', 'Iduser'),
            'country' => Yii::t('default', 'Country'),
            'city' => Yii::t('default', 'City'),
            'address' => Yii::t('default', 'Address'),
            'zip' => Yii::t('default', 'Zip'),
            'image_file' => Yii::t('default', 'Picture of a pigeon'),
            'returned' => Yii::t('default', 'Returned'),
            'date_created' => Yii::t('default', 'Date Created UTC'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationPigeonIDcountry()
    {
        return $this->hasOne(PigeonCountry::className(), ['ID' => 'IDcountry']);
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
    public function getRelationRealIDcountry()
    {
        return $this->hasOne(CountryList::className(), ['ID' => 'country']);
    }
	
		/*
	* dropdownlista za polje Returned, to je polje koje se koristi kako bi se označilo jel golub vraćen vlasniku ili ne
	* ako je Yes onda se neće prikazivati na public listi, ako je No prikazivat će se na public listi
	* @return array Yes/No
	*/
	public function pigeonReturned()
	{
		return [0=>Yii::t('default', 'No'), 1=>Yii::t('default', 'Yes') ];
	}

}
