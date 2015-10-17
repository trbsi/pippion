<?php

namespace backend\models;

use Yii;
use backend\user\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use dektrium\user\models\Account;
/**
 * This is the model class for table "{{%breeder}}".
 *
 * @property integer $ID
 * @property integer $IDuser
 * @property string $name_of_breeder
 * @property integer $country
 * @property string $town
 * @property string $address
 * @property string $tel1
 * @property string $tel2
 * @property string $mob1
 * @property string $mob2
 * @property string $email1
 * @property string $email2
 * @property string $fax
 * @property string $website
 * @property integer $verified
 *
 * @property User $iDuser
 * @property CountryList $country0
 */
class Breeder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%breeder}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDuser', 'name_of_breeder', 'country', 'town', 'address', 'tel1', 'email1'], 'required'],
            [['IDuser', 'country', 'verified'], 'integer'],
            [['name_of_breeder'], 'string', 'max' => 40],
            [['town', 'tel1', 'tel2', 'mob1', 'mob2', 'fax'], 'string', 'max' => 20],
            [['email1', 'email2', 'website'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'ID' => 'ID',
			'IDuser' => Yii::t('default', 'IDuser'),
			'name_of_breeder' => Yii::t('default', 'UZG_IME_UZGAJIVACA'),
			'country' => Yii::t('default', 'UZG_DRZAVA'),
			'town' => Yii::t('default', 'UZG_MJESTO'),
			'address' => Yii::t('default', 'UZG_ADRESA'),
			'tel1' => Yii::t('default', 'UZG_TEL'),
			'tel2' => Yii::t('default', 'tel2'),
			'mob1' => Yii::t('default', 'UZG_MOB'),
			'mob2' => Yii::t('default', 'mob2'),
			'email1' => Yii::t('default', 'UZG_EMAIL'),
			'email2' => Yii::t('default', 'email2'),
			'fax' => Yii::t('default', 'UZG_FAX'),
			'website' => Yii::t('default', 'UZG_WEBSITE'),
			'verified' => Yii::t('default','Verified'),
        ];
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
    public function getRelationCountry()
    {
        return $this->hasOne(CountryList::className(), ['ID' => 'country']);
    }
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationLastVisit()
    {
        return $this->hasOne(LastVisit::className(), ['IDuser' => 'IDuser']);
    }
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationSubscription()
    {
        return $this->hasOne(Subscription::className(), ['IDuser' => 'IDuser']);
    }
	
	/*
	* return drop down list with YES/NO values for GridView (active fields)
	*/
	public function dropDownListSearchYesNo()
	{
		$array=
		[
			['key'=>1, 'value'=>'Yes'],
			['key'=>0, 'value'=>'No'],
		];
		return ArrayHelper::map($array, 'key', 'value');
	}
	
	/*
	* check if user has connected with Google
	*/
	public function isConnectedGoogle()
	{
		$G=Account::find()->where(['user_id'=>Yii::$app->user->getId(), 'provider'=>'google'])->one();
		//user is not connected
		if(empty($G))
		{
			$return='<a href="'.Url::to(['/user/settings/networks']).'" class="label label-important"><img src="'.Yii::getAlias('@web').'/images/share_btns/Google.png">&nbsp;&nbsp;&nbsp;&nbsp;'.Yii::t('default', 'Connect with G').'</a>';
			return $return;

		}
		//user is connected
		else
		{
			return '<a href="'.Url::to(['/user/settings/networks']).'" class="label label-info"><img src="'.Yii::getAlias('@web').'/images/share_btns/Google.png">&nbsp;&nbsp;&nbsp;&nbsp;'.Yii::t('default', 'Already connected').'</a>';
		}
	}
	/*
	* check if user has connected with Facebook
	*/
	public static function isConnectedFacebook()
	{
		$G=Account::find()->where(['user_id'=>Yii::$app->user->getId(), 'provider'=>'facebook'])->one();
		
		//user is not connected
		if(empty($G))
		{
			$return='<a href="'.Url::to(['/user/settings/networks']).'" class="label label-important"><img src="'.Yii::getAlias('@web').'/images/share_btns/Facebook.png">&nbsp;&nbsp;&nbsp;&nbsp;'.Yii::t('default', 'Connect with F').'</a>';
			return $return;

		}
		//user is connected
		else
		{
			return '<a href="'.Url::to(['/user/settings/networks']).'" class="label label-info"><img src="'.Yii::getAlias('@web').'/images/share_btns/Facebook.png">&nbsp;&nbsp;&nbsp;&nbsp;'.Yii::t('default', 'Already connected').'</a>';
		}
	}

	 /**
     * Finds a user by the given id.
     *
     * @param  integer     $id User id to be used on search.
     * @return models\User
     */
    public static function findUserById($id)
    {
        return User::find()->where(['ID'=>$id])->one();
    }

	/*
	* finds breeder profile by IDuser
	*/
	public static function findBreederProfile($id)
	{
		return Breeder::find()->where(['IDuser'=>$id])->one();
	}
	
	/*
	* get user's email address and username
	*/
	public static function getUserEmailAndUsername($IDuser)
	{
		$breeder=Breeder::findBreederProfile($IDuser);
		
		//check if breeder data are empty
		$username=empty($breeder->name_of_breeder) ? $breeder->relationIDuser->username : $breeder->name_of_breeder; 
		
		if(!empty($breeder->email1))	
			$email=$breeder->email1;
			
		else if(!empty($breeder->email2))	
			$email=$breeder->email2;
			
		else	
			$email=$breeder->relationIDuser->email;
		
		return ['email'=>$email, 'username'=>$username];
	}
}
