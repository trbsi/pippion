<?php

namespace backend\modules\club\models;

use Yii;
use  backend\modules\club\models\ClubResults;

/**
 * This is the model class for table "{{%club_tables}}".
 *
 * @property integer $ID
 * @property integer $IDclub
 * @property integer $IDuser
 * @property integer $year
 * @property string $description
 * @property string $date_created
 * @property integer $pdf_file
 * @property integer $result_type
 */
class ClubTables extends \yii\db\ActiveRecord
{

	const RESULT_TYPE_TEAM=0; //show team tables (poredak timova)
	const RESULT_TYPE_PIGEON=1; //show pigeon tables (poredak golubova)
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_tables}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDclub', 'year', 'pdf_file', 'IDuser', 'result_type'], 'required'],
            [['IDclub', 'year', 'result_type'], 'integer'],
            [['description'], 'string'],
            [['pdf_file'], 'string', 'max' => 100],
			['date_created', 'default', 'value' => date("Y-m-d")],
			['pdf_file', 'file', 'extensions' => ['pdf'], 'maxSize' => ClubResults::PDF_FILE_SIZE],
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
            'IDuser' => Yii::t('default', 'Iduser'),
            'year' => Yii::t('default', 'Year'),
            'description' => Yii::t('default', 'Description'),
            'date_created' => Yii::t('default', 'Date created only'),
            'pdf_file' => Yii::t('default', 'Table file'),
			'result_type' => Yii::t('default', 'Type'), //0-team table, 1-pigeon table
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
	
	/*
	* club result upload directory
	* $path - Yii::getAlias("@web") or Yii::getAlias("@webroot")
	* $year - year of submited result
	* $IDclub - ID of a club
	* $file - some_file.pdf from database
	*/
	public static function clubTablesUploadDir($path, $year, $IDclub, $file=NULL)
	{
		$dir=$path.ClubResults::PDF_CLUB_DIRECTORY.$IDclub."/".$year."/tables/";
			
		if($file!=NULL)
			return $dir.$file;
		else
			return $dir;		
	}
}
