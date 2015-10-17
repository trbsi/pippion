<?php
namespace backend\models;

use Yii;
use backend\models\Pigeon;
use backend\models\PigeonCountry;
use backend\models\Status;
use backend\models\RacingTable;
use yii\db\Query;

class Statistics
{
	//-------------------------------------------PIGEONS------------------------------------------------
	const TYPE_PIGEON_SEX="pigeon_sex";
	const TYPE_PIGEON_YEAR="pigeon_year";
	const TYPE_PIGEON_COUNTRY="pigeon_country";
	const TYPE_PIGEON_STATUS="pigeon_status";
	
	//return PIE CHART statistics for male/female
	public static function statisticsPigeonsMaleFemale()
	{
		$male=$female=$unknown=0;
		$query=Pigeon::find()->where(['IDuser'=>Yii::$app->user->getId()])->all();
		foreach($query as $key=>$value)
		{
			if($value->sex==Pigeon::MALE_PIGEON)
				$male++;
			else if ($value->sex==Pigeon::FEMALE_PIGEON)
				$female++;
			else
				$unknown++;
		}
	
		
		/* Create and populate the pData object */
		$MyData = new \pData();   
		$MyData->loadPalette(Yii::getAlias("@common")."/pchart/palettes/light.color", TRUE);
		$MyData->addPoints(array($male,$female, $unknown),"ScoreA");  
		$MyData->setSerieDescription("ScoreA","Application A");
		
		/* Define the absissa serie */
		$MyData->addPoints(array(Yii::t('default', 'Male')." ($male)", Yii::t('default', 'Female')." ($female)", Yii::t('default', 'No sex')." ($unknown)"),"Labels");
		$MyData->setAbscissa("Labels");
		
		/* Create the pChart object */
		$myPicture = new \pImage(400,230,$MyData);
		
		/* Set the default font properties */ 
		$myPicture->setFontProperties(array("FontName"=>Yii::getAlias("@common")."/pchart/fonts/verdana.ttf","FontSize"=>10,"R"=>80,"G"=>80,"B"=>80));	
			
		/* Create the pPie object */ 
		$PieChart = new \pPie($myPicture,$MyData);
		
		/* Draw two AA pie chart */ 
		$PieChart->draw2DPie(100,100,array("Border"=>TRUE, "Radius"=>100));
		
		/* Write down the legend next to the 2nd chart*/
		$PieChart->drawPieLegend(100+150,100-40);
		
		/* Render the picture (choose the best way) */
		$myPicture->autoOutput(Yii::getAlias("@webroot")."/temp/example.drawPieLegendMaleFemale.png");
	}
	
	//return BAR CHART for year of pigeons
	public static function statsticsPigeonsYear()
	{
		$pigeonTable=Pigeon::getTableSchema();
		$query = (new Query())->select(["COUNT(*) as count", "year", "sex"])->from($pigeonTable->name)->where(['IDuser'=>Yii::$app->user->getId()])->groupBy('year, sex');
		$command = $query->createCommand();
		$rows = $command->queryAll();
		$year=[]; $maleCount=[]; $femaleCount=[]; $unknownCount=[];		
		foreach($rows as $value)
		{
			$year[$value["year"]]=$value["year"];
			$femaleCount[$value["year"]]=empty($femaleCount[$value["year"]])?0:$femaleCount[$value["year"]];
			$maleCount[$value["year"]]=empty($maleCount[$value["year"]])?0:$maleCount[$value["year"]];
			$unknownCount[$value["year"]]=empty($unknownCount[$value["year"]])?0:$unknownCount[$value["year"]];
			if($value["sex"]==Pigeon::MALE_PIGEON)
			{
				$maleCount[$value["year"]]=$value["count"];
			}
			
			if($value["sex"]==Pigeon::FEMALE_PIGEON)
			{
				$femaleCount[$value["year"]]=$value["count"];
			}
			
			if($value["sex"]==Pigeon::UNKNOWN_SEX_PIGEON) 
			{
				$unknownCount[$value["year"]]=$value["count"];
			}
		}
		
		 /* Create and populate the pData object */ 
		$MyData = new \pData();   
		$MyData->loadPalette(Yii::getAlias("@common")."/pchart/palettes/light.color", TRUE);
		$MyData->addPoints($maleCount,Yii::t('default', 'Male')); 
		$MyData->addPoints($femaleCount,Yii::t('default', 'Female')); 
		$MyData->addPoints($unknownCount,Yii::t('default', 'No sex')); 
		$MyData->setAxisName(0,Yii::t('default', 'Number of pigeons')); 
		$MyData->addPoints($year,"Year"); 
		$MyData->setSerieDescription("Year",Yii::t('default', 'Year')); 
		$MyData->setAbscissa("Year"); 
		
		/* Create the pChart object */ 
		$myPicture = new \pImage(900,1000,$MyData); 
		
		/* Turn of Antialiasing */ 
		$myPicture->Antialias = FALSE; 
		
		/* Set the default font */ 
		$myPicture->setFontProperties(array("FontName"=>Yii::getAlias("@common")."/pchart/fonts/verdana.ttf","FontSize"=>10)); 
		
		/* Define the chart area */ 
		$myPicture->setGraphArea(60,60,800,1000); 
		
		/* Draw the scale */ 
		$scaleSettings = array("GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE, "Mode"=>SCALE_MODE_START0, "Pos"=>SCALE_POS_TOPBOTTOM); 
		$myPicture->drawScale($scaleSettings); 
		
		/* Write the chart legend */ 
		$myPicture->drawLegend(480,12,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL)); 
		
		
		/* Draw the chart */ 
		$myPicture->drawBarChart(); 
		
		/* Render the picture (choose the best way) */ 
		$myPicture->autoOutput(Yii::getAlias("@webroot")."/temp/example.drawBarChartYear.png"); 
	}
	
