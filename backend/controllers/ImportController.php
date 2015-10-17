<?php

namespace backend\controllers;

use Yii;
use backend\models\Pigeon;
use backend\models\Status;
use backend\models\PigeonList;
use backend\models\PigeonCountry;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\db\IntegrityException;
use yii\web\HttpException;

use backend\helpers\ExtraFunctions;
use backend\helpers\Mysqli;
use backend\models\Subscription;
use backend\models\PigeonData;
use backend\models\CoupleRacing;
use backend\models\CoupleBreeding;
use backend\models\BroodRacing;
use backend\models\BroodBreeding;

/**
 * PigeonController implements the CRUD actions for Pigeon model.
 */
class ImportController extends Controller
{
	/*
	* execute this code before everything else
	*/
	public function beforeAction($action)
	{

		$x = new ExtraFunctions;
		$x->beforeActionTimeLanguage();
		$x->beforeActionBreederData();
		
		/*$s = new Subscription;
		$s->hasSubEnded();*/
		return parent::beforeAction($action);
	}


    public function behaviors()
    {
        return [
		
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['pigeon-planner'],
                        'roles' => ['@']
                    ],
                ]
            ],

            'verbs' => 
			[
                'class' => VerbFilter::className(),
                'actions' => [
                    //'delete' => ['post'],
                ],
            ],
        ];
    }
	
	
	public function actionPigeonPlanner()
	{
		ini_set('max_execution_time', 400); //300 seconds = 5 minutes
		
		$screenshots=
		'
		<a href="'.Yii::getAlias('@web').'/images/media/pigeon_planner_1.png" class="group1_colorbox">
			<img src="'.Yii::getAlias('@web').'/images/media/pigeon_planner_1.png" style="max-height:200px;">
		</a>
		<a href="'.Yii::getAlias('@web').'/images/media/pigeon_planner_2.png" class="group1_colorbox">
			<img src="'.Yii::getAlias('@web').'/images/media/pigeon_planner_2.png" style="max-height:200px;">
		</a>
		<a href="'.Yii::getAlias('@web').'/images/media/pigeon_planner_3.png" class="group1_colorbox">
			<img src="'.Yii::getAlias('@web').'/images/media/pigeon_planner_3.png" style="max-height:200px;">
		</a>
		';
		
		//this is from pippion.js
		$allowedFile=
		'
			$(\'#database_file\').change(function()
			{
					var allowedFiles=["db"];
					fileValidation(allowedFiles, this);
			});
		';
		
		//upload file
		if(isset($_POST["submit"]))
		{
			$file=ExtraFunctions::uploadAnyFile			
			(
				$_FILES["database"]["name"],
				$_FILES["database"]["tmp_name"],
				$_FILES["database"]["size"],
				$_FILES["database"]["error"],
				"/temp/",
				5242880
			);
			
			$IDuser=Yii::$app->user->getId();
			
			//add 2 new statuses: 1. My Pigeon and 2. Pedigree Pigeon
			$MyPigeonStatus=new Status;
			$MyPigeonStatus->IDuser=$IDuser;
			$MyPigeonStatus->status="My Pigeon";
			$MyPigeonStatus->frompedigree=0;
			$MyPigeonStatus->save();

			$PedigreeStatus=new Status;
			$PedigreeStatus->IDuser=$IDuser;
			$PedigreeStatus->status="Pedigree Pigeon";
			$PedigreeStatus->frompedigree=1;
			$PedigreeStatus->save();

			$db = new \SQLite3(Yii::getAlias('@webroot')."/temp/".$file["FILE_NAME"]);
			$results = $db->query('SELECT * FROM Pigeons');
			
			$i=0;
			while ($row = $results->fetchArray()) 
			{
				$text=str_replace("-"," ",$row["band"]);
				$text=str_replace("."," ",$text);
				$text1=explode(" ", $text);
				$country=strtolower($text1[0]);
				
				if($country=="be" || $country=="belg" || $country=="bel")
					$country="BELG";
					
				//find pigeon country
				$PigeonCountry=PigeonCountry::find()->where(['country'=>$country])->one();
				
				$Pigeon = new Pigeon;
				$Pigeon->IDuser=$IDuser;
				$Pigeon->pigeonnumber=$row["band"];
				$Pigeon->sex=$this->getSex($row["sex"]);
				$Pigeon->color=$row["colour"];
				$Pigeon->breed=$row["strain"];
				$Pigeon->name=$row["name"];
				$Pigeon->year=$row["year"];
				$Pigeon->IDcountry=(!empty($PigeonCountry)) ? $PigeonCountry->ID : 86; //86 is XXX 
				$Pigeon->IDstatus=($row["show"]==0) ? $PedigreeStatus->ID : $MyPigeonStatus->ID;
				$Pigeon->pigeon_image=ExtraFunctions::NO_PICTURE;
				$Pigeon->eye_image==ExtraFunctions::NO_EYE;
				$Pigeon->save();
				
				$PigeonData=new PigeonData;
				$PigeonData->IDuser=$IDuser;
				$PigeonData->IDpigeon=$Pigeon->ID;
				$PigeonData->pigeondata=$row["extra1"]."\n".$row["extra2"]."\n".$row["extra3"]."\n".$row["extra4"]."\n".$row["extra5"]."\n".$row["extra6"];
				$PigeonData->year=date("Y");
				$PigeonData->date_created=ExtraFunctions::currentTime("ymd-his");
				$PigeonData->save();
				
				//save this pigeon so you can later find it's parent and save to PigeonList
				$father_country=str_replace("-"," ",$row["sire"]);
				$father_country=str_replace("."," ",$father_country);
				$father_country=explode(" ", $father_country);
				$father_country_x=strtolower($father_country[0]);

				$mother_country=str_replace("-"," ",$row["dam"]);
				$mother_country=str_replace("."," ",$mother_country);
				$mother_country=explode(" ", $mother_country);
				$mother_country_x=strtolower($mother_country[0]);

				$pigeon_array[$i]["pigeon"]=$Pigeon->ID;
				$pigeon_array[$i]["father"]=$row["sire"];
				$pigeon_array[$i]["father_year"]=$row["yearsire"];
				$pigeon_array[$i]["father_country"]=$father_country_x;
				$pigeon_array[$i]["mother"]=$row["dam"];
				$pigeon_array[$i]["mother_year"]=$row["yeardam"];
				$pigeon_array[$i]["mother_country"]=$mother_country;
				$i++;
			}
			
			//now save to PigeonList
			foreach($pigeon_array as $key=>$value)
			{
				$existFather=Pigeon::find()->where(["pigeonnumber"=>$pigeon_array[$key]["father"], 'IDuser'=>$IDuser])->one();
				$existMother=Pigeon::find()->where(["pigeonnumber"=>$pigeon_array[$key]["mother"], 'IDuser'=>$IDuser])->one();
				
				if(empty($pigeon_array[$key]["father"]))
				{
					$FatherID=0;
				}
				else if(empty($existFather))
				{
					//find pigeon country
					$PigeonCountry=PigeonCountry::find()->where(['country'=>$pigeon_array[$key]["father_country"]])->one();

					$Pigeon = new Pigeon;
					$Pigeon->IDuser=$IDuser;
					$Pigeon->pigeonnumber=$pigeon_array[$key]["father"];
					$Pigeon->sex=Pigeon::MALE_PIGEON;
					$Pigeon->color="";
					$Pigeon->breed="";
					$Pigeon->name="";
					$Pigeon->year=$pigeon_array[$key]["father_year"];
					$Pigeon->IDcountry=(!empty($PigeonCountry)) ? $PigeonCountry->ID : 86; //86 is XXX 
					$Pigeon->IDstatus=$MyPigeonStatus->ID;
					$Pigeon->pigeon_image=ExtraFunctions::NO_PICTURE;
					$Pigeon->eye_image==ExtraFunctions::NO_EYE;
					$Pigeon->save();
					$FatherID=$Pigeon->ID;
					
					$PigeonList=new PigeonList;
					$PigeonList->IDuser=$IDuser;
					$PigeonList->IDpigeon=$FatherID;
					$PigeonList->IDmother=0;
					$PigeonList->IDfather=0;
					$PigeonList->IDbrood_racing=0;
					$PigeonList->IDbrood_breeding=0;
					$PigeonList->save();
				}
				else
					$FatherID=$existFather->ID;
					
				if(empty($pigeon_array[$key]["mother"]))
				{
					$MotherID=0;
				}
				else if(empty($existMother))
				{
					//find pigeon country
					$PigeonCountry=PigeonCountry::find()->where(['country'=>$pigeon_array[$key]["mother_country"]])->one();

					$Pigeon = new Pigeon;
					$Pigeon->IDuser=$IDuser;
					$Pigeon->pigeonnumber=$pigeon_array[$key]["mother"];
					$Pigeon->sex=Pigeon::FEMALE_PIGEON;
					$Pigeon->color="";
					$Pigeon->breed="";
					$Pigeon->name="";
					$Pigeon->year=$pigeon_array[$key]["mother_year"];
					$Pigeon->IDcountry=(!empty($PigeonCountry)) ? $PigeonCountry->ID : 86; //86 is XXX  
					$Pigeon->IDstatus=$MyPigeonStatus->ID;
					$Pigeon->pigeon_image=ExtraFunctions::NO_PICTURE;
					$Pigeon->eye_image==ExtraFunctions::NO_EYE;
					$Pigeon->save();
					$MotherID=$Pigeon->ID;		
								
					$PigeonList=new PigeonList;
					$PigeonList->IDuser=$IDuser;
					$PigeonList->IDpigeon=$MotherID;
					$PigeonList->IDmother=0;
					$PigeonList->IDfather=0;
					$PigeonList->IDbrood_racing=0;
					$PigeonList->IDbrood_breeding=0;
					$PigeonList->save();
				}
				else
					$MotherID=$existMother->ID;

				$PigeonList=new PigeonList;
				$PigeonList->IDuser=$IDuser;
				$PigeonList->IDpigeon=$pigeon_array[$key]["pigeon"];
				$PigeonList->IDmother=$MotherID;
				$PigeonList->IDfather=$FatherID;
				$PigeonList->IDbrood_racing=0;
				$PigeonList->IDbrood_breeding=0;
				$PigeonList->save();
				
			}//foreach($pigeon_array as $key=>$value)
			
			$this->redirect(["/pigeon/index"]);
		}//if(isset($_POST["submit"]))
		
		return $this->render('import', 
		[
			'screenshots'=>$screenshots,
			'allowedFile'=>$allowedFile
		]);
	}
	
	/*
	* return sex for the pigeon
	*/
		/*
	* return proper value for sex when showing/returning from X,Y or ?
	*/
	protected function getSex($value)
	{
		if($value==0)
			return Pigeon::MALE_PIGEON;
		else if($value==1)
			return Pigeon::FEMALE_PIGEON;
		else
			return '?';
	}

}
