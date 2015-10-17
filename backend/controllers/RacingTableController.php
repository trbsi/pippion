<?php

namespace backend\controllers;

use Yii;
use backend\models\RacingTable;
use backend\models\search\RacingTableSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\Url;

use yii\db\IntegrityException;
use yii\web\HttpException;

use backend\helpers\ExtraFunctions;
use backend\helpers\Mysqli;
use backend\models\Subscription;
use backend\models\Pigeon;

/**
 * RacingTableController implements the CRUD actions for RacingTable model.
 */
class RacingTableController extends Controller
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
                        'actions' => ['index', 'update', 'delete', 'view', 'create', 'download-print', 'dependant-racing-table'],
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
	* called from statistics/table-racing
	* when user chooses racing table category return values for dropdownlist of all pigeons who belongs to this category
	*/
	public function actionDependantRacingTable()
	{
		if(isset($_POST["IDcategory"]))
		{
			$array=[];
			$racingTableTable=RacingTable::getTableSchema();
			$query=RacingTable::find()
				->joinWith('relationIDpigeon')
				->select("*")
				->distinct(true)
				->where([$racingTableTable->name.'.IDuser'=>Yii::$app->user->getId(), 'IDcategory'=>(int)$_POST["IDcategory"]])
				->all();
			
			$i=0;
			foreach($query as $value)
			{
				$array[$i]["key"]=$value->IDpigeon;
				$array[$i]["value"]="[".$value->relationIDpigeon->pigeonnumber."] ".Pigeon::getSex($value->relationIDpigeon->sex)."/".$value->relationIDpigeon->relationIDcountry->country;
				$i++;
			}
			
			echo json_encode($array);
		}

	}

	/*
	* using to print or download racing table        
	* the same as actionView but this shows just table without css
	*/
	public function actionDownloadPrint($target, $pid=NULL, $cid=NULL)
	{
		$searchModel = new RacingTableSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, array('target'=>$target, 'pid'=>$pid, 'cid'=>$cid), 1000);

		$RacingTable = new RacingTable;		
		$littleTable=$RacingTable->littleTable($target, $pid, $cid);
		$h2=$RacingTable->racingTableH2($target, $pid, $cid);
		
        return $this->renderPartial('download-print', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'h2'=>$h2,
			'littleTable'=>$littleTable,
        ]);

	}


    /**
     * Lists all RacingTable models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RacingTableSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single RacingTable model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($target, $pid=NULL, $cid=NULL)
    {
		$searchModel = new RacingTableSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, array('target'=>$target, 'pid'=>$pid, 'cid'=>$cid));
		
		$RacingTable = new RacingTable;		
		$littleTable=$RacingTable->littleTable($target, $pid, $cid);
		$h2=$RacingTable->racingTableH2($target, $pid, $cid);
		
        return $this->render('view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'h2'=>$h2,
			'littleTable'=>$littleTable,
        ]);
    }

    /**
     * Creates a new RacingTable model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RacingTable();

        if ($model->load(Yii::$app->request->post())) 
		{
			/*
			var_dump(Yii::$app->request->post());
			array(6) 
			{ 
				["_csrf"]=> string(56) "Si1YaE9aRXkmagwZHi4xJiJJH1EJYjMvc14SGD4RfDsZTgELCBYCGg==" 
				["RacingTable"]=> array(3) 
				{ 
					["IDcategory"]=> string(1) "8" 
					[0]=> array(6) 
					{ 
						["racing_date"]=> string(10) "2015-04-24" 
						["place_of_release"]=> string(7) "Ä‘akovo" 
						["distance"]=> string(6) "125.36" 
						["participated_competitors"]=> string(3) "123" 
						["participated_pigeons"]=> string(2) "50" 
						["won_place"]=> string(1) "1" 
						
					} 
					[1]=> array(6) 
					{ 
						["racing_date"]=> string(10) "2015-03-24" 
						["place_of_release"]=> string(6) "Pakrac" 
						["distance"]=> string(6) "123.69" 
						["participated_competitors"]=> string(2) "78" 
						["participated_pigeons"]=> string(2) "25" 
						["won_place"]=> string(1) "3" 
					} 
				 } 
				 ["malefemale"]=> string(4) "male" 
				 ["dependentCountryFather"]=> string(1) "1" 
				 ["Father_ID"]=> string(3) "449"
				 ["dependentCountryMother"]=> string(0) "" 
			}
			*/
			
			if($_POST["malefemale"]=="male")
			{
				$IDpigeon=$_POST["Father_ID"];
			}
			else if($_POST["malefemale"]=="female")
			{
				$IDpigeon=$_POST["Mother_ID"];
			}
			$IDcategory=$_POST['RacingTable']['IDcategory'];
			$IDuser=Yii::$app->user->getId();

			//idi red po red i postavi sve atribute
			foreach($_POST['RacingTable'] as $key=>$value)
			{
				$model=new RacingTable;
				//http://www.yiiframework.com/doc/api/1.1/CModel#setAttributes-detail
				$model->attributes=$_POST['RacingTable'][$key];
				$model->IDuser=$IDuser;
				$model->IDcategory=$IDcategory;
				$model->IDpigeon=$IDpigeon;
				if($model->validate())
					$model->save();
			}
			
			Yii::$app->session->setFlash('success', Yii::t('default', 'Action was successful'));
            return $this->redirect(['view', 'target'=>'both', 'pid'=>$IDpigeon, 'cid'=>$IDcategory]);
        } 
		else 
		{
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing RacingTable model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) 
		{
			Yii::$app->session->setFlash('success', Yii::t('default','Action was successful'));
            return $this->redirect(['view', 'target'=>'both', 'pid'=>$model->IDpigeon, 'cid'=>$model->IDcategory]);
        } 
		else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing RacingTable model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		try
		{
			$model=$this->findModel($id);
			$model->delete();
			Yii::$app->session->setFlash('success', Yii::t('default','Action was successful'));
		}
		catch(IntegrityException $e)
		{
			throw new HttpException(400, Yii::t('default', 'Action was now successful'));
		}

       
		//when result is deleted check if there are more results from that category and that pigeon. 
		//If there isn't any redirect to index
		$r=RacingTable::find()->where(['IDuser'=>Yii::$app->user->getId(), 'IDcategory'=>$model->IDcategory, 'IDpigeon'=>$model->IDpigeon])->all();
		if(!empty($r))
			return $this->redirect(['view', 'target'=>'both', 'pid'=>$model->IDpigeon, 'cid'=>$model->IDcategory]);
		else
			return $this->redirect(['index']);
    }

    /**
     * Finds the RacingTable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RacingTable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RacingTable::find()->where(['ID'=>$id, 'IDuser'=>Yii::$app->user->getId()])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
