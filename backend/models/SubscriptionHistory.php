<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%subscription_history}}".
 *
 * @property integer $ID
 * @property integer $IDsubscription
 * @property string $start_date
 * @property string $end_date
 * @property double $price
 * @property string $status
 * @property string $order_id
 * @property string $subscription_type
 * @property integer $amount
 *
 * @property Subscription $iDsubscription
 */
class SubscriptionHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%subscription_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDsubscription', 'start_date', 'end_date', 'price', 'status', 'order_id', 'subscription_type'], 'required'],
            [['IDsubscription', 'amount'], 'integer'],
            [['start_date', 'end_date'], 'safe'],
            [['price'], 'number'],
            [['status'], 'string', 'max' => 30],
            [['order_id'], 'string', 'max' => 100],
            [['subscription_type'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
            'IDsubscription' => Yii::t('default', 'Idsubscription'),
            'start_date' => Yii::t('default', 'Start Date'),
            'end_date' => Yii::t('default', 'End Date'),
            'price' => Yii::t('default', 'Price'),
            'status' => Yii::t('default', 'Status'),
            'order_id' => Yii::t('default', 'Order ID'),
            'subscription_type' => Yii::t('default', 'Subscription Type'),
            'amount' => Yii::t('default', 'Amount'),
		];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDsubscription()
    {
        return $this->hasOne(Subscription::className(), ['ID' => 'IDsubscription']);
    }
}
