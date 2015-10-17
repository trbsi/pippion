<?php

namespace backend\controllers;

use Yii;
use backend\models\search\CoupleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\Url;

use yii\db\IntegrityException;
use yii\web\HttpException;

use backend\helpers\ExtraFunctions;
use backend\models\Subscription;
use backend\models\CoupleRacing;
use backend\models\CoupleBreeding;
use backend\models\BroodRacing;
use backend\models\BroodBreeding;

use backend\helpers\Mysqli;

/**
 * CoupleController implements the CRUD actions for CoupleRacing model.
 */
class CoupleRacingController extends Controller
{
	private $_MODEL;//model - new CoupleRacing or new CoupleBreeding
	private $_MODEL_1;
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
		
		//check if url contains "couple-racing" or "couple-breeding" word so you can know which model to use	
		$REQUEST_URI=$_SERVER['REQUEST_URI'];
		$racing=strpos($REQUEST_URI,"couple-racing");
		$breeding=strpos($REQUEST_URI,"couple-breeding");
		
		if($racing!=false)
		{
			$this->_MODEL = new CoupleRacing;
			$this->_MODEL_CHOOSE = "CoupleRacing";
		}
		else if($breeding!=false)
		{
			$this->_MODEL = new CoupleBreeding;
			$this->_MODEL_CHOOSE = "CoupleBreeding";
		}
		else
		{
			$this->_MODEL = new CoupleRacing;
			$this->_MODEL_CHOOSE = "CoupleRacing";
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
                        'actions' => ['index', 'update', 'delete', 'view', 'create', 'hatching-diary', 'list-couples'],
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
	* creating hatching diary for print or download
	* list hatching diaries of chosen couple
	* I'm sending IDcouple from 
			/couple-breeding/index (couple-racing/index) via See Diary button
			/couple-breeding/hatching-diary (/couple-breeding/hatching-diary) when user chooses couple and submits it
	* "couple_year" which is being set as url paramtere is not really important here but used in CoupleRacing::chooseCouplesDropDown() to collect all couples in specific year and fill dropdown(MIGHT BE USEFUL here in future)		
	*/
	public function actionHatchingDiary($IDcouple=NULL)
	{
		$model = $this->_MODEL;
		$dataProvider=NULL;
    	if(isset($_POST["printdownload_year"]))
		{
			$Mysqli = new Mysqli;
			$mysqli=$Mysqli->connectMysqli();
			
			if($this->_MODEL_CHOOSE=="CoupleRacing")
			{
				$coupleTable=CoupleRacing::getTableSchema();
				$broodTable=BroodRacing::getTableSchema();
			}
			else if($this->_MODEL_CHOOSE=="CoupleBreeding")
			{
				$coupleTable=CoupleBreeding::getTableSchema();
				$broodTable=BroodBreeding::getTableSchema();
			}
			
			return $this->renderPartial('/couple/hatchingdiary/hatchingdiary_main', 
				[
					'_POST_USER_ID'=>Yii::$app->user->getId(),
					'_POST_YEAR'=>$_POST["printdownload_year"],
					'mysqli'=>$mysqli,
					'broodTable'=>$broodTable->name,
					'coupleTable'=>$coupleTable->name,
					
				]
			);
		}
		
		if(isset($IDcouple))
		{
			$coupleDetails = $this->_MODEL->hatchingDiaryCoupleDetails($IDcouple, $this->_MODEL, $this->_MODEL_CHOOSE);
			$dataProvider = $this->_MODEL->broodsOfSpecificCouple($IDcouple, $this->_MODEL, $this->_MODEL_CHOOSE);
			 return $this->render('/couple/hatching-diary', [
				'_MODEL_CHOOSE'=>$this->_MODEL_CHOOSE,
				'dataProvider'=>$dataProvider,
				'coupleDetails'=>$coupleDetails,
			]);

		}
		
	
	    return $this->render('/couple/hatching-diary', [
			'_MODEL_CHOOSE'=>$this->_MODEL_CHOOSE,
        ]);
	}

	/*
	* find all couples depending are they racing or breeding and return json to fill dropdownlist for posted year
	* called from CoupleRacing::chooseCouplesDropDown()
	*/
	public function actionListCouples()
	{
		if($this->_MODEL_CHOOSE=="CoupleRacing")
		{
			$modelCouple = new CoupleRacing;
		}
		else if($this->_MODEL_CHOOSE=="CoupleBreeding")
		{
			$modelCouple = new CoupleBreeding;
		}


		if(isset($_POST["couple_year"]))
			$year=$_POST["couple_year"];
			
		if(isset($year) && !empty($year))
		{
			$couples=$modelCouple->find()->where(['IDuser'=>Yii::$app->user->getId(), 'year'=>$year] )->orderBy('couplenumber ASC')->all();
			$return=[];
			$i=0;
			foreach($couples as $key=>$value)
			{
				$content=$this->_MODEL->formatCouple($value);

				$return[$i]["key"]=$value->ID;
				$return[$i]["value"]=$content;
				$i++;
			} 
			echo json_encode($return);
		}
		/*
		[
			{"key":39,"value":"Par [1-01] - M\/33352-12\/CRO <==> \u017d\/20001-13\/CRO"},
			{"key":40,"value":"Par [1-02] - M\/06557-10-143\/DV <==> \u017d\/112-12-2502\/SLO"},
			{"key":41,"value":"Par [1-03] - M\/33853-07\/CRO <==> \u017d\/27742-08\/CRO"},
			{"key":43,"value":"Par [1-04] - M\/06557-03-292\/DV <==> \u017d\/11061-13\/CRO"},
			{"key":44,"value":"Par [1-05] - M\/0895-11-453\/DV <==> \u017d\/00060-10\/CRO"},
			...
		]
		*/
	}


    /**
     * Lists all CoupleRacing models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CoupleSearch($this->_MODEL_CHOOSE);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('/couple/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            '_MODEL_CHOOSE' => $this->_MODEL_CHOOSE,
        ]);
    }

    /**
     * Displays a single CoupleRacing model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('/couple/view', [
            'model' => $this->findModel($id),
			'_MODEL_CHOOSE'=>$this->_MODEL_CHOOSE
        ]);
    }

    /**
     * Creates a new CoupleRacing model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = $this->_MODEL;

        if ($model->load(Yii::$app->request->post())) 
		{
			$model->IDuser=Yii::$app->user->getId();
			$model->male=$_POST["Father_ID"];
			$model->female=$_POST["Mother_ID"];
			$model->save();
            return $this->redirect(['view', 'id' => $model->ID]);
        } 
		else 
		{
            return $this->render('/couple/create', [
                'model' => $model,
				'_MODEL_CHOOSE'=>$this->_MODEL_CHOOSE,
            ]);
        }
    }

    /**
     * Updates an existing CoupleRacing model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) 
		{
			$model->IDuser=Yii::$app->user->getId();
			$model->male=$_POST["Father_ID"];
			$model->female=$_POST["Mother_ID"];
			$model->save();
            return $this->redirect(['view', 'id' => $model->ID]);
        } 
		else 
		{
            return $this->render('/couple/update', [
                'model' => $model,
				'_MODEL_CHOOSE'=>$this->_MODEL_CHOOSE,
            ]);
        }
    }

    /**
     * Deletes an existing CoupleRacing model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		if($this->_MODEL_CHOOSE=="CoupleRacing")
		{
			$redirect=Url::to('/couple-racing/index');
		}
		else
		{
			$redirect=Url::to('/couple-breeding/index');
		}
		
		try
		{
			$this->findModel($id)->delete();
			Yii::$app->session->setFlash('success', Yii::t('default','Action was successful'));
		}
		catch(IntegrityException $e)
		{
			throw new HttpException(400, Yii::t('default', 'DELETE_PAR'));
		}

        

        return $this->redirect($redirect);
    }

    /**
     * Finds the CoupleRacing model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CoupleRacing the loaded model
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
}