	//return BAR CHART for country of pigeons
	public static function statsticsPigeonsCountry()
	{
		$pigeonTable=Pigeon::getTableSchema();
		$pigeonCountryTable=PigeonCountry::getTableSchema();
		$query = (new Query())
				->select(["COUNT(*) as count", "IDcountry", "country"])
				->from($pigeonTable->name)
				->leftJoin($pigeonCountryTable->name, "$pigeonCountryTable->name.ID=$pigeonTable->name.IDcountry", $params = [] )
				->where(['IDuser'=>Yii::$app->user->getId()])
				->groupBy('IDcountry');
		$command = $query->createCommand();
		$rows = $command->queryAll();

		foreach($rows as $value)
		{
			$countryCount[$value["IDcountry"]]=$value["count"];
			$countryLabel[$value["IDcountry"]]=$value["country"];
		}

		 /* Create and populate the pData object */ 
		$MyData = new \pData();   
		$MyData->loadPalette(Yii::getAlias("@common")."/pchart/palettes/light.color", TRUE);
		$MyData->addPoints($countryCount, "Countries"); 
		$MyData->setAxisName(0,Yii::t('default', 'Number of pigeons')); 
		$MyData->addPoints($countryLabel,"Country Name"); 
		//$MyData->setSerieDescription("Country Name", ""); 
		$MyData->setAbscissa("Country Name"); 
		
		/* Create the pChart object */ 
		$myPicture = new \pImage(800,230,$MyData); 
		
		/* Turn of Antialiasing */ 
		$myPicture->Antialias = FALSE; 
		
		/* Set the default font */ 
		$myPicture->setFontProperties(array("FontName"=>Yii::getAlias("@common")."/pchart/fonts/verdana.ttf","FontSize"=>10)); 
		
		/* Define the chart area */ 
		$myPicture->setGraphArea(60,40,650,200); 
		
		/* Draw the scale */ 
		$scaleSettings = array("GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE, "Mode"=>SCALE_MODE_START0); 
		$myPicture->drawScale($scaleSettings); 
		
		/* Write the chart legend */ 
		$myPicture->drawLegend(480,12,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL)); 
		
		
		/* Draw the chart */ 
		$myPicture->drawBarChart(); 
		
		/* Render the picture (choose the best way) */ 
		$myPicture->autoOutput(Yii::getAlias("@webroot")."/temp/example.drawBarChartCountry.png"); 

	}

