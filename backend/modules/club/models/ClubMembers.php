<?php

namespace backend\modules\club\models;

use Yii;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "{{%club_members}}".
 *
 * @property integer $ID
 * @property integer $IDclub
 * @property string $name
 * @property string $address
 * @property string $tel
 * @property string $mob
 * @property string $email
 */
class ClubMembers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_members}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDclub', 'name'], 'required'],
            [['IDclub'], 'integer'],
            [['name', 'address'], 'string', 'max' => 100],
            [['tel', 'mob'], 'string', 'max' => 20],
            [['email'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
            'IDclub' => Yii::t('default', 'Idclub'),
            'name' => Yii::t('default', 'Name'),
            'address' => Yii::t('default', 'Address'),
            'tel' => Yii::t('default', 'UZG_TEL'),
            'mob' => Yii::t('default', 'UZG_MOB'),
            'email' => Yii::t('default', 'Email'),
        ];
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDclub()
    {
        return $this->hasOne(Club::className(), ['ID' => 'IDclub']);
    }
}
