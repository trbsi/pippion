<?php

namespace backend\controllers;

use Yii;
use backend\models\Pigeon;
use backend\models\Auction;
use backend\models\AuctionPigeon;
use backend\models\AuctionImage;
use backend\models\AuctionRating;
use backend\models\AuctionBid;
use backend\models\Breeder;
use backend\models\PaymentGateway;
use backend\models\AccountBalance;
use backend\models\search\AuctionSearch;

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

use PayPal\Service\AdaptivePaymentsService;
use PayPal\Types\AP\Receiver;
use PayPal\Types\AP\ReceiverList;
use PayPal\Types\AP\PayRequest;
use PayPal\Types\Common\RequestEnvelope;

use PayPal\Types\AP\ExecutePaymentRequest;

/**
 * AuctionController implements the CRUD actions for Auction model.
 */
class AuctionController extends Controller
{
	/*
	* execute this code before everything else
	*/
	public function beforeAction($action)
	{
		$action_tmp=Yii::$app->controller->action->id;
		$x = new ExtraFunctions;
		$x->beforeActionTimeLanguage();
		$x->beforeActionBreederData();
		if(!Yii::$app->user->isGuest && ($action_tmp=="bid" || $action_tmp=="create"))
		{
			$x->isBreederVerified(Yii::t('default', 'Auctions'));
			PaymentGateway::userPaymentGateway();
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
                        'actions' => ['update', 'delete', 'create', 'auctions-by-user', 'bid', 'all-bids', 'pigeon-arrived', 'pay'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view', 'index', 'opened', 'closed', 'rules', 'test'],
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
public function actionTest()
{
	$t=(new \PayPal\Types\Common\RequestEnvelope("en_US"));
	var_dump($t);
	}
	/*
	* when buyer wins on auction, he is redirected here to buy that pigeon, transfer money to our account
	*/
	public function actionPay()
	{
		/*
		 * Use the Pay API operation to transfer funds from a sender’s PayPal account to one or more receivers’ PayPal accounts. You can use the Pay API operation to make simple payments, chained payments, or parallel payments; these payments can be explicitly approved, preapproved, or implicitly approved.
		
		 Use the Pay API operation to transfer funds from a sender's PayPal account to one or more receivers' PayPal accounts. You can use the Pay API operation to make simple payments, chained payments, or parallel payments; these payments can be explicitly approved, preapproved, or implicitly approved. 
		
		 A chained payment is a payment from a sender that is indirectly split among multiple receivers. It is an extension of a typical payment from a sender to a receiver, in which a receiver, known as the primary receiver, passes part of the payment to other receivers, who are called secondary receivers
		 
		 * Create your PayRequest message by setting the common fields. If you want more than a simple payment, add fields for the specific kind of request, which include parallel payments, chained payments, implicit payments, and preapproved payments.
		 */
		require_once(Yii::getAlias('@common').'/paypal/adaptivepayments-sdk-php/autoload.php');
		
		if(isset($_POST['idauction'])) 
		{
			$IDauction=(int)$_POST["idauction"];
			$receiver = array();
			
			//get the latest bid of current auction
			$latestBid=AuctionBid::findLatestBid($IDauction);
			//get Pippion commission
			$commission=Auction::calculateCommission($latestBid->price);
			//price to pay seller
			//$toSeller=$latestBid->price-$commission;
			//get seller's paypal account. IDuser is the person who started the auction, seller.
			$seller_paypal_account=PaymentGateway::find()->where(['gateway'=>'paypal', 'IDuser'=>$latestBid->relationIDauction->IDuser])->one();
			//get auction currency
			$currency=$latestBid->relationIDauction->relationIDcurrency->currency;
			
			if(Auction::PAYPAL_SANDBOX==true)
			{
				$paypal_url="https://www.sandbox.paypal.com/webscr&cmd=_ap-payment&paykey=";
			}
			else
			{
				$paypal_url="https://www.paypal.com/webscr&cmd=_ap-payment&paykey=";
			}
			$ipn_url=\Yii::$app->params['pippion_site']."ipn/delay-payments-paypal-auction?IDauction=$IDauction";

			$i=0;
			$receiver[$i] = new Receiver();
			//send to me
			$receiver[$i]->email = \Yii::$app->params['paypalEmail'];
			//Amount to be credited to the receiver's account 
			//total from auction, last bid
			$receiver[$i]->amount = $commission;
			//Set to true to indicate a chained payment; only one receiver can be a primary receiver. Omit this field, or set it to false for simple and parallel payments.
			$receiver[$i]->primary = false;
			
			$i=1;
			$receiver[$i] = new Receiver();
			//seller's account
			$receiver[$i]->email = $seller_paypal_account->pay_email;
			//Amount to be credited to the receiver's account 
			//total from auction, last bid
			$receiver[$i]->amount = $latestBid->price;
			//Set to true to indicate a chained payment; only one receiver can be a primary receiver. Omit this field, or set it to false for simple and parallel payments.
			$receiver[$i]->primary = true;
			
			
			$receiverList = new ReceiverList($receiver);
		
		
			/*
			 * The action for this request. Possible values are:
			
				PAY – Use this option if you are not using the Pay request in combination with ExecutePayment.
				CREATE – Use this option to set up the payment instructions with SetPaymentOptions and then execute the payment at a later time with the ExecutePayment.
				PAY_PRIMARY – For chained payments only, specify this value to delay payments to the secondary receivers; only the payment to the primary receiver is processed.
			
			 */
			/*
			 * The code for the currency in which the payment is made; you can specify only one currency, regardless of the number of receivers 
			 */
			/*
			 * URL to redirect the sender's browser to after canceling the approval for a payment; it is always required but only used for payments that require approval (explicit payments) 
			 */
			/*
			 * URL to redirect the sender's browser to after the sender has logged into PayPal and approved a payment; it is always required but only used if a payment requires explicit approval 
			 */
			 $actionType="PAY";
			 $cancelUrl=\Yii::$app->params['pippion_site']."auction/view?id=$IDauction";
			 $returnUrl=\Yii::$app->params['pippion_site']."ipn/return-url-paypal-auction?IDauction=$IDauction";
			$payRequest = new PayRequest(new RequestEnvelope("en_US"), $actionType, $cancelUrl, $currency, $receiverList, $returnUrl);
			$payRequest->ipnNotificationUrl = $ipn_url;
			// Add optional params
			
			if(isset($_POST["memo"]) && $_POST["memo"] != "") {
				$payRequest->memo = $_POST["memo"];
			}
			
			
			/*
			 * 	 ## Creating service wrapper object
			Creating service wrapper object to make API call and loading
			Configuration::getAcctAndConfig() returns array that contains credential and config parameters
			 */
			$Configuration = new \Configuration; 
			$service = new AdaptivePaymentsService($Configuration->getAcctAndConfig());
			try {
				/* wrap API method calls on the service object with a try catch */
				$response = $service->Pay($payRequest);
				$payKey = $response->payKey;
				$payPalURL = $paypal_url.$payKey;

				if(empty($payKey))
				{
					return $this->render('error', ['errormsg'=>Yii::t('default', 'Cant process paykey')]);
				}
				else
				{
					\Yii::$app->response->redirect($payPalURL, 301)->send();
					exit;
				}
			} 
			catch(Exception $ex) 
			{
				echo "Error";
				exit;
			}
			/* Make the call to PayPal to get the Pay token
			 If the API call succeded, then redirect the buyer to PayPal
			to begin to authorize payment.  If an error occured, show the
			resulting errors */
		}
	}

	/*
	* auctions rules
	*/
	public function actionRules()
	{
		return $this->render('rules');
		
	}

	/*
	* When buyer receives pigeons and clicks on "Pigeon has arrived" in auction page the money will be sent to the seller
	* $id - ID in Auction, IDauction
	*/
	/*public function actionPigeonArrived($id)
	{
		//when buyer pays for a pigeon, the money goes to our account
		$AB=AccountBalance::find()->where(['IDauction'=>$id])->one();
		$payKey=$AB->payKey;
		
		//find latest bid
		$latestBid=AuctionBid::findLatestBid($id);

		$url_to_auction=\Yii::$app->params['pippion_site']."auction/view?id=".$id;
		$url_to_rules=\Yii::$app->params['pippion_site']."auction/rules";
		
		//send me an email
		$url_to_transfer_money=\Yii::$app->params['pippion_site']."account-balance/transfer-money-to-seller?id=".$id;
		$message='Korisnik je potvrdio da je dobio goluba. 
		<br>Aukcija: <a href="'.$url_to_auction.'">'.$url_to_auction.'</a>
		<br>Transfer money to the seller: <a href="'.$url_to_transfer_money.'">'.$url_to_transfer_money.'</a>';
		ExtraFunctions::sendEmail("Dario T.", "no-reply@pippion.com", "[Auction] Pigeon arrived", $message, true);
		
		$seller=Breeder::getUserEmailAndUsername($AB->relationIDauction->IDuser);
		$buyer=Breeder::getUserEmailAndUsername($AB->relationIDauction->relationAuctionRating->IDwinner);
		
		//send email to the seller
		$send_to_email=$seller['email'];
		$send_to_name=$seller['username'];
		$subject=Yii::t('default', 'Pigeon arrived email subject');
		$message=Yii::t('default', 'Pigeon arrived email message to seller', ['0'=>$seller['username'], '1'=>$buyer['username'], '2'=>$url_to_auction, '3'=>$url_to_rules]);
		ExtraFunctions::sendEmailToSomeone($send_to_email, $send_to_name, $subject, $message, $loadAutoLoader=false);
		
		//send email to the buyer
		$send_to_email=$buyer['email'];
		$send_to_name=$buyer['username'];
		$subject=Yii::t('default', 'Pigeon arrived email subject');
		$message=Yii::t('default', 'Pigeon arrived email message to buyer', ['0'=>$buyer['username'], '1'=>$seller['username'], '2'=>$url_to_auction]);
		ExtraFunctions::sendEmailToSomeone($send_to_email, $send_to_name, $subject, $message, $loadAutoLoader=false);

		require_once(Yii::getAlias('@common').'/paypal/adaptivepayments-sdk-php/PPBootStrap.php');

		/*
		 * The ExecutePayment API operation lets you execute a payment set up with the Pay API operation with the actionType CREATE. To pay receivers identified in the Pay call, set the pay key from the PayResponse message in the ExecutePaymentRequest message.
		
		The ExecutePayment API operation lets you execute a payment set up with the Pay API operation with the actionType CREATE. To pay receivers identified in the Pay call, set the pay key from the PayResponse message in the ExecutePaymentRequest message. 
		 */
		
		/*
		 * (Optional) The pay key that identifies the payment to be executed. This is the pay key returned in the PayResponse message. 
		 *
		$executePaymentRequest = new ExecutePaymentRequest(new RequestEnvelope("en_US"),$payKey);
		$executePaymentRequest->actionType = "PAY";
		/*
		 * The ID of the funding plan from which to make this payment.
		 *
		if(isset($_POST["fundingPlanID"]) && $_POST["fundingPlanID"] != "") {
			$executePaymentRequest->fundingPlanId = $_POST["fundingPlanID"];
		}
		/*
		 * 	 ## Creating service wrapper object
		Creating service wrapper object to make API call and loading
		Configuration::getAcctAndConfig() returns array that contains credential and config parameters
		 *
		 $Configuration = new \Configuration; // nalazi se unutar PPBootStrap.php
		$service = new AdaptivePaymentsService($Configuration->getAcctAndConfig());
		try 
		{
			//wrap API method calls on the service object with a try catch 
			
			$response = $service->ExecutePayment($executePaymentRequest);
			$ack = strtoupper($response->responseEnvelope->ack);
			if($ack == "SUCCESS")
			{
				$AB->pigeon_arrived=1;
				$AB->money_transferred=1;
				$AB->save();
		
				//send email to seller
				$seller=Breeder::getUserEmailAndUsername($latestBid->relationIDauction->IDuser);
				$send_to_email=$seller['email'];
				$send_to_name=$seller['username'];
				$subject=Yii::t('default', 'Money transferred');
				$message=Yii::t('default', 'Money transferred to seller paypal', ['0'=>$seller['username'], '1'=>\Yii::$app->params['pippion_site']."auction/view?id=$id"]);
				ExtraFunctions::sendEmailToSomeone($send_to_email, $send_to_name, $subject, $message, $loadAutoLoader=false);
				
				//send email to me that seller got the money
				$subject="[Auction] Money transferred to seller";
				ExtraFunctions::sendEmail("Dario T.", "no-reply@pippion.com", $subject, $message, $both=true, $loadAutoLoader=false);
			} 
			else
			 {
				 //send email to me that something went wront
				$subject="Money transfer error";
				$message="User confirmed that pigeon arrived but there was an error with transferring money to seller's account";
				ExtraFunctions::sendEmail("Dario T.", "no-reply@pippion.com", $subject, $message, $both=true, $loadAutoLoader=false);
				//show error
				return $this->render('pigeon-arrived', ['id'=>$id, 'error'=>true]);

			}

		} 
		catch(Exception $ex) 
		{
			echo ' ERROR';
			exit;
		}
		
		return $this->render('pigeon-arrived', ['id'=>$id, 'error'=>false]);
	}*/
	
	/*
	* vrati sve bidove koji su korisnici ponudili
	* @param $id, ID aukcije, (ID u mg_auction)
	* @return sve bidove
	*/
	public function actionAllBids($id)
	{
		//provjeri jel pokretač aukcije pregledava sve ponude, onda dopusti, ako netko drugi onda nemoj dopustit
		$allow=Auction::findOne($id);
		if($allow->IDuser!=Yii::$app->user->getId())
			throw new HttpException(404,Yii::t('default', 'You cant view who bid on this auction') );
		
		$query=AuctionBid::find();
		$query->where(['IDauction'=>$id]);
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort'=>['defaultOrder'=> ['ID'=>SORT_DESC]],
			'pagination' => [
				'pageSize' => 50,
			],
		]);
		
		return $this->render('allbids',array(
			'dataProvider'=>$dataProvider,
			'IDauction'=>$id,
		));

	}


	/*
	* kada korisnik otvori neku aukciju i želi bidat, stisne gumb Bid onda ga odvede tu da procesira
	*/
	public function actionBid()
	{
		$model=new AuctionBid;
		
		if(isset($_POST['AuctionBid']))
		{
			$model->attributes=$_POST['AuctionBid'];
			$model->IDbidder=Yii::$app->user->getId();
			if($model->save())
			{
				//ako sačuva bid tada promijeni IDwinner u mg_auction_rating u ID korisnika koji je trenutno bidao
				//da lakše odredim tko je winner i tko treba ostaviti feedback
				$auctionRating=$this->loadAuctionRatingModel($model->IDauction);
				$auctionRating->IDwinner=$model->IDbidder;
				$auctionRating->save();

				Yii::$app->session->setFlash('success', Yii::t('default','You have successfully placed a bid'));
				return $this->redirect(['view','id'=>$model->IDauction]);
			}
		}
		
		return $this->render('bid',[
			'model'=>$model,
		]);

	}
	
	/*
	* loadiram si mode, iz mg_auction_rating loadiram model koji je vezan za IDauctiom
	* korisit se za auctionBid()
	* @param $id - ID aukcije (IDauction)
	*/
	public function loadAuctionRatingModel($id)
	{
		$model=AuctionRating::find()->where(['IDauction'=>$id])->one();
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;

	}

    /**
     * Lists all Auction models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuctionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
     /* Lists all Auction models.
     * @return mixed
     */
    public function actionOpened()
    {
        $searchModel = new AuctionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
    /* Lists all Auction models.
     * @return mixed
     */
    public function actionClosed()
    {
        $searchModel = new AuctionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	/*
	* $user - ID of a user, when someone wants to see all auctions from specific user
	*/
    public function actionAuctionsByUser($user)
    {
        $searchModel = new AuctionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $user);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Auction model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {	
		$auction = $this->findViewModel($id);
		$Pigeon = new Pigeon;
		if(!empty($auction->title))
		{
			$title=$auction->auctionTitle($auction->relationIDpigeon->relationPigeonIDcountry->country, $auction->relationIDpigeon->sex, $auction->relationIDpigeon->pigeonnumber, $auction->title);

		}
		else	
		{
			$title=$auction->auctionTitle($auction->relationIDpigeon->relationPigeonIDcountry->country, $auction->relationIDpigeon->sex, $auction->relationIDpigeon->pigeonnumber, $auction->relationIDuser->username);
		}
	
        return $this->render('view', [
            'auction' => $auction,
			'title'=> $title,
        ]);
    }
	
    /**
     * Creates a new Auction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Auction();
		$pigeon = new AuctionPigeon();
		
        if ($model->load(Yii::$app->request->post())) 
		{
			
			$ExtraFunctions = new ExtraFunctions;
			$IDuser=Yii::$app->user->getId();
			$model->IDuser=$IDuser;
			
			if(empty($model->IDbreeder))
				$model->IDbreeder=$IDuser;
			
			//check if first image name is empty [0], if that's empty, everything is
			if(isset($_FILES['auction_images']['name']) && !empty($_FILES['auction_images']['name'][0]))
			{
				$images = ExtraFunctions::multipleImageUpload
				($_FILES['auction_images']['name'], 
				$_FILES['auction_images']['error'], 
				$_FILES['auction_images']['size'], 
				$_FILES["auction_images"]["tmp_name"]);
				
			}
			
			
			if($_POST["sellneworexisting"]=="new")
			{
				$pigeon->load(Yii::$app->request->post());
				if(isset($_FILES['AuctionPigeon']['name']["pedigree"]) && !empty($_FILES['AuctionPigeon']['name']["pedigree"]))
				{
					$uploadedImage=$ExtraFunctions->uploadImage
					($_FILES['AuctionPigeon']['name']["pedigree"], 
					$_FILES['AuctionPigeon']['tmp_name']["pedigree"], 
					$_FILES['AuctionPigeon']['size']["pedigree"], 
					$_FILES["AuctionPigeon"]["error"]["pedigree"],
					 $pigeon, 
					 'create', 
					 Auction::UPLOAD_DIR_PEDIGREE, 
					 'pedigree', 
					 Auction::PEDIGREE_SIZE);

					if($uploadedImage["uploadOk"]==1)
						$pigeon->pedigree=$uploadedImage["FILE_NAME"];
				}
				
			}
			else if($_POST["sellneworexisting"]=="existing")
			{
				if($_POST["malefemale"]=="male")
				{
					$IDpigeon=$_POST["Father_ID"];	
				}
				else if($_POST["malefemale"]=="female")
				{
					$IDpigeon=$_POST["Mother_ID"];	
				}
				
				//find information about pigeon to save it to mg_auction_pigeon
				$find=Pigeon::findOne($IDpigeon);
				$pigeon->pigeonnumber=$find->pigeonnumber;
				$pigeon->IDcountry=$find->IDcountry;
				$pigeon->sex=$find->sex;
				$pigeon->breed=$find->breed;
				$pigeon->pedigree="auto"; //it means that pedigree is generated automatically, it is not uploaded
			}
			
			$pigeon->type="auction";
			if($pigeon->save())
			{
				//save to Auction
				$model->IDpigeon=$pigeon->ID;
				if($model->save())
				{
					//save to AuctionRating
					$auctionRating=new AuctionRating;
					$auctionRating->IDseller=$IDuser;
					$auctionRating->IDwinner=$IDuser;
					$auctionRating->winner_rating=0;
					$auctionRating->seller_rating=0;
					$auctionRating->IDauction=$model->ID;
					$auctionRating->save();
					
					//save images to AuctionImage
					if(!empty($images))
					{
						foreach($images as $key=>$value)
						{
							$image = new AuctionImage;
							$image->IDauction=$model->ID;
							$image->image_file=$value;
							$image->save();
						}
					}
					
					//save to AccountBalance
					$AB=new AccountBalance;
					$AB->IDuser=$IDuser;
					$AB->IDauction=$model->ID;
					$AB->txn_id=Auction::NO_TXN_ID;
					$AB->payKey=Auction::NO_TXN_ID;
					$AB->save();

					return $this->redirect(['view', 'id' => $model->ID]);
				}
			}
			
        } 
		else 
		{
            return $this->render('create', [
                'model' => $model,
                'pigeon' => $pigeon,
            ]);
        }
    }

    /**
     * Updates an existing Auction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		
		$model = new Auction();
		$pigeon = new AuctionPigeon;
		$ExtraFunctions = new ExtraFunctions;

        $model = $this->findModel($id);
		//if auction has ended you cannot delete it
		Auction::didAuctionEnd($model, Yii::t('default', 'Auction has ended'));

		//because of token input it won't post IDbreeder (on update this field is empty on frontend) so set it here, because this field is required
		$IDbreeder=$model->IDbreeder;
		
        if ($model->load(Yii::$app->request->post())) 
		{
			//because of token input it won't post IDbreeder (its post is empty) so set it here, because this field is required
			if(empty($model->IDbreeder))
				$model->IDbreeder=$IDbreeder;
			
			if($model->save())
			{
				//check if first image name is empty [0], if that's empty, everything is
				if(isset($_FILES['auction_images']['name']) && !empty($_FILES['auction_images']['name'][0]))
				{
					$images = ExtraFunctions::multipleImageUpload
					($_FILES['auction_images']['name'], 
					$_FILES['auction_images']['error'], 
					$_FILES['auction_images']['size'], 
					$_FILES["auction_images"]["tmp_name"]);
					if(!empty($images))
					{
						foreach($images as $key=>$value)
						{
							$image = new AuctionImage;
							$image->IDauction=$model->ID;
							$image->image_file=$value;
							$image->save();
						}
					}

				}
				
				if(isset($_FILES['AuctionPigeon']['name']["pedigree"]) && !empty($_FILES['AuctionPigeon']['name']["pedigree"]))
				{
					$pigeon = AuctionPigeon::find()->where(['ID'=>$model->IDpigeon])->one();
					$uploadedImage=$ExtraFunctions->uploadImage($_FILES['AuctionPigeon']['name']["pedigree"], $_FILES['AuctionPigeon']['tmp_name']["pedigree"], $_FILES['AuctionPigeon']['size']["pedigree"], $_FILES["AuctionPigeon"]["error"]["pedigree"], $pigeon, 'create', Auction::UPLOAD_DIR_PEDIGREE, 'pedigree', Auction::PEDIGREE_SIZE);
					
					if($uploadedImage["uploadOk"]==1)
					{
						unlink(Yii::getAlias('@webroot').Auction::UPLOAD_DIR_PEDIGREE.$pigeon->pedigree);
						$pigeon->pedigree=$uploadedImage["FILE_NAME"];
						$pigeon->save();
					}
				}
				
				if(isset($_POST["delete_images"]))
				{
					//$value is ID in mg_auction_image
					foreach($_POST["delete_images"] as $value)
					{
						$image=AuctionImage::find()->where(['ID'=>$value,'IDauction'=>$model->ID])->one();
						if($image)
						{
							unlink(Yii::getAlias('@webroot').Auction::UPLOAD_DIR_IMAGES.$image->image_file);
							$image->delete();
						}
					}
				}

			}
            return $this->redirect(['view', 'id' => $model->ID]);
        } 
		else 
		{
			$images = AuctionImage::find()->where(['IDauction'=>$id])->all();
            return $this->render('update', [
                'model' => $model,
				'images'=>$images,
				'pigeon'=>$pigeon,
            ]);
        }
    }

    /**
     * Deletes an existing Auction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		/*
		You can't delete auction if:
		a) IT ENDED
		*/
		//a)
		$ExtraFunctions = new ExtraFunctions;
		$auction=Auction::findOne($id);

		//if auction has ended you cannot delete it
		Auction::didAuctionEnd($auction, Yii::t('default', 'You cannot delete this auction'));

		try
		{
			//find that pigeon in mg_auction_pigeon
			$deletePigeon=AuctionPigeon::findOne($auction->IDpigeon);
			//find all images
			$images=AuctionImage::find()->where(['IDauction'=>$id])->all();
			if($this->findModel($id)->delete())
			{
				if(!empty($images))
				{
					foreach($images as $value)
						unlink(Yii::getAlias('@webroot').Auction::UPLOAD_DIR_IMAGES.$value->image_file);
				}
				
				if(!empty($deletePigeon->pedigree) && $deletePigeon->pedigree!="auto")
				{
						unlink(Yii::getAlias('@webroot').Auction::UPLOAD_DIR_PEDIGREE.$deletePigeon->pedigree);
				}
				//delete pigeon
				$deletePigeon->delete();
			}
			
		}
		catch(IntegrityException $e)
		{
			throw new HttpException(403, Yii::t('default', 'Action was successful'));
		}

        return $this->redirect(['index']);
    }

    /**
     * Finds the Auction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Auction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Auction::find()->where(['ID'=>$id, 'IDuser'=>Yii::$app->user->getId()])->one()) !== null) 
		{
            return $model;
        } 
		else 
		{
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

			
    protected function findViewModel($id)
    {
		$model = Auction::find()->where(['ID'=>$id])->with(['relationRealIDcountry', 'relationIDcurrency', 'relationIDbreeder', 'relationAuctionImage', 'relationIDpigeon.relationPigeonIDcountry', 'relationIDuser'])->one();
        if ($model !== null) 
		{
            return $model;
        } 
		else 
		{
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
