<?php

namespace backend\modules\club\models;

use Yii;
use backend\models\CountryList;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "{{%club}}".
 *
 * @property integer $ID
 * @property string $club
 * @property integer $IDcountry
 * @property string $city
 * @property string $date_created
 * @property string $about
 * @property string $contact
  */
class Club extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['club', 'club_link', 'IDcountry', 'city'], 'required'],
			[['IDcountry'], 'integer'],
            [['club'], 'unique'],
            [['club', 'club_link', 'city'], 'string', 'max' => 50],
			//['club', 'match', 'pattern' => '(^[a-zA-Z0-9]+$)'], //http://regexlib.com/REDetails.aspx?regexp_id=1014&AspxAutoDetectCookieSupport=1
			[['date_created'], 'default', 'value' => date("Y-m-d")],
			[['about', 'contact'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
            'club' => Yii::t('default', 'Club'),
			'IDcountry' => Yii::t('default', 'Country'),
			'city' => Yii::t('default', 'City'),
			'date_created'=>Yii::t('default', 'Date created only'),
			'about' => Yii::t('default', 'Info'),
            'contact' => Yii::t('default', 'Contact'),
			'club_link'=>"Club Link",
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
    public function getClubAdmins()
    {
        return $this->hasMany(ClubAdmin::className(), ['IDclub' => 'ID']);
    }
	
    /**
     * Finds the Club model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Club the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public static function findModel($page)
    {
        if (($model = Club::findOne(["club_link"=>$page])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	/*
	* check if current user is admin of this page
	* $model - model from Club, $model->ID is ID in mg_club
	*/
    public static function pageAdmin($model)
    {
        if (($model = ClubAdmin::find()->where(["IDclub"=>$model->ID, "IDuser"=>Yii::$app->user->getId()])->one()) !== null) 
		{
            return true;
        } 
		else 
		{
            return false;
        }
    }
	
}
