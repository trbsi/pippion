<?php

namespace backend\modules\club\models;

use Yii;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "{{%club_results}}".
 *
 * @property integer $ID
 * @property integer $IDclub
 * @property integer $IDuser
 * @property string $pdf_file
 * @property string $place
 * @property string $distance
 * @property integer $distance_type
 * @property string $year
 * @property string $date_created
 * @property string $description
 * @property integer $result_type
 *
 * @property Club $iDclub
 * @property User $iDuser
 */
class ClubResults extends \yii\db\ActiveRecord
{
	
	const PDF_FILE_SIZE=3145728;//3mb
	const PDF_CLUB_DIRECTORY="/files/clubs/"; //+CLUB_ID/YEAR
	
	const RESULT_TYPE_OTHER=0;
	const RESULT_TYPE_SHORT=1;
	const RESULT_TYPE_MEDIUM=2;
	const RESULT_TYPE_LONG=3;
	const RESULT_TYPE_MARATHON=4;
	//(0-other, 1-short, 2-medium, 3-long, 4-marathon)
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_results}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDclub', 'IDuser', 'pdf_file', 'place', 'distance', 'year', 'result_type'], 'required'],
            [['IDclub', 'IDuser', 'distance_type', 'result_type'], 'integer'],
            [['year', 'date_created'], 'safe'],
            [['description'], 'string'],
            [['pdf_file'], 'string', 'max' => 100],
            [['place'], 'string', 'max' => 50],
            [['distance'], 'string', 'max' => 15],
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
            'pdf_file' => Yii::t('default', 'Result file'),
            'place' => Yii::t('default', 'Mjesto Pustanja'),
            'distance' => Yii::t('default', 'Udaljenost'),
			'distance_type' => Yii::t('default', 'km').", ".Yii::t('default', 'miles'),
            'year' => Yii::t('default', 'Year'),
            'date_created' => Yii::t('default', 'Date created only'),
            'description' => Yii::t('default', 'Description'),
			'result_type' => Yii::t('default', 'Result category'), //ostalo, kratke, srednje, duge pruge, maraton (0-other, 1-short, 2-medium, 3-long, 4-marathon)
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
	* dropdown list for distance type
	*/
	public static function dropDownListDistanceType()
	{
		return
		[
			0 => Yii::t('default', 'km'),
			1 => Yii::t('default', 'miles')
		];
	}
	
	/*
	* return measure for distance
	*/
	public static function returnDistanceMeasure($key)
	{
		$array=self::dropDownListDistanceType();
		return $array[$key];
	}
	
	/*
	* club result upload directory
	* $path - Yii::getAlias("@web") or Yii::getAlias("@webroot")
	* $year - year of submitet result
	* $IDclub - ID of a club
	* $file - some_file.pdf from database
	*/
	public static function clubResultUploadDir($path, $year, $IDclub, $file=NULL)
	{
		$dir=$path.ClubResults::PDF_CLUB_DIRECTORY.$IDclub."/".$year."/races/";
			
		if($file!=NULL)
			return $dir.$file;
		else
			return $dir;		
	}
	
	/*
	* return result type for dropdownlist
	* (0-other, 1-short, 2-medium, 3-long, 4-marathon)
	*/
	public static function resultType()
	{
		return 
		[
			0=>Yii::t('default', 'Other'),
			1=>Yii::t('default', 'Short distances'),
			2=>Yii::t('default', 'Medium distances'),
			3=>Yii::t('default', 'Long distances'),
			4=>Yii::t('default', 'Marathon'),
		];
	}
	
	/*
	* sent from index.php to show result type name
	*/
	public static function returnResultTypeName($value)
	{
		$array=self::resultType();
		return $array[$value];
	}
}
