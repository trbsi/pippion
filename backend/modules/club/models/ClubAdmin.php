<?php

namespace backend\modules\club\models;

use Yii;
use yii\helpers\ArrayHelper;
use backend\modules\club\models\Club;
/**
 * This is the model class for table "{{%club_admin}}".
 *
 * @property integer $ID
 * @property integer $IDuser
 * @property integer $IDclub
 *
 * @property Club $iDclub
 * @property User $iDuser
 */
class ClubAdmin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_admin}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDuser', 'IDclub'], 'required'],
            [['IDuser', 'IDclub'], 'integer']
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
            'IDclub' => Yii::t('default', 'Idclub'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDclub()
    {
        return $this->hasOne(Club::className(), ['ID' => 'IDclub']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDuser()
    {
        return $this->hasOne(User::className(), ['id' => 'IDuser']);
    }
	
	/**
	* get all clubs where loggedin user is admin
	* $club - when someone goes to club page and from there wants to upload pic it sends Club's name here, for example "certissa"
	*/
	public static function allClubsWhereUserIsAdmin($club=NULL)
	{
		if($club!=NULL)
		{
			$clubModel=Club::findModel($club);
			$clubs=ClubAdmin::find()->where(['IDuser'=>Yii::$app->user->getId(), 'IDclub'=>$clubModel->ID])->with(['relationIDclub'])->all();
		}
		else
		{
			$clubs=ClubAdmin::find()->where(['IDuser'=>Yii::$app->user->getId()])->with(['relationIDclub'])->all();
			$result[]="";//first item is empty
		}
		
		foreach($clubs as $key=>$value)
		{
			$result[$value->IDclub]=$value->relationIDclub->club;
		}
	/*	$result = ArrayHelper::map($clubs, 'ID', function($clubs)
					{
						return $clubs->relationIDclub->club;
					});*/
		return $result;
		
	}
}
