<?php

namespace backend\controllers;

use Yii;
use backend\models\search\BroodSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\Url;

use backend\helpers\ExtraFunctions;
use backend\models\Subscription;
use backend\models\BroodRacing;
use backend\models\BroodBreeding;
use backend\models\CoupleRacing;
use backend\models\CoupleBreeding;
use backend\models\PigeonList;
use backend\models\Pigeon;

use yii\db\IntegrityException;
use yii\web\HttpException;

/**
 * BroodController implements the CRUD actions for BroodBreeding model.
 */
class BroodRacingController extends Controller
{
	private $_MODEL; // main model: new BroodBreeding or new BroodRacing
	private $_MODEL_1; //extra model if I need it
	private $_MODEL_CHOOSE;

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
	
		//	check if url contains "brood-racing" or "brood-breeding" word so you can know which model to use	
		$REQUEST_URI=$_SERVER['REQUEST_URI'];
		$racing=strpos($REQUEST_URI,"brood-racing");
		$breeding=strpos($REQUEST_URI,"brood-breeding");
		
		if($racing!=false)
		{
			$this->_MODEL=new BroodRacing;
			$this->_MODEL_1=new BroodRacing;
			$this->_MODEL_CHOOSE="BroodRacing";
		}
		else if($breeding!=false)
		{
			$this->_MODEL=new BroodBreeding;
			$this->_MODEL_1=new BroodBreeding;
			$this->_MODEL_CHOOSE="BroodBreeding";
		}
		else
		{
			$this->_MODEL=new BroodRacing;
			$this->_MODEL_1=new BroodRacing;
			$this->_MODEL_CHOOSE="BroodRacing";
		}

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
                        'actions' => ['index', 'update', 'delete', 'view', 'create', 'add-young-to-list'],
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
	* add young pigeons to list of all pigeons
	*/
	public function actionAddYoungToList()
	{
		
		if($this->_MODEL_CHOOSE=="BroodRacing")
		{
			$brood_column="IDbrood_racing";
			$broodTable = new BroodRacing;
			$coupleTable = new CoupleRacing;
			$redirect=Url::to('/brood-racing/index');
		}
		else if($this->_MODEL_CHOOSE=="BroodBreeding")
		{
			$brood_column="IDbrood_breeding";
			$broodTable = new BroodBreeding;
			$coupleTable = new CoupleBreeding;
			$redirect=Url::to('/brood-breeding/index');
		}
		
		if(isset($_GET['Status']['ID']) && !empty($_GET['Status']['ID']) && isset($_GET['selection']) && !empty($_GET['selection']))
		{
			$status=$_GET['Status']['ID'];
			foreach($_GET['selection'] as $key=>$ID_brood) //$ID_brood is ID in mg_brood_breeding/mg_brood_racing
			{
				//Check if young pigeon exist in list of all pigeons
				$count=PigeonList::find()->where(['IDuser'=>Yii::$app->user->getId(), $brood_column=>$ID_brood])->count();
				if($count>=1)
					continue;
				else
				{
					//get ring number, color and IDcountry
					$data=$broodTable->find()->where(['IDuser'=>Yii::$app->user->getId(), 'ID'=>$ID_brood])->one();
					$ringnumber=$data->ringnumber;
					$IDcountry=$data->IDcountry;
					$color=$data->color;

					$IDcouple=$data->IDcouple;
					
					//get ID of mother and father and year
					$data2=$coupleTable->find()->where(['IDuser'=>Yii::$app->user->getId(),'ID'=>$IDcouple])->one();
					$father=$data2->male;
					$mother=$data2->female;
					$year=$data2->year;
					
					//add that young pigeon to the list 		
					$Pigeon=new Pigeon;
					$Pigeon->pigeonnumber=$ringnumber;
					$Pigeon->color=$color;
					$Pigeon->IDcountry=$IDcountry;
					$Pigeon->IDuser=Yii::$app->user->getId();
					$Pigeon->year=$year;
					$Pigeon->IDstatus=$status;
					$Pigeon->sex="?";
					$Pigeon->save();
					
					//add young pigeon to mg_pigeon_list 
					$PigeonList= new PigeonList;
					$PigeonList->IDuser=Yii::$app->user->getId();
					$PigeonList->IDpigeon=$Pigeon->ID;
					$PigeonList->$brood_column=$ID_brood;
					$PigeonList->IDfather=$father;
					$PigeonList->IDmother=$mother;
					$PigeonList->save();
				}
			}//end foreach
			Yii::$app->session->setFlash('success', Yii::t('default','Action was successful'));
		}//end else
		else
		{
			Yii::$app->session->setFlash('danger', Yii::t('default',"Action was not successful"));		
		}

		return \Yii::$app->response->redirect($redirect, 301)->send(); 
	}
	
	
    /**
     * Lists all BroodBreeding models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BroodSearch($this->_MODEL_CHOOSE);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('/brood/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'_MODEL_CHOOSE'=>$this->_MODEL_CHOOSE
        ]);
    }

    /**
     * Displays a single BroodBreeding model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		//choose correct brood
		$id=$this->chooseBrood($id);


        return $this->render('/brood/view', [
            'model' => $this->findModel($id),
			'model1'=>$this->findModel($id+1),
			'_MODEL_CHOOSE'=>$this->_MODEL_CHOOSE,

        ]);
    }

    /**
     * Creates a new BroodBreeding model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		//BroodBreeding extends BroodRacing, that's way here can be used BroodRacing as model
		//they both have exactly the same properties and functions except some relations
        $model0 = $this->_MODEL;
		$model1 = $this->_MODEL_1;//extra model
		
        if ($model0->load(Yii::$app->request->post()) && $model1->load(Yii::$app->request->post())) 
		{
			
			//------------the same in UPDATE+CREATE------------
			/*
			var_dump(Yii::$app->request->post());
			array(4) 
			{ 
				["_csrf"]=> string(56) "WjNqcXgxUzc2dD4AKUUnaDJXLUg.CSVhY0AgAQl6anUJUDMSP30UVA==" 
				["year"]=> string(4) "2014" 
				["IDcouple"]=> string(2) "39" 
				["BroodBreeding"]=> array(4) 
				{ 
					["firstegg"]=> string(10) "2014-12-22" 
					["hatchingdate"]=> string(10) "2000-01-01" 
					[0]=> array(3) 
					{ 
						["IDcountry"]=> string(1) "1" 
						["ringnumber"]=> string(0) "" 
						["color"]=> string(0) "" 
					} 
					[1]=> array(3) 
					{ 
						["IDcountry"]=> string(1) "1" 
						["ringnumber"]=> string(0) "" 
						["color"]=> string(0) "" 
					 } 
				} 
			}			
			*/
			$post=Yii::$app->request->post();
			$value=$post[$this->_MODEL_CHOOSE];

			$model0->attributes = $value[0];
			$model1->attributes = $value[1];
			$IDcouple=$_POST["IDcouple"];
			$firstegg=$value["firstegg"];
			$hatchingdate=$value["hatchingdate"];

			$model0->IDuser=$model1->IDuser=Yii::$app->user->getId();
			//pick and assign fields that are not assigned via $model->attributes	
			$model0->firstegg=$model1->firstegg=$firstegg;
			$model0->hatchingdate=$model1->hatchingdate=$hatchingdate;
			$model0->IDcouple=$model1->IDcouple=$IDcouple;
			//-----------the same in UPDATE+CREATE------------
			
			if($model0->save() && $model1->save())
			{
				//bow update it and add IDD
				$IDD=$model0->ID."-".$model1->ID;
				$model0->IDD=$IDD;
				$model0->save();
				
				$model1->IDD=$IDD;
				$model1->save();
				
             	return $this->redirect(['view', 'id' => $model0->ID]);
			}
        } 
		return $this->render('/brood/create', [
			'model0' => $model0,
			'model1' => $model1,
			'_MODEL_CHOOSE'=>$this->_MODEL_CHOOSE
		]);
    }

    /**
     * Updates an existing BroodBreeding model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		//choose right brood
		$id=$this->chooseBrood($id);
		
        $model0 = $this->findModel($id);
        $model1 = $this->findModel($id+1);

        if ($model0->load(Yii::$app->request->post()) && $model1->load(Yii::$app->request->post())) 
		{
			//------------the same in UPDATE+CREATE------------
			/*
			var_dump(Yii::$app->request->post());
			array(3) 
			{ 
				["_csrf"]=> string(56) "ai53b1p5ajQGaSMeCw0eawJKMFYcQRxiU109HysyU3Y5TS4MHTUtVw==" 
				["year"]=> string(4) "2014" 
				["BroodBreeding"]=> array(5) 
				{ 
					["IDcouple"]=> string(2) "39" 
					["firstegg"]=> string(10) "2014-12-21" 
					["hatchingdate"]=> string(10) "2000-01-01" 
					[0]=> array(3) 
					{ 
						["IDcountry"]=> string(1) "1" 
						["ringnumber"]=> string(3) "qqq" 
						["color"]=> string(3) "www" 
					} 
					[1]=> array(3) 
					{ 
						["IDcountry"]=> string(2) "11" 
						["ringnumber"]=> string(3) "eee" 
						["color"]=> string(3) "rrr" 
					} 
				} 
			}			
			*/
			$post=Yii::$app->request->post();
			$value=$post[$this->_MODEL_CHOOSE];

			$model0->attributes = $value[0];
			$model1->attributes = $value[1];
			$IDcouple=$_POST["IDcouple"];
			$firstegg=$value["firstegg"];
			$hatchingdate=$value["hatchingdate"];

			$model0->IDuser=$model1->IDuser=Yii::$app->user->getId();
			
			//pick and assign fields that are not assigned via $model->attributes	
			$model0->firstegg=$model1->firstegg=$firstegg;
			$model0->hatchingdate=$model1->hatchingdate=$hatchingdate;
			$model0->IDcouple=$model1->IDcouple=$IDcouple;
			//-----------the same in UPDATE+CREATE------------

			if($model0->save() && $model1->save())
				return $this->redirect(['view', 'id' => $model0->ID]);
        }
		
		return $this->render('/brood/update', [
			'model0' => $model0,
			'model1' => $model1,
			'_MODEL_CHOOSE'=>$this->_MODEL_CHOOSE,
		]);
    }

    /**
     * Deletes an existing BroodBreeding model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$id=$this->chooseBrood($id);

		try
		{
			$this->findModel($id)->delete();
			$this->findModel($id+1)->delete();
			Yii::$app->session->setFlash('success', Yii::t('default','Action was successful'));
		}
		catch(IntegrityException $e)
		{
			throw new HttpException(400, Yii::t('default', 'DELETE_LEGLO'));
		}

		if($this->_MODEL_CHOOSE=="BroodRacing")
		{
			$redirect=Url::to('/brood-racing/index');
		}
		else
		{
			$redirect=Url::to('/brood-breeding/index');
		}
		
        return $this->redirect($redirect);
    }

    /**
     * Finds the BroodBreeding model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BroodBreeding the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = $this->_MODEL->find()->where(['ID'=>$id, 'IDuser'=>Yii::$app->user->getId()])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	
	/**
	* When users chooses brood for view, update or delete be sure to choose the right two broods which have the same IDD
	* for example if user chooses ID=17 and ID =18 but they are not couple
	* @return - it will return lower ID, for example if ID=17 was sent it will look for that ID and return IDD. If IDD is 16-17 it will return 16
	* so anywhere I need I can use $id and $id+1 
	*/
	public function chooseBrood($id)
	{
		$brood=$this->_MODEL->find()->where(['ID'=>$id, 'IDuser'=>Yii::$app->user->getId()])->one();
		if($brood)
		{
		$IDD=explode('-', $brood->IDD);
		return $IDD[0];
		}
		else
			throw new HttpException(404,Yii::t('default', 'Not allowed'));
	}

}
