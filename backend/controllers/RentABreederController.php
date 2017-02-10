<?php

namespace backend\controllers;

use Yii;
use backend\helpers\ExtraFunctions;
use backend\models\CountryList;
use backend\models\Currency;
use backend\models\RentABreeder;
use backend\models\search\RentABreederSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * RentABreederController implements the CRUD actions for RentABreeder model.
 */
class RentABreederController extends Controller
{
	/*
	* execute this code before everything else
	*/
	public function beforeAction($action)
	{
		$action_tmp=Yii::$app->controller->action->id;
		$x = new ExtraFunctions;
		$x->beforeActionTimeLanguage();
		if($action_tmp=="create")
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
                        'actions' => ['create', 'delete', 'update'],
                        'roles' => ['@']
                    ],
                  /*  [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['@'],
						'matchCallback'=>function($rule, $action) ////http://www.yiiframework.com/wiki/771/rbac-super-simple-with-admin-and-user/
						{
							return Yii::$app->user->identity->getIsAdmin();
						}
                    ],*/
                    [
                        'allow' => true,
                        'actions' => ['index', 'rent-me-request'],
                        'roles' => ['@', '?']
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
	* ajax call this function to send email when someone wants to rent a breeder
	*/
	public function actionRentMeRequest()
	{
		$country=CountryList::find()->where(['ID'=>$_POST["rent_me_country"]])->one();
		$currency=Currency::find()->where(['ID'=>$_POST["rent_me_currency"]])->one();
		
		$sender_name=$_POST["name_surname"]; 
		$sender_email=$_POST["rent_me_email"]; 
		$subject="[REQUEST] Rent A Breeder"; 
		$message="<strong>ID: </strong>".$_POST["ID"];
		$message.="<br><strong>Name: </strong>".$_POST["name_surname"]; 
		$message.="<br><strong>Country: </strong>".$country->country_name; 
		$message.="<br><strong>Price: </strong>".$_POST["rent_me_price"]." ".$currency->currency." per year"; 
		$message.="<br><strong>Email: </strong>".$_POST["rent_me_email"]; 
		$message.="<br><strong>Extra info: </strong>".$_POST["rent_me_extra_info"]; 
		$both=true;
		ExtraFunctions::sendEmail($sender_name, $sender_email, $subject, $message, $both);
		
		echo json_encode(["success"=>"true"]);

	}
	
    /**
     * Lists all RentABreeder models.
     * @return mixed
	 * 
     */
    public function actionIndex()
    {
        $searchModel = new RentABreederSearch();
		
		//if isset GET[admin] search for non active ads, else search for active ads
		if(isset($_GET["admin"]) && $_GET["admin"]=="true")
			$active=0;
		else
			$active=1;
			
		//if isset GET[mine] search only ads from currently logged users
		if(isset($_GET["mine"]) && $_GET["mine"]=="true")
			$IDuser=Yii::$app->user->getId();
		else
			$IDuser=NULL;
			
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $active, $IDuser);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RentABreeder model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new RentABreeder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RentABreeder();
		$model->scenario="create";

        if ($model->load(Yii::$app->request->post())) 
		{
			$tmp_dir=RentABreeder::rentABreederImageDir(Yii::getAlias("@webroot"));
			if(!file_exists($tmp_dir))
			{
				mkdir($tmp_dir, 0755, true);//true - create whole path to that directory
			}

			$file=ExtraFunctions::uploadImage
			(
				$_FILES["RentABreeder"]["name"]["breeder_picture"],
				$_FILES["RentABreeder"]["tmp_name"]["breeder_picture"],
				$_FILES["RentABreeder"]["size"]["breeder_picture"], 
				$_FILES["RentABreeder"]["error"]["breeder_picture"], 
				$model, 
				"create", 
				RentABreeder::rentABreederImageDir(Yii::getAlias("@web")), 
				"breeder_picture", 
				RentABreeder::IMAGE_SIZE
			);
			
			if($file["uploadOk"]==1)
			{
				$model->breeder_picture=$file["FILE_NAME"];
				$model->IDuser=Yii::$app->user->getId();
				$model->save();
				
				$sender_name="Pippion"; 
				$sender_email="noreply@pippion.com"; 
				$subject="[NEW] Rent a Breeder"; 
				$message='
				There was new rent a breeder added to the database. 
				<br><a href="http://www.pippion.com/rent-a-breeder/index?admin=true">http://www.pippion.com/rent-a-breeder/index?admin=true</a>
				<br><a href="http://www.pippion.com/rent-a-breeder/update?id='.$model->ID.'">http://www.pippion.com/rent-a-breeder/update?id='.$model->ID.'</a>
				'; 
				$both=true;
				ExtraFunctions::sendEmail($sender_name, $sender_email, $subject, $message, $both);
				Yii::$app->session->setFlash('success', Yii::t('default', 'You have successfully created rent a breeder'));
				return $this->redirect(['index']);
			}
			else 
			{
				return $this->render('create', [
					'model' => $model,
				]);
			}
        } 
		else 
		{
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing RentABreeder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		if(in_array(Yii::$app->user->getId(), Yii::$app->params['adminId']))
       		$model = $this->findAdminModel($id);
		else
			$model = $this->findModel($id);
		$model->scenario="update";
		
        if ($model->load(Yii::$app->request->post())) 
		{
			//if user uploaded new picture
			if(isset($_FILES["RentABreeder"]["name"]["breeder_picture"]) && !empty($_FILES["RentABreeder"]["name"]["breeder_picture"]))
			{
				$file=ExtraFunctions::uploadImage
				(
					$_FILES["RentABreeder"]["name"]["breeder_picture"],
					$_FILES["RentABreeder"]["tmp_name"]["breeder_picture"],
					$_FILES["RentABreeder"]["size"]["breeder_picture"], 
					$_FILES["RentABreeder"]["error"]["breeder_picture"], 
					$model, 
					"update", 
					RentABreeder::rentABreederImageDir(Yii::getAlias("@web")), 
					"breeder_picture", 
					RentABreeder::IMAGE_SIZE
				);
				
				if($file["uploadOk"]==1)
				{
					$model->breeder_picture=$file["FILE_NAME"];
				}
			}
			
			//if admin didn't update it, make it inactive
			if(!in_array(Yii::$app->user->getId(), Yii::$app->params['adminId']))
			{
				$sender_name="Pippion"; 
				$sender_email="noreply@pippion.com"; 
				$subject="[UPDATED] Rent a Breeder"; 
				$message='
				There was new rent a breeder added to the database. 
				<br><a href="http://www.pippion.com/rent-a-breeder/index?admin=true">http://www.pippion.com/rent-a-breeder/index?admin=true</a>
				<br><a href="http://www.pippion.com/rent-a-breeder/update?id='.$model->ID.'">http://www.pippion.com/rent-a-breeder/update?id='.$model->ID.'</a>
				'; 
				$both=true;
				ExtraFunctions::sendEmail($sender_name, $sender_email, $subject, $message, $both);
				Yii::$app->session->setFlash('success', Yii::t('default', 'You have successfully created rent a breeder'));
				
				$model->active=0;
			}
			$model->save();
			return $this->redirect(['index']);

        } 
		else 
		{
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing RentABreeder model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		if(in_array(Yii::$app->user->getId(), Yii::$app->params['adminId']))
       		$model = $this->findAdminModel($id);
		else
			$model = $this->findModel($id);
		if($model->delete())
		{
			unlink(RentABreeder::rentABreederImageDir(Yii::getAlias('@webroot')).$model->breeder_picture);
		}

        return $this->redirect(['index']);
    }

    /**
     * Finds the RentABreeder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RentABreeder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findAdminModel($id)
    {
        if (($model = RentABreeder::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
    protected function findModel($id)
    {
        if (($model = RentABreeder::find()->where(['IDuser'=>Yii::$app->user->getId(), 'ID'=>$id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
