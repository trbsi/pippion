<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\Pigeon;
use backend\models\PigeonList;
use backend\models\Status;
use backend\models\CoupleRacing;
use backend\models\CoupleBreeding;
use backend\models\BroodRacing;
use backend\models\BroodBreeding;
use backend\helpers\ExtraFunctions;

/**
 * AdminNotsController implements the CRUD actions for AdminNots model.
 */
class QuickInsertController extends Controller
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
                        'actions' => ['create'],
                        'allow' => true,
						'roles' => ['@'],
                    ],
					[
						'actions'=>['delete', 'create', 'update', 'index', 'view'],
						'allow' => true,
						'roles'=>['@'],
						'matchCallback'=>function($rule, $action) ////http://www.yiiframework.com/wiki/771/rbac-super-simple-with-admin-and-user/
						{
							return Yii::$app->user->identity->getIsAdmin();
						}
					],
                ],
            ],

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Creates a new AdminNots model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$Pigeon = new Pigeon;
		$Status = new Status;
		$CoupleRacing = new CoupleRacing;
		$CoupleBreeding = new CoupleBreeding;
		$BroodRacing = new BroodRacing;
		$BroodBreeding = new BroodBreeding;
		
		//ADDING NEW PIGEONS
		if(isset($_POST["Pigeon"]) && $model=$_POST["Pigeon"])
		{
			$i=0;
			$arrayPigeonID=[];
			//save to mg_pigeon
			foreach($model as $key=>$value)
			{
				$Pigeon_tmp = new Pigeon;
				$Pigeon_tmp->IDuser=Yii::$app->user->getId();
				$Pigeon_tmp->attributes=$value;
				if(empty($Pigeon_tmp->pigeonnumber) || empty($Pigeon_tmp->IDstatus) || empty($Pigeon_tmp->year))
					continue;
				$Pigeon_tmp->save();
				$arrayPigeonID[$i]["pigeon_id"]=$Pigeon_tmp->ID;
				$arrayPigeonID[$i]["father"]=$_POST["father"][$i];
				$arrayPigeonID[$i]["mother"]=$_POST["mother"][$i];
				$i++;
			}
			
			//save to mg_pigeon_list
			foreach($arrayPigeonID as $key=>$value)
			{
				//find ID of the father and mother
				$father_id=Pigeon::find()->where(['IDuser'=>Yii::$app->user->getId(), 'pigeonnumber'=>$value["father"]])->one();
				$mother_id=Pigeon::find()->where(['IDuser'=>Yii::$app->user->getId(), 'pigeonnumber'=>$value["mother"]])->one();
				
				$PigeonList = new PigeonList;
				$PigeonList->IDuser = Yii::$app->user->getId();
				$PigeonList->IDpigeon = $value["pigeon_id"];
				$PigeonList->IDmother=empty($mother_id->ID) ? 0 : $mother_id->ID;
				$PigeonList->IDfather=empty($father_id->ID) ? 0 : $father_id->ID;
				$PigeonList->save();
			}
			
			Yii::$app->session->setFlash('success', Yii::t('default', 'Pigeons have been added'));
		}
		
		//CREATING NEW STATUS
		if(isset($_POST["Status"]) && $model=$_POST["Status"])
		{
			foreach($model as $key=>$value)
			{
				$Status_tmp = new Status;
				$Status_tmp->IDuser=Yii::$app->user->getId();
				$Status_tmp->frompedigree=0;
				$Status_tmp->attributes=$value;
				if(empty($Status_tmp->status))
					continue;
				$Status_tmp->save();
				
				Yii::$app->session->setFlash('success', Yii::t('default', 'Status has been created'));
			}
		}
		
		//ADD COUPLES
		if( isset($_POST["CoupleRacing"]) || isset($_POST["CoupleBreeding"]) )
		{
			if(isset($_POST["CoupleRacing"]))
			{
				$model = $_POST["CoupleRacing"];
				$Couple = new CoupleRacing;
			}
			else if(isset($_POST["CoupleBreeding"]))
			{
				$model = $_POST["CoupleBreeding"];
				$Couple = new CoupleBreeding;
			}
			
			foreach($model as $key=>$value)
			{
				$Couple_tmp = new $Couple;
				$Couple_tmp->IDuser=Yii::$app->user->getID();
				$Couple_tmp->attributes=$value;
				$Couple_tmp->save();
			}
			Yii::$app->session->setFlash('success', Yii::t('default', 'Couples have been created'));
		}
		
		//ADD BROODS
		if( isset($_POST["BroodRacing"]) || isset($_POST["BroodBreeding"]) )
		{
			
			if(isset($_POST["BroodRacing"]))
			{
				$Brood = new BroodRacing;
				$_MODEL_NAME=BroodRacing::BroodRacingName;
			}
			else if(isset($_POST["BroodBreeding"]))
			{
				$Brood = new BroodBreeding;
				$_MODEL_NAME=BroodRacing::BroodBreedingName;
			}
			$model = Yii::$app->request->post();
			$model_values=$model[$_MODEL_NAME];

			/*
			var_dump(Yii::$app->request->post());
			array(2) 
			{ 
				["_csrf"]=> string(56) "WGNiNHVRek8vDClHICc0PRsnUUIyAzsbLwAEUColFnosVT1tMj9PAQ==" 
				["BroodRacing"]=> array(4) 
				{ 
					[0]=> array(5) 
					{ 
						["IDcountry"]=> string(2) "43" 
						["firstegg"]=> string(0) "" 
						["hatchingdate"]=> string(10) "2015-04-17" 
						["ringnumber"]=> string(1) "1" 
						["color"]=> string(0) "" 
					 } 
					 [1]=> array(3) 
					 { 
						["IDcountry"]=> string(2) "43" 
						["ringnumber"]=> string(1) "2" 
						["color"]=> string(0) "" 
					 } 
					 [2]=> array(5) 
					 { 
						["IDcountry"]=> string(2) "43" 
						["firstegg"]=> string(0) "" 
						["hatchingdate"]=> string(0) "" 
						["ringnumber"]=> string(1) "3" 
						["color"]=> string(0) "" 
					  } 
					  [3]=> array(3) 
					  { 
						["IDcountry"]=> string(2) "43" 
						["ringnumber"]=> string(1) "4" 
						["color"]=> string(0) "" 
					  } 
				} 
			} 
			*/
			
			for($i=0; $i<count($model_values); $i=$i+2)
			{
				$j=$i+1;
				
				$Brood1 = new $Brood; $Brood2 = new $Brood; 
				$Brood1->attributes=$model_values[$i];
				$Brood2->attributes=$model_values[$j];
				
				$Brood2->firstegg=$Brood1->firstegg;
				$Brood2->hatchingdate=$Brood1->hatchingdate;
				$Brood2->IDcouple=$Brood1->IDcouple;
				$Brood1->IDuser=$Brood2->IDuser=Yii::$app->user->getId();

				if($Brood1->save() && $Brood2->save())
				{
					$IDD=$Brood1->ID."-".$Brood2->ID;
					$Brood1->IDD=$Brood2->IDD=$IDD;
					$Brood1->save();
					$Brood2->save();
					Yii::$app->session->setFlash('success', Yii::t('default', 'Couples have been created'));
				}
			}
		}
		
		return $this->renderPartial('create', [
			'Pigeon' => $Pigeon,
			'Status' => $Status,
			'CoupleBreeding' => $CoupleBreeding,
			'CoupleRacing' => $CoupleRacing,
			'BroodRacing' => $BroodRacing,
			'BroodBreeding' => $BroodBreeding,
		]);
    }

    /**
     * Lists all AdminNots models.
     * @return mixed
     */
  /*  public function actionIndex()
    {
        $searchModel = new AdminNotsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }*/

    /**
     * Displays a single AdminNots model.
     * @param integer $id
     * @return mixed
     */
  /*  public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }*/


    /**
     * Updates an existing AdminNots model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
   /* public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }*/

    /**
     * Deletes an existing AdminNots model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
   /* public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }*/

    /**
     * Finds the AdminNots model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminNots the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*protected function findModel($id)
    {
        if (($model = AdminNots::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }*/
}