	//return PIE CHART for status of pigeons
	public static function statsticsPigeonsStatus()
	{
		$pigeonTable=Pigeon::getTableSchema();
		$statusTable=Status::getTableSchema();
		$query = (new \yii\db\Query())
				->select(["COUNT(*) as count", "IDstatus", "status"])
				->from($pigeonTable->name)
				->leftJoin($statusTable->name, "$statusTable->name.ID=$pigeonTable->name.IDstatus")
				->where(["$pigeonTable->name.IDuser"=>Yii::$app->user->getId()])
				->groupBy('IDstatus');
		$command = $query->createCommand();
		$rows = $command->queryAll();
		foreach($rows as $value)
		{
			$statusCount[$value["IDstatus"]]=$value["count"];
			$statusName[$value["IDstatus"]]=$value["status"]. " (".$value["count"].")";
		}
		
		/* Create and populate the pData object */
		$MyData = new \pData();   
		$MyData->loadPalette(Yii::getAlias("@common")."/pchart/palettes/light.color", TRUE);
		$MyData->addPoints($statusCount,"ScoreA");  
		$MyData->setSerieDescription("ScoreA","Application A");
		
		/* Define the absissa serie */
		$MyData->addPoints($statusName,"Labels");
		$MyData->setAbscissa("Labels");
		
		/* Create the pChart object */
		$myPicture = new \pImage(500,230,$MyData);
		
		/* Set the default font properties */ 
		$myPicture->setFontProperties(array("FontName"=>Yii::getAlias("@common")."/pchart/fonts/verdana.ttf","FontSize"=>10,"R"=>80,"G"=>80,"B"=>80));	
			
		/* Create the pPie object */ 
		$PieChart = new \pPie($myPicture,$MyData);
		
		/* Draw two AA pie chart */ 
		$PieChart->draw2DPie(100,100,array("Border"=>TRUE, "Radius"=>100));
		
		/* Write down the legend next to the 2nd chart*/
		$PieChart->drawPieLegend(100+150,100-80);
		
		/* Render the picture (choose the best way) */
		$myPicture->autoOutput(Yii::getAlias("@webroot")."/temp/example.drawPieLegendStatus.png");			
	}
	
	
	/*--------------------------------------------------RACING TABLE---------------------------------------------*/
	//LINE CHART for racing table
	/*
	* $racing_table_cat - IDcategory in RacingTable 
	* $pigeon_number - IDpigeon in RacingTable
	*/
	public static function statisticsRacingTable($racing_table_cat, $pigeon_number, $query)
	{
		foreach($query as $value)
		{
			$distance[]=$value->distance;
			$competitors[]=$value->participated_competitors;
			$pigeons[]=$value->participated_pigeons;
			$place[]=$value->won_place;
			$year[]=date("d.m.Y", strtotime($value->racing_date));	
		}
				
		/* Create and populate the pData object */ 
		$MyData = new \pData();   
		$MyData->loadPalette(Yii::getAlias("@common")."/pchart/palettes/light.color", TRUE);
		$MyData->addPoints($distance, Yii::t('default', 'Udaljenost')); 
		$MyData->setSerieWeight(Yii::t('default', 'Udaljenost'),1); 
		$MyData->setAxisName(0,Yii::t('default', 'Udaljenost'));
	
		$MyData->addPoints($competitors, Yii::t('default', 'Sud Natjecateljsa')); 
		$MyData->setSerieWeight(Yii::t('default', 'Sud Natjecateljsa'),1); 
		$MyData->setAxisName(0,Yii::t('default', 'Sud Natjecateljsa')); 
	
		$MyData->addPoints($pigeons,  Yii::t('default', 'Sud Golubova')); 
		$MyData->setSerieWeight( Yii::t('default', 'Sud Golubova'),1); 
		$MyData->setAxisName(0, Yii::t('default', 'Sud Golubova')); 
	
		$MyData->addPoints($place,  Yii::t('default', 'Osv Mjesto')); 
		$MyData->setSerieWeight(Yii::t('default', 'Osv Mjesto'),1); 
		$MyData->setAxisName(0, Yii::t('default', 'Osv Mjesto')); 
	
		$MyData->addPoints($year,"Labels"); 
		$MyData->setSerieDescription("Labels","Year"); 
		$MyData->setAbscissa("Labels"); 
		
		/* Create the pChart object */ 
		$myPicture = new \pImage(1000,450,$MyData); 
		
		/* Write the chart title */  
		$myPicture->setFontProperties(array("FontName"=>Yii::getAlias("@common")."/pchart/fonts/verdana.ttf","FontSize"=>8,"R"=>0,"G"=>0,"B"=>0)); 
		$myPicture->drawText(10,16,Yii::t('default','Racing table stats'),array("FontSize"=>11,"Align"=>TEXT_ALIGN_BOTTOMLEFT)); 
		
		/* Set the default font */ 
		$myPicture->setFontProperties(array("FontName"=>Yii::getAlias("@common")."/pchart/fonts/verdana.ttf","FontSize"=>6,"R"=>0,"G"=>0,"B"=>0)); 
		/* Define the chart area */ 
		$myPicture->setGraphArea(60,40,1000,400); 
		
		/* Draw the scale */ 
		$scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE); 
		$myPicture->drawScale($scaleSettings); 
		
		/* Draw the line chart */ 
		$myPicture->drawLineChart(); 
		$myPicture->drawPlotChart(array("DisplayValues"=>TRUE,"PlotBorder"=>TRUE,"BorderSize"=>2,"Surrounding"=>-60,"BorderAlpha"=>80)); 
		
		/* Write the chart legend */ 
		$myPicture->drawLegend(590,9,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL,"FontR"=>0,"FontG"=>0,"FontB"=>0)); 
		
		/* Render the picture (choose the best way) */ 
		$myPicture->autoOutput(Yii::getAlias("@webroot")."/temp/example.drawLineChart.plotsLINECHART.png"); 
		
	}
}
?>