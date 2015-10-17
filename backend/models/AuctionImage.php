<?php

namespace backend\models;

use Yii;
use backend\models\Auction;
/**
 * This is the model class for table "{{%auction_image}}".
 *
 * @property integer $ID
 * @property integer $IDauction
 * @property string $image_file
 *
 * @property Auction $iDauction
 */
class AuctionImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auction_image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDauction'], 'required'],
            [['IDauction'], 'integer'],
            [['image_file'],  'file', 'extensions' => ['png', 'jpg', 'gif', 'jpeg'], 'maxSize' => Auction::IMAGE_SIZE/*2MB*/]
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
            'image_file' => Yii::t('default', 'Image'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDauction()
    {
        return $this->hasOne(Auction::className(), ['ID' => 'IDauction']);
    }
}
