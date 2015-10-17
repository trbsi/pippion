<?php

namespace backend\controllers;

use Yii;
use backend\models\Pigeon;
use backend\models\PigeonFather;
use backend\models\PigeonMother;
use backend\models\PigeonList;
use backend\models\search\PigeonSearch;
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
class PigeonController extends Controller
{
	/*
	* execute this code before everything else
	*/
	public function beforeAction($action)
	{

		$x = new ExtraFunctions;
		$x->beforeActionTimeLanguage();
		$x->beforeActionBreederData();
		
		//if action is "pedigree" then don't check for subscription because pedigree is being generated from auctions, in this way people can create pedigrees even if subscription has ended but other stuff they can't
		/*if(Yii::$app->controller->action->id != "pedigree")
		{
			$s = new Subscription;
			$s->hasSubEnded();
		}*/
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
                        'actions' => ['index', 'update', 'delete', 'view', 'create', 'ajax-dependent-parents', 'ajax-delete', 'pedigree', 'upload-pigeon-eye'],
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
	
	
	/*
	* generating pedigree
	*/
	public function actionPedigree()
	{
		$createPedigree=0;
		
		//in auctions when user adds existing pigeon to sell pedigree has to be automatically created, everytime user clicks on pedigree
		//it will take him here to generate pedigree, so I have to send user ID
		//but also I'm sending here pigeon number instead of ID because I can't send ID since I don't know it(I don't know it because I save this pigeon to AuctionPigeon with limited info) so find what pigeon is it
		//also sent from pigeon/view (when user sees it's own pigeon he can create pedigree from there)
		if(isset($_POST["user"]))
		{
			$IDuser=(int)$_POST["user"];
			//sending from auction/view
			if(isset($_POST["pigeonnumber"]))
				$pigeon=Pigeon::find()->where(['IDuser'=>$IDuser, 'pigeonnumber'=>$_POST["pigeonnumber"]])->one();
		
			//sending from pigeon/view
			if(isset($_POST["IDpigeon"]))
				$pigeon=Pigeon::find()->where(['IDuser'=>$IDuser, 'ID'=>$_POST["IDpigeon"]])->one();
				
			$IDpigeon=$pigeon->ID;
			$createPedigree=1;
		}
		
		//sending directly from pigeon/pedigree
		//Father_ID and Mother_ID are ID of pigeons for which I want to create pedigree
		if(isset($_POST["Father_ID"]) || isset($_POST["Mother_ID"]))
		{
			//depending which radio button did user checked
			$IDpigeon=($_POST["malefemale"]=="male")?$_POST["Father_ID"]:$_POST["Mother_ID"];//ID of chosen pigeon
			$IDuser=Yii::$app->user->getId();
			$createPedigree=1;
		}
		
		if($createPedigree==1)
		{
			$Mysqli=new Mysqli;
			$mysqli=$Mysqli->connectMysqli(); 
			return $this->renderPartial('pedigree/pedigree_main',['IDpigeon'=>$IDpigeon, 'IDuser'=>$IDuser,'mysqli'=>$mysqli]);
		}
		
		return $this->render('pedigree');
	}
	
	/*
	* not really ajax function
	*/
	public function actionAjaxDelete()
	{
		if(!empty($_GET["selection"]))
		{
			if(isset($_GET["ajax_delete_pigeon"]))
			{
				//you get ID of pigeon. ID in mg_pigeon
				foreach($_GET["selection"] as $key=>$value)
				{
					$this->actionDelete($value);
				}
				
	
			}
			else if(isset($_GET["ajax_delete_mother"]))
			{
				//you get id of pigeon (ID in mg_pigeon) and search it i nmg_pigeon_list and just put IDmother=0
				foreach($_GET["selection"] as $key=>$value)
				{
					$mother=PigeonList::find()->where(['IDpigeon'=>$value, 'IDuser'=>Yii::$app->user->getId()])->one();
					if($mother)
					{
						$mother->IDmother=0;
						$mother->save();
					}
				}
			}
			else if(isset($_GET["ajax_delete_father"]))
			{
				//where IDfather is 0
				foreach($_GET["selection"] as $key=>$value)
				{
					$father=PigeonList::find()->where(['IDpigeon'=>$value, 'IDuser'=>Yii::$app->user->getId()])->one();
					if($father)
					{
						$father->IDfather=0;
						$father->save();
					}
				}
			}
			else if(isset($_GET["ajax_delete_both"]))
			{
				foreach($_GET["selection"] as $key=>$value)
				{
					$update=PigeonList::find()->where(['IDpigeon'=>$value, 'IDuser'=>Yii::$app->user->getId()])->one();
					$update->IDfather=0;
					$update->IDmother=0;
					$update->save();
				}
			}
			
			Yii::$app->session->setFlash('success', Yii::t('default','Action was successful'));
		}
		else
		{
			Yii::$app->session->setFlash('danger', Yii::t('default',"You haven't chosen any pigeon to delete"));		
		}
		$this->redirect('index');
	}

	/*
	* When users updates or creates new pigeon and wants to add existing father or mather then he selects country  and this action will filtrate all pigeons from specific country 
	* called from Pigeon::dependentDropDownMother() and Pigeon::dependentDropDownFather()
	*/
	public function actionAjaxDependentParents()
	{
		$id=$_GET['id'];
		$sex=$_GET['sex'];
		
		if($sex=='X')
			$dependentCountry=$_GET['dependentCountryFather'];
		else
			$dependentCountry=$_GET['dependentCountryMother'];
			
		// this $id is for collecting all male/female pigeons except that specific one, because he/she cannot be father/mother to itself
		// it doesn't count for actionCreate, only for actionUpdate
		$sql=Pigeon::find()->where(['IDcountry'=>(int)$dependentCountry, 'IDuser'=>Yii::$app->user->getId(), 'sex'=>$sex])->andWhere('ID NOT LIKE :id',[':id'=>$id])->orderBy('pigeonnumber ASC')->all();
		
		$return  = [];
		$i=0;
		foreach($sql as $data)
		{
			$return[$i]['id']=$data->ID;
			$return[$i]['pigeonnumber']="[$data->year/".Pigeon::getSex($data->sex)."] ".$data->pigeonnumber;
			$i++;
		}

		echo json_encode($return);
		/*
		returns:		
		[
			{"id":34,"pigeonnumber":"18534-00"}, 
			{"id":42,"pigeonnumber":"33853-07"}, 
			{"id":50,"pigeonnumber":"39067-08"}, 
			{"id":56,"pigeonnumber":"29908-08"}...
		]
		*/
	}


    /**
     * Lists all Pigeon models.
     * @return mixed
     */
    public function actionIndex()
    {	
		//show pigeons from pedigree = false
		if (!isset($_SESSION['show_frompedigree'])) 
		{
		  $_SESSION['show_frompedigree'] = false;
		} 
		else if(isset($_GET["show_frompedigree"]) && $_GET["show_frompedigree"]=="true")
		{
			$_SESSION['show_frompedigree']=true;
		}
		else if(isset($_GET["show_frompedigree"]) && $_GET["show_frompedigree"]=="false")
		{
			$_SESSION['show_frompedigree']=false;
		}
		
        $searchModel = new PigeonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pigeon model.
     * @param integer $id - IDpigeon
     * @return mixed
     */
    public function actionView($id)
    {
		$model=$this->findModel($id);
		
		//pigeon results
        $pigeonDataProvider = new ActiveDataProvider([
			'query' => PigeonData::find()->where(['IDpigeon'=>$id]),
			'sort'=>['defaultOrder'=>['year'=>SORT_DESC]],
			'pagination' => [
				'pageSize' => 30,
			],
		]);
		
		//racing partners
		$column = ($model->sex==Pigeon::MALE_PIGEON) ? "male" : "female";
        $racingPartnersDataProvider = new ActiveDataProvider([
			'query' => CoupleRacing::find()->where([$column=>$id])->with(['relationFemale.relationIDcountry', 'relationMale.relationIDcountry']),
			'sort'=>['defaultOrder'=>['year'=>SORT_DESC]],
			'pagination' => [
				'pageSize' => 20,
			],
		]);
        $breedingPartnersDataProvider = new ActiveDataProvider([
			'query' => CoupleBreeding::find()->where([$column=>$id])->with(['relationFemale.relationIDcountry', 'relationMale.relationIDcountry']),
			'sort'=>['defaultOrder'=>['year'=>SORT_DESC]],
			'pagination' => [
				'pageSize' => 20,
			],
		]);
		
		//offsprings
		$coupleRacingTable=CoupleRacing::getTableSchema();
        $offspringsRacingDataProvider = new ActiveDataProvider([
			'query' => BroodRacing::find()->joinWith(['relationIDcoupleRacing', 'relationIDcountry'])->where("$coupleRacingTable->name.male=:id OR $coupleRacingTable->name.female=:id", [":id"=>$id]),
			'sort'=>['defaultOrder'=>['hatchingdate'=>SORT_DESC]],
			'pagination' => [
				'pageSize' => 20,
			],
		]);
		$coupleBreedingTable=CoupleBreeding::getTableSchema();
        $offspringsBreedingDataProvider = new ActiveDataProvider([
			'query' => BroodBreeding::find()->joinWith(['relationIDcoupleBreeding', 'relationIDcountry'])->where("$coupleBreedingTable->name.male=:id OR $coupleBreedingTable->name.female=:id", [":id"=>$id]),
			'sort'=>['defaultOrder'=>['hatchingdate'=>SORT_DESC]],
			'pagination' => [
				'pageSize' => 20,
			],
		]);
		
		return $this->render('view', [
            'model' => $model,
			'pigeonDataProvider'=>$pigeonDataProvider,
			'racingPartnersDataProvider'=>$racingPartnersDataProvider,
			'breedingPartnersDataProvider'=>$breedingPartnersDataProvider,
			'offspringsRacingDataProvider'=>$offspringsRacingDataProvider,
			'offspringsBreedingDataProvider'=>$offspringsBreedingDataProvider,
        ]);
    }

	/*
	* helper function that calls image upload function to save pigeon image and eye image. After that is saves the model
	* I just call this function so I don't have to write the same code many times
	* $model = Pigeon, PigeonFather, PigeonMother
	* $database_field = pigeon_image, eye_image
	* $render - "update", "create"
	*/
	public function saveUploadedImageOrEye($model, $database_field, $render, $file_name, $file_tmp_name, $file_size, $file_error)
	{
		$pigeon_image=ExtraFunctions::uploadImage(
			$file_name,
			$file_tmp_name,
			$file_size, 
			$file_error,
			$model, 
			$render, 
			Pigeon::returnPathToPigeonImageEye(NULL, Yii::$app->user->getId()), 
			$database_field, 
			Pigeon::MAX_IMAGE_SIZE_PIGEON_EYE_IMAGE);
			
		if($pigeon_image["uploadOk"]==1)
		{
			$model->$database_field=$pigeon_image["FILE_NAME"];
			$model->save();
		}
	}
    /**
     * Creates a new Pigeon model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Pigeon();
        $father = new PigeonFather();
        $mother = new PigeonMother();
		
		//I don't want to put user's id in hidden field in _form so I set user id right here
		$model->IDuser = $father->IDuser = $mother->IDuser = Yii::$app->user->getId();
		$model->pigeon_image = $father->pigeon_image = $mother->pigeon_image = ExtraFunctions::NO_PICTURE;
		$model->eye_image = $father->eye_image = $mother->eye_image = ExtraFunctions::NO_EYE;

        if ($model->load(Yii::$app->request->post())) 
		{
			
			$redirect=0;

			$father->load(Yii::$app->request->post());
			$mother->load(Yii::$app->request->post());

			
			if($model->save())
				$redirect=1;
			
			//if data of main pigeon is saved($model) then save to mg_pigeon_list and redirect 
			if($redirect==1)
			{	
				$radio_father=$_POST['radio_father'];
				$radio_mother=$_POST['radio_mother'];
				
				//save in mg_pigeon
				//if isset not to add anything.
				//USER DIDN'T ADD FATHER AT ALL
				if($radio_father=="dont_add_father")
				{
					$IDfather=0;
				}
				//if isset to add new father
				//USER ADDED NEW FATHER
				else if($radio_father=="radio_new_father")
				{
					$father->save();
					
					//add father in mg_pigeon_list
					$newFather=new PigeonList;
					$newFather->IDuser=Yii::$app->user->getId();	
					$newFather->IDpigeon=$father->ID;
					$newFather->IDmother=0;
					$newFather->IDfather=0;
					$newFather->save();
					
					$IDfather=$father->ID;	


				}
				//does user want to add existing father and save variable ID of the pigeon he chose in dropdown list
				else if($radio_father=="radio_existing_father")
				{
					$IDfather=$_POST['Father_ID'];
				}
				
				
				
				//DONT ADD MOTHER
				if($radio_mother=="dont_add_mother")
				{
					$IDmother=0;
				}
				//NEW MOTHER
				else if($radio_mother=="radio_new_mother")
				{
					$mother->save();
										
					//add mother mg_pigeon_list
					$newMother=new PigeonList;
					$newMother->IDuser=Yii::$app->user->getId();	
					$newMother->IDpigeon=$mother->ID;
					$newMother->IDmother=0;
					$newMother->IDfather=0;
					$newMother->save();	
					
					$IDmother=$mother->ID;	

				}
				//EXISTING mother
				else if($radio_mother=="radio_existing_mother")
				{
					$IDmother=$_POST['Mother_ID'];
				}
				
				//add main pigeon ($model) into mg_pigeon_list
				$pigeonList=new PigeonList;
				$pigeonList->IDuser=Yii::$app->user->getId();
				$pigeonList->IDpigeon=$model->ID;
				$pigeonList->IDmother=$IDmother;
				$pigeonList->IDfather=$IDfather;
				$pigeonList->save();
				
				//UPLOAD PICTURES IF THERE ARE ANY
				//upload pigeon image and the eye
				if(isset($_FILES))
				{
					//create directory if it doesn't exist
					if (!file_exists(Pigeon::returnPathToPigeonImageEye(Yii::getAlias('@webroot'), Yii::$app->user->getId()))) 
					{
						mkdir(Pigeon::returnPathToPigeonImageEye(Yii::getAlias('@webroot'), Yii::$app->user->getId()), 0777, true);
					}
					//---------------THE PIGEON-------------------
					//pigeon image for the pigeon
					if(!empty($_FILES["pigeon_image"]["name"]))
					{
						$this->saveUploadedImageOrEye(
						$model, 
						"pigeon_image", 
						"create", 
						$_FILES["pigeon_image"]["name"], 
						$_FILES["pigeon_image"]["tmp_name"],
						$_FILES["pigeon_image"]["size"], 
						$_FILES["pigeon_image"]["error"]);
					}
					
					//the eye for the pigeon
					if(!empty($_FILES["eye_image"]["name"]))
					{
						$this->saveUploadedImageOrEye(
						$model, 
						"eye_image", 
						"create", 
						$_FILES["eye_image"]["name"], 
						$_FILES["eye_image"]["tmp_name"],
						$_FILES["eye_image"]["size"], 
						$_FILES["eye_image"]["error"]);
					}
					
					//---------------THE FATHER-------------------
					//pigeon image for the pigeon
					if(!empty($_FILES["pigeon_image_father"]["name"]))
					{
						$this->saveUploadedImageOrEye(
						$father, 
						"pigeon_image", 
						"create", 
						$_FILES["pigeon_image_father"]["name"], 
						$_FILES["pigeon_image_father"]["tmp_name"],
						$_FILES["pigeon_image_father"]["size"], 
						$_FILES["pigeon_image_father"]["error"]);
					}
					
					//the eye for the pigeon
					if(!empty($_FILES["eye_image_father"]["name"]))
					{
						$this->saveUploadedImageOrEye(
						$father, 
						"eye_image", 
						"create", 
						$_FILES["eye_image_father"]["name"], 
						$_FILES["eye_image_father"]["tmp_name"],
						$_FILES["eye_image_father"]["size"], 
						$_FILES["eye_image_father"]["error"]);
					}
					
					//---------------THE MOTHER-------------------
					//pigeon image for the pigeon
					if(!empty($_FILES["pigeon_image_mother"]["name"]))
					{
						$this->saveUploadedImageOrEye(
						$mother, 
						"pigeon_image", 
						"create", 
						$_FILES["pigeon_image_mother"]["name"], 
						$_FILES["pigeon_image_mother"]["tmp_name"],
						$_FILES["pigeon_image_mother"]["size"], 
						$_FILES["pigeon_image_mother"]["error"]);
					}
					
					//the eye for the pigeon
					if(!empty($_FILES["eye_image_mother"]["name"]))
					{
						$this->saveUploadedImageOrEye(
						$mother, 
						"eye_image", 
						"create", 
						$_FILES["eye_image_mother"]["name"], 
						$_FILES["eye_image_mother"]["tmp_name"],
						$_FILES["eye_image_mother"]["size"], 
						$_FILES["eye_image_mother"]["error"]);
					}
				}//end image upload
				
				return $this->redirect(['view', 'id' => $model->ID]);
			}
            
        } 
		else 
		{
            return $this->render('create', [
                'model' => $model,
                'father' => $father,
                'mother' => $mother,
            ]);
        }
    }

    /**
     * Updates an existing Pigeon model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);


        if ($model->load(Yii::$app->request->post()) && $model->save()) 
		{
			//upload pigeon image and the eye
			if(isset($_FILES["pigeon_image"]) || isset($_FILES["eye_image"]))
			{
				if (!file_exists(Pigeon::returnPathToPigeonImageEye(Yii::getAlias('@webroot'), Yii::$app->user->getId()))) 
				{
					mkdir(Pigeon::returnPathToPigeonImageEye(Yii::getAlias('@webroot'), Yii::$app->user->getId()), 0777, true);
				}
				
					//pigeon image for the pigeon
					if(!empty($_FILES["pigeon_image"]["name"]))
					{
						$this->saveUploadedImageOrEye(
						$model, 
						"pigeon_image", 
						"update", 
						$_FILES["pigeon_image"]["name"], 
						$_FILES["pigeon_image"]["tmp_name"],
						$_FILES["pigeon_image"]["size"], 
						$_FILES["pigeon_image"]["error"]);
					}
					
					//the eye for the pigeon
					if(!empty($_FILES["eye_image"]["name"]))
					{
						$this->saveUploadedImageOrEye(
						$model, 
						"eye_image", 
						"update", 
						$_FILES["eye_image"]["name"], 
						$_FILES["eye_image"]["tmp_name"],
						$_FILES["eye_image"]["size"], 
						$_FILES["eye_image"]["error"]);
					}
			}// end of image upload
			

			//load model from pigeon_list
			$pigeonList=PigeonList::find()->where(['IDpigeon'=>$model->ID])->one();
			//if user chose father
			if(isset($_POST['addfather']))
			{
				$pigeonList->IDfather=$_POST['Father_ID'];
				$pigeonList->save();
			}
			
			if(isset($_POST['addmother']))
			{
				$pigeonList->IDmother=$_POST['Mother_ID'];
				$pigeonList->save();
			}

            return $this->redirect(['view', 'id' => $model->ID]);
        } 
		else 
		{
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Pigeon model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$delete=1;
		//PRVO PROVJERI JEL TAJ GOLUB NEKOME OTAC ILI MAJKA TAKO ŠTO ĆEŠ PRIJEĆI CIJELI POPIS GOLUBOVA I TADA PROVJERITI JEL IDmother ILI IDfather JEDNAK $id, jer on predstavlja ID tog goluba kojeg se želi izbrisati
		//AKO JE ZNAČI DA JE ON NEKOME OTAC ILI MAJKA I PREKINI RADNJU
		$parent=PigeonList::find()->where('IDuser=:id1 AND (IDmother=:id2 OR IDfather=:id2)', [':id1'=>Yii::$app->user->getId(), ':id2'=>$id])->count();
		if($parent>=1)
			$delete=0;
					
		if($delete==1)
		{
			try
			{
				$this->findModel($id)->delete();
			}
			catch(IntegrityException $e)
			{
				throw new HttpException(403, Yii::t('default', 'DELETE_GOLUB'));
			}
		}
		else
			throw new HttpException(403, Yii::t('default', 'DELETE_GOLUB'));



        return $this->redirect(['index']);
    }

    /**
     * Finds the Pigeon model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pigeon the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pigeon::find()->where(['ID'=>$id, 'IDuser'=>Yii::$app->user->getId()])->with(['relationIDstatus'])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
