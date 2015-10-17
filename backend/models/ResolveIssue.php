<?php

namespace backend\models;

use Yii;
use backend\models\AuctionRating;

/**
 * This is the model class for table "{{%resolve_issue}}".
 *
 * @property integer $ID
 * @property integer $IDauction
 * @property string $date_created
 * @property integer $resolved
 *
 * @property Auction $iDauction
 * @property ResolveIssueReply[] $resolveIssueReplies
 */
class ResolveIssue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%resolve_issue}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDauction', 'date_created'], 'required'],
            [['IDauction', 'resolved'], 'integer'],
            [['date_created'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
            'IDauction' => Yii::t('default', 'Idauction'),
            'date_created' => Yii::t('default', 'Date Created'),
            'resolved' => Yii::t('default', 'Resolved'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDauction()
    {
        return $this->hasOne(Auction::className(), ['ID' => 'IDauction']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResolveIssueReplies()
    {
        return $this->hasMany(ResolveIssueReply::className(), ['IDresolveAnIssue' => 'ID']);
    }

    /**
     * @inheritdoc
     * @return \backend\models\activequery\ResolveIssueQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \backend\models\activequery\ResolveIssueQuery(get_called_class());
    }
	
	/*
	* check if user is allowed to see case and add reply
	* $id - ID in Auction
	*/
	public static function isUserAllowedToSeeCase($id)
	{
		$IDuser=Yii::$app->user->getId();
		
		//before allowing user to resolve an issue check if he is the winner or seller of specific auction
		$check=AuctionRating::find()->where(['IDauction'=>$id])->one();
		//if he is admin, it's ok to access
		if(Yii::$app->user->identity->getIsAdmin())
			return true;
		//if he is not winner or seller deny access
		else if($check->IDwinner!=$IDuser && $check->IDseller!=$IDuser)
			throw new \yii\web\HttpException(401, Yii::t('default', 'You cannot resolve an issue'));
		else
			return $check;
	}
}
