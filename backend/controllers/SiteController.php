<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;

use dektrium\user\models\User;
use backend\helpers\ExtraFunctions;
use backend\models\Breeder;
use yii\helpers\Json;
use yii\data\ActiveDataProvider;
use backend\modules\club\models\Club;

/**
 * Site controller
 */
class SiteController extends Controller
{
	
	/*
	* execute this code before everything else
	*/	
	public function beforeAction($action)
	{
		$x = new ExtraFunctions;
		$x->beforeActionTimeLanguage();
		if(!Yii::$app->user->isGuest)
			$x->beforeActionBreederData();
		return parent::beforeAction($action);
	}

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'captcha', 'what-is-pippion', 'auth-connect', 'server-time', 'radio', 'tutorials', 'about'],
                        'allow' => true,
						'roles'=>['@','?']
                    ],
                    [
                        'actions' => ['user-list', 'yii2-user-migrate'],
                        'allow' => true,
                        'roles' => ['@'],
						'matchCallback' => function ($rule, $action) { //http://www.yiiframework.com/wiki/771/rbac-super-simple-with-admin-and-user/
                      		return Yii::$app->user->identity->getIsAdmin();
                  		 }
                    ],
                    [
                        'actions' => ['verify-acc', 'logout', 'contact', 'suggest-user', 'privacy-police'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => 
			[
                'class' => 'yii\web\ErrorAction',
            ],
			'captcha' => 
			[
                'class' => 'yii\captcha\CaptchaAction',
            ],
        ];
    }

	/*
	* about pippion
	*/
	public function actionAbout()
	{
		return $this->render("about");
	}
	
	public function actionUserList()
	{
		$userEmail=User::find()->all();
		$dataProvider = new ActiveDataProvider([
			'query' => Breeder::find(),
			'pagination' => [
				'pageSize' => 100,
			],
		]);
		
	}
	
	/*
	* Show YouTube Tutorials
	*/
	public function actionTutorials()
	{
        return $this->render('tutorials');
	}
	
	/*
	* Pigeon Radio Australia
	*/
    public function actionRadio()
    {
        return $this->render('radio');
    }
	
	
	public function actionPrivacyPolice()
	{
		return $this->render('privacy-police');
	}
	
	/*
	* return current (UTC) time on Pippion
	* used for auction countdown
	*/
	public function actionServerTime()
	{
		$now = new \DateTime(); 
		echo $now->format("M j, Y H:i:s O")."\n"; 
	}

	/*
	* Suggest user for token unput (pippion-js.php)
	* while user is typing in search field, this action searches database and suggest matched user
	*/	
	public function actionSuggestUser()
	{
		//Build LIKE condition - http://www.bsourcecode.com/yiiframework2/select-query-model/#LIKE-Condition
		if(isset($_GET["q"]))
		{ 
			$array=array();
			//http://www.yiiframework.com/doc-2.0/yii-db-query.html#where()-detail
			$user = User::find()->where(['LIKE', 'username', $_GET["q"]])->all();
			//ILI User::find()->where('username LIKE :query')->addParams([':query'=>'%'.$_GET["q"].'%'])->all()
			foreach($user as $key=>$value)
			{
				 array_push($array, array('id'=>$value->id, 'name'=>$value->username));
			}

			echo Json::encode($array);
		}
	}
	
	/*
	* if users tries to register via F or G on login page it will redirect him here
	* User has to register first and then connect account with F or G and then he can login via F or G
	*/
	public function actionAuthConnect()
	{
		\Yii::$app->view->theme = new \yii\base\Theme([
			'pathMap'=> ['@backend/views'=>'@backend/themes/login/views'],
		]);
		return $this->render('authconnect');
	}
	

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		
		if(isset($_POST['submit']))
		{
			$Breeder = new Breeder;
			$user=$Breeder->findUserById(Yii::$app->user->getId());
			Yii::$app->getSession()->setFlash('info', Yii::t('default', 'Thanks for contacting'));
			/*Yii::$app->mailer->compose()
				->setFrom([$_POST["email"]=>$_POST["name"]])
				->setTo([Yii::$app->params['adminEmail2']=>'Contact Pippion', Yii::$app->params['adminEmail']=>'Contact Pippion'])
				->setSubject($_POST["subject"])
				->setHtmlBody($_POST["message"])
				->send();*/
			require Yii::getAlias('@common').'/phpmailer/PHPMailerAutoload.php';

			//Create a new PHPMailer instance
			$mail = new \PHPMailer;
			$mail->CharSet = 'UTF-8';
			//Set who the message is to be sent from
			$mail->setFrom($user->email, $user->username);
			//Set who the message is to be sent to
			$mail->addAddress(Yii::$app->params['adminEmail'], 'Contact Pippion');
			$mail->addAddress(Yii::$app->params['adminEmail2'], 'Contact Pippion');
			//Set the subject line
			$mail->Subject = $_POST['subject'];
			//Read an HTML message body from an external file, convert referenced images to embedded,
			//convert HTML into a basic plain-text alternative body
			$mail->msgHTML($_POST['message']);
			$mail->send();

		}
		return $this->render('contact');
	}

	/*
	* Landing page
	*/
	public function actionWhatIsPippion()
	{
		//since ajax doesn't recognize submit button as post, check if required fields are set
		if(isset($_POST["name"]) && isset($_POST["email"]))
		{
			/*Yii::$app->mailer->compose(null, array())
				->setFrom(array($_POST['email'] => $_POST['name']))
				->setTo(array(Yii::$app->params['adminEmail'] => 'Landing Page Contact',Yii::$app->params['adminEmail2'] => 'Landing Page Contact'))
				->setSubject($_POST['subject'])
				->setHtmlBody($_POST['message'])
				->send();*/
			ExtraFunctions::sendEmail($_POST['name'], $_POST['email'], $_POST['subject'], $_POST['message'], true);
		}

		/*---------------GET LATEST POST FROM MY BLOG----------------------*/
		//http://davidwalsh.name/wordpress-recent-posts
		// Include the wp-load'er
		include(Yii::getAlias('@webroot').'/pigeonblog/wp-load.php');
		
		// Get the last 10 posts
		// Returns posts as arrays instead of get_posts' objects
		$recent_posts = wp_get_recent_posts(array(
			'numberposts' => 1,
			'order' => 'DESC',
			'post_status' => 'publish',
		));
		/*---------------GET LATEST POST FROM MY BLOG----------------------*/
		
		/*---------------GET RANDOM CLUBS----------------------*/
		$clubTable=Club::getTableSchema();
		$db = new yii\db\Connection();
		// return a set of rows. each row is an associative array of column names and values.
		// an empty array is returned if no results
		$clubs = (new \yii\db\Query())
		->from($clubTable->name)
		->where("club!='index'")
		->orderBy('RAND()')
		->limit(3)
		->all();
		/*---------------GET RANDOM CLUBS----------------------*/


		return $this->renderPartial('whatispippion', ['recent_posts'=>$recent_posts, 'clubs'=>$clubs]);
	}
	
	
	
	/*
	* verify your own account by sending image of yourself with your name written on peace of paper
	* or verify by sending your ID
	*/
	public function actionVerifyAcc()
	{

		if(isset($_POST["submit"]))
		{
			$Breeder = new Breeder;
			
			$FILE_NAME = $_FILES["file"]["name"];
			$FILE_TYPE = $_FILES["file"]["type"];
			$FILE_SIZE = $_FILES["file"]["size"];
			$FILE_TMP_NAME = $_FILES["file"]["tmp_name"];
			
			$directory=$_SERVER['DOCUMENT_ROOT']."/temp/";
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			$temp = explode(".", $FILE_NAME);
			$extension = end($temp);
			
			if($FILE_SIZE > 2097152)
			{
				$warning = Yii::t('default',"Invalid file");
				\Yii::$app->getSession()->setFlash('warning', $warning);
				return $this->render('verifyaccount');
			}
			else if ((($FILE_TYPE == "image/gif") || ($FILE_TYPE == "image/jpeg") || ($FILE_TYPE == "image/jpg")  || ($FILE_TYPE == "image/pjpeg") || ($FILE_TYPE == "image/x-png") || ($FILE_TYPE == "image/png"))  && in_array($extension, $allowedExts)) 
			{
			  if ($_FILES["file"]["error"] > 0) 
			  {
					$warning = "Return Code: " . $_FILES["file"]["error"] . "<br>";
					\Yii::$app->getSession()->setFlash('warning', $warning);
					return $this->render('verifyaccount');
			  } 
			  else 
			  {
				/*echo "Upload: " . $_FILES["file"]["name"] . "<br>";
				echo "Type: " . $_FILES["file"]["type"] . "<br>";
				echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
				echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";*/
				if (file_exists($directory.$FILE_NAME)) 
				{
				  $FILE_NAME = mt_rand().".".$extension;
				} 
				move_uploaded_file($FILE_TMP_NAME, $directory.$FILE_NAME);
				$breeder=Breeder::find()->where(['IDuser'=>Yii::$app->user->getId()])->one();
				$user=$Breeder->findUserById(Yii::$app->user->getId());
				$email=(!empty($breeder->email1)) ? $breeder->email1 : $breeder->email2;
				$body=
				"
				<b>User:</b> $user->username ($user->id)<br>
				<b>Breeder:</b> $breeder->name_of_breeder<br>
				<b>Registration email:</b> $user->email<br>
				";
				
				/*Yii::$app->mailer->compose(null, array())
				->setFrom(array($email => $breeder->name_of_breeder))
				->setTo(array(Yii::$app->params['adminEmail'] => 'Acc Verify',Yii::$app->params['adminEmail2'] => 'Acc Verify'))
				->setSubject('Account verification')
				->setHtmlBody($body)
				->attach($directory.$FILE_NAME)
				->send();*/
				
				require Yii::getAlias('@common').'/phpmailer/PHPMailerAutoload.php';
				
				//Create a new PHPMailer instance
				$mail = new \PHPMailer();
				$mail->CharSet = 'UTF-8';
				//Set who the message is to be sent from
				$mail->setFrom($email, $breeder->name_of_breeder);
				//Set who the message is to be sent to
				$mail->addAddress(Yii::$app->params['adminEmail'], 'Acc Verify');
				$mail->addAddress(Yii::$app->params['adminEmail2'], 'Acc Verify');
				//Set the subject line
				$mail->Subject = 'Account verification';
				//Read an HTML message body from an external file, convert referenced images to embedded,
				//convert HTML into a basic plain-text alternative body
				$mail->msgHTML($body);
				//Attach an image file
				$mail->addAttachment($directory.$FILE_NAME);
				$mail->send();
				/*if (file_exists($directory.$FILE_NAME) )
				{
					unlink($directory.$FILE_NAME);
				} */
				$message=Yii::t('default', 'Verification message has been sent');
				\Yii::$app->getSession()->setFlash('success', $message);
				return $this->render('verifyaccount');
			  }
			} 
			else 
			{
				$warning = Yii::t('default',"Invalid file");
				\Yii::$app->getSession()->setFlash('warning', $warning);
				return $this->render('verifyaccount');
			}
		}
		
		return $this->render('verifyaccount');
	}
	

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
	
	/*
	* this is used for showing errors
	*/
	public function actionError()
	{
		$exception = Yii::$app->errorHandler->exception;
		if ($exception !== null) {
			return $this->render('error', ['exception' => $exception]);
		}
	}
	
	/* 
	* yii2 user migration
	*/
	public function actionYii2UserMigrate()
	{

		// https://github.com/yiisoft/yii2/issues/1764#issuecomment-42436905
		$oldApp = \Yii::$app;
		new \yii\console\Application([
			'id'            => 'Command runner',
			'basePath'      => '@app',
			'components'    => [
				'db' => $oldApp->db,
			],
		]);
		\Yii::$app->runAction('migrate/up', ['migrationPath' => '@vendor/dektrium/yii2-user/migrations', 'interactive' => false]);
		\Yii::$app = $oldApp;
	
	}
}
