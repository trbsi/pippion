<?php

namespace backend\controllers;

use Yii;
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
use backend\models\Statistics;
use backend\models\RacingTable;
use backend\models\Pigeon;
use backend\models\LastVisit;

/**
 * AuctionRatingController implements the CRUD actions for AuctionRating model.
 */
class StatisticsController extends Controller
{
	
	/*
	* execute this code before everything else
	*/
	public function beforeAction($action)
	{
		$x = new ExtraFunctions;
		$x->beforeActionTimeLanguage();
		$x->beforeActionBreederData();
	
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
                        'actions' => ['pigeons', 'pigeons-statistics', 'table-racing', 'racing-table-statistics', 'last-visit','test'],
                        'roles' => ['@']
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
	
	/*
	* for testing purposes
	*/
	public function actionTest()
	{		
	}
	
	/*
	* little statistic for admin about who are active users
	*/
	public function actionLastVisit()
	{
		$query=LastVisit::find();
		$dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['last_visit'=>SORT_DESC]]
        ]);

		return $this->render("last-visit", ['dataProvider'=>$dataProvider]);
	}
	
	/*------------------------------------------PIGEONS--------------------------------------------------*/
    /**
	* Statistics about pigeons
		* Male/Female chart
		* Year chart
		* Country chart
		* Status chart
    */
    public function actionPigeons()
    {
		return $this->render("pigeons", []);		
    }

    public function actionPigeonsStatistics($type)
    {
		/* pChart library inclusions */
		include(Yii::getAlias("@common")."/pchart/pData.class.php");
		include(Yii::getAlias("@common")."/pchart/pDraw.class.php");
		include(Yii::getAlias("@common")."/pchart/pPie.class.php");
		include(Yii::getAlias("@common")."/pchart/pImage.class.php");
		
		if($type==Statistics::TYPE_PIGEON_SEX)
			Statistics::statisticsPigeonsMaleFemale();
		else if($type==Statistics::TYPE_PIGEON_YEAR)
			Statistics::statsticsPigeonsYear();
		else if($type==Statistics::TYPE_PIGEON_COUNTRY)
			Statistics::statsticsPigeonsCountry();
		else if($type==Statistics::TYPE_PIGEON_STATUS)
			Statistics::statsticsPigeonsStatus();
    }
	
	/*-------------------------------------RACING TABLE----------------------------------------------*/
	/*
	* LINE CHART
	* distance <-> year
	* participated competitors <-> year
	* participated pigeons <-> year
	* won place <-> year
	*/
	public function actionTableRacing($racing_table_cat=NULL, $pigeon_number=NULL)
	{
		$sub_title=NULL;
		if($racing_table_cat!=NULL && $pigeon_number!=NULL)
		{
			$query=RacingTable::find()
			->where(['IDuser'=>Yii::$app->user->getId(), 'IDpigeon'=>(int)$pigeon_number, 'IDcategory'=>(int)$racing_table_cat])
			->one();
			$sub_title="[".$query->relationIDpigeon->pigeonnumber."] ".Pigeon::getSex($query->relationIDpigeon->sex)."/".$query->relationIDpigeon->relationIDcountry->country." | ".$query->relationIDcategory->category;
		}

		return $this->render("racing-table",['sub_title'=>$sub_title]);
	}
	
	/*
	* $racing_table_cat - IDcategory in RacingTable 
	* $pigeon_number - IDpigeon in RacingTable
	*/
	public function actionRacingTableStatistics($racing_table_cat, $pigeon_number)
	{
		include(Yii::getAlias("@common")."/pchart/pData.class.php");
		include(Yii::getAlias("@common")."/pchart/pDraw.class.php");
		include(Yii::getAlias("@common")."/pchart/pImage.class.php");
		
		$racingTableTable=RacingTable::getTableSchema();
		$query=RacingTable::find()
			->from("(SELECT * FROM $racingTableTable->name WHERE IDuser=".Yii::$app->user->getId()." ORDER BY racing_date DESC LIMIT 15) AS reverse")
			->where(['IDuser'=>Yii::$app->user->getId(), 'IDpigeon'=>(int)$pigeon_number, 'IDcategory'=>(int)$racing_table_cat])
			->orderBy('racing_date ASC')
			->all();
		

		Statistics::statisticsRacingTable($racing_table_cat, $pigeon_number, $query);
	}

}
