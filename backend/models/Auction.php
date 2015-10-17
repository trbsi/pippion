<?php

namespace backend\models;

use Yii;
use dektrium\user\models\User;
use backend\helpers\ExtraFunctions;
use backend\helpers\LinkGenerator;
use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\AuctionBid;
use backend\models\AuctionPigeon;
use backend\models\AuctionImage;
use backend\models\Pigeon;
use yii\web\HttpException;

/**
 * This is the model class for table "{{%auction}}".
 *
 * @property integer $ID
 * @property integer $IDuser
 * @property integer $IDpigeon
 * @property string $title
 * @property string $start_time
 * @property string $end_time
 * @property string $information
 * @property integer $country
 * @property string $city
 * @property integer $IDbreeder
 * @property double $start_price
 * @property double $min_bid
 * @property integer $currency
 *
 * @property User $iDbreeder
 * @property AuctionPigeon $iDpigeon
 * @property User $iDuser
 * @property CountryList $country0
 * @property Currency $currency0
 * @property AuctionBid[] $auctionBs
 * @property AuctionImage[] $auctionImages
 * @property AuctionRating[] $auctionRatings
 */
class Auction extends \yii\db\ActiveRecord
{
	//full path backend/web/images/auction/
	const UPLOAD_DIR_IMAGES = "/images/auction/";
	const UPLOAD_DIR_PEDIGREE = "/files/auction/";
	const IMAGE_SIZE = 2097152; //in bytes - 2mb
	const PEDIGREE_SIZE = 4194304; //in bytes - 4mb
	const PAYPAL_SANDBOX=false;
	const NO_TXN_ID="none";
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auction}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDuser', 'start_time', 'end_time', 'country', 'city', 'start_price', 'min_bid', 'currency'], 'required'],
            [['IDuser', 'IDpigeon', 'country', 'IDbreeder', 'currency'], 'integer'],
            [['start_time', 'end_time'], 'safe'],
            [['start_time', 'end_time'], 'date', 'format'=>'yyyy-MM-dd HH:mm:ss'],
            [['information'], 'string'],
            [['start_price', 'min_bid'], 'number'],
            [['title'], 'string', 'max' => 50],
            [['city'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
            'IDuser' => Yii::t('default', 'Auctioneer'),
            'IDpigeon' => Yii::t('default', 'Pigeon'),
            'title' => Yii::t('default', 'Title'),
            'start_time' => Yii::t('default', 'Start Time'),
            'end_time' => Yii::t('default', 'End Time'),
            'information' => Yii::t('default', 'Information'),
            'country' => Yii::t('default', 'Country'),
            'city' => Yii::t('default', 'City'),
            'IDbreeder' => Yii::t('default', 'Breeder'),
            'start_price' => Yii::t('default', 'Start Price'),
            'min_bid' => Yii::t('default', 'Min Bid'),
            'currency' => Yii::t('default', 'Currency'),
        ];
    }

	 /**
     * @return \yii\db\ActiveQuery
	 * ADDED BY ME, I THINK I FIXED FROM hasMany to hasOne
     */
    public function getRelationAccountBalance()
    {
        return $this->hasOne(AccountBalance::className(), ['IDauction' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDbreeder()
    {
        return $this->hasOne(User::className(), ['id' => 'IDbreeder']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDpigeon()
    {
        return $this->hasOne(AuctionPigeon::className(), ['ID' => 'IDpigeon']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDuser()
    {
        return $this->hasOne(User::className(), ['id' => 'IDuser']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationRealIDcountry()
    {
        return $this->hasOne(CountryList::className(), ['ID' => 'country']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDcurrency()
    {
        return $this->hasOne(Currency::className(), ['ID' => 'currency']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationAuctionBid()
    {
        return $this->hasMany(AuctionBid::className(), ['IDauction' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationAuctionImage()
    {
        return $this->hasMany(AuctionImage::className(), ['IDauction' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationAuctionRating()
    {
        return $this->hasOne(AuctionRating::className(), ['IDauction' => 'ID']);
    }
	
	/*
	* radio button. Choose to sell new or existing pigeon.
	* HOW TO USE:
		<?php
		echo $Auction->sellNewOrExisting();
		?>

		<div id="div-new" style="display:none;">
			FIELDS
		</div>
		
		<div id="div-existing" style="display:none;">
			FIELDS
		</div>

	*/
	public function sellNewOrExisting()
	{
		$return=NULL;
		
		ob_start();
		?>
        <script>
		$(document).ready(function(e) 
		{
			var radioexisting =  $("#radioexisting");
			var radionew =  $("#radionew");
			var div_existing =  $("#div-existing");
			var div_new =  $("#div-new");
			var on_hide_fill = $(".js-onhide-fill");
			var Father_ID = $("#Father_ID");
			var Mother_ID = $("#Mother_ID");
			
          	radionew.click(function()
			{
				div_new.show();
				//when user click to add new pigeon put fields back to null
				on_hide_fill.val("");
				Father_ID.prop("required", false);
				Mother_ID.prop("required", false);
				
				div_existing.hide();
			});
			
            radioexisting.click(function()
			{
				div_new.hide();
				//if div-new is hidden than fill required fields so user can submit it without getting error
				on_hide_fill.val("-");
				
				div_existing.show();
				Father_ID.prop("required", true);
				$("#radio-male").prop("selected", true);
			});
        });
		</script>
		<div class="alert alert-info">
			<div class="radio radio-primary">
				<input id="radionew" type="radio" name="sellneworexisting" value="new">
				<label for="radionew"><?= Yii::t('default', 'New') ?></label>
				<input id="radioexisting" type="radio" name="sellneworexisting" value="existing">
				<label for="radioexisting"><?= Yii::t('default', 'Existing') ?></label>
			</div>
		</div>
		<?php
		$return=ob_get_clean();
		
		return $return;
	}

	
	/*
	* vrati zadnju zadnju ponudu i biddera
	* $Auction = Auction model of specific auction
	*/
	public static function lastBidder($Auction)
	{
		$relationAuctionBid=$Auction->relationAuctionBid;
		if(empty($relationAuctionBid))
			$return='<span class="label label-danger">'.Yii::t('default', 'No bids yet').'</span>';
		else
		{
			$lastBid=end($relationAuctionBid);
			$return='<span class="label label-success">'.$lastBid->price.' '.$lastBid->relationIDauction->relationIDcurrency->currency.'</span> '.LinkGenerator::breederLink($lastBid->relationIDbidder->username,$lastBid->IDbidder);
		}
		return $return;
	}

	/*
	* what is latest price per auction
	* $Auction = Auction model from AuctionSearch so you can fetch currency
	*/
	public static function latestPrice($Auction)
	{
		$relationAuctionBid=$Auction->relationAuctionBid;
		if(!empty($relationAuctionBid))
		{
			$lastBid=end($relationAuctionBid); //get the latest element in array, the latest bid
			$return=$lastBid->price." ".$Auction->relationIDcurrency->currency;
		}
		else
			$return="0 ".$Auction->relationIDcurrency->currency;
		return $return;
	}
		
	/*
	* PRONAĐI PRVU SLIKU KOJA JE VEZANA ZA TU AUKCIJU DA TU PRIKAŽEŠ KAO THUMBNAIL
	* @param $Auction - Auction model, so you can call $Auction->relationAuctionImage
	* @return ime slike
	*/
	public static function auctionImage($Auction)
	{
		if(!empty($Auction->relationAuctionImage[0]))		
			return $Auction->relationAuctionImage[0]->image_file;
		else
			return ExtraFunctions::NO_PICTURE;
	}
	
	
	/*
	* PRONAĐI SVE SLIKE KOJE SU VEZANE ZA TU AUKCIJU DA IH MOŽEŠ PRIKAZAT U COLORBOX
	* @return html anchor links
	* $alt_title - is title of auctions that goes into alt of image
	* $auction - Auction model
	*/
	public function otherAuctionImages($auction, $alt_title)
	{
		$return=NULL;
		//do not show first image because it is main image for that auction
		$i=0;
		foreach($auction->relationAuctionImage as $value)
		{
			if($i==0)
			{
				$i++;
				continue;
			}
			else
			{
				$i++;
				$return.='<a class="group1_colorbox" href="'.Yii::getAlias('@web').Auction::UPLOAD_DIR_IMAGES.$value->image_file.'" alt="'.$alt_title.'"></a>';
			}
		}
		
		return '<div style="display:none;">'.$return.'</div>';
	}


	/*
	* vrati informacije o čovjeku koji je pokrenuo aukciju, njegov rating i druge aukcije koje je ima
	* @param IDkorisnik, ID of user for who we want to check rating
	*/
	public function sellerRating($IDuser)
	{
		$LinkGenerator = new LinkGenerator;
		$return=NULL;
		$ocjena=0; // ukupna ocjena, tipa 5+2+5+3=15
		$ocjenaCount=0; // broj ocjena, npr. 5+2+5+3->4 ocjene
		
		//prvo pronađi sve gdje je pokretač aukcije(seller) IDseller u mg_auction_rating i zbroj ocjene
		//zbroj sa seller_rating jer je to ocjena pokretača aukcije(sellera) koju je dobio od winnera
		//the same for seller and winner
		$rating=AuctionRating::find()->where(['IDseller'=>$IDuser])->orWhere(['IDwinner'=>$IDuser])->all();
		foreach($rating as $value)
		{
			if($value->IDseller==$IDuser)
			{
				$ocjena=$ocjena+$value->seller_rating;
				//samo ako je ocjena različita od nule onda zbroji jer ako je jednaka nuli to znači da nije ocjenjen
				if($value->seller_rating!=0)
					$ocjenaCount++;
			}
			else if($value->IDwinner==$IDuser)
			{
				$ocjena=$ocjena+$value->winner_rating;
				//samo ako je ocjena različita od nule onda zbroji jer ako je jednaka nuli to znači da nije ocjenjen
				if($value->winner_rating!=0)
					$ocjenaCount++;
			}
		}
		

		//ako ima rating onda računaj
		if($ocjena!=0)
		{
			$percentage=round($ocjena/$ocjenaCount,2);
			$percentage=$percentage.'/5';
		}
		else
		{
			$percentage=Yii::t('default', "No rating");
		}

		
		$return.="<p>";
		$return.=$LinkGenerator->breederLink($this->relationIDuser->username, $IDuser);
					
		$return.=$LinkGenerator->auctionRatingLink('&nbsp;&nbsp;<span class="badge badge-inverse">'.$percentage.' ('.$ocjenaCount.')</span>', $IDuser);
		$return.="</p>";
				
		$return.='<p>'.$LinkGenerator->otherAuctionsLink(Yii::t('default', 'Other auctions'),$IDuser, ['class'=>'btn btn-default btn-sm btn-small btn-block']).'</p>';
				
		return $return;
	}



	/*
	* prikazuje textbox i bid button za bidanje aukcije u auction/view.php, a ako gost nije logiran prikazuje link da se logira da može biddat
	* a ako je prošlo vrijeme aukcije, kaže da je aukcija gotova :D
	* $Auction - (Auction model) loaded model of specific Auction
	*/
	public function bidForm($Auction)
	{
		$ExtraFunctions = new ExtraFunctions;
		$LinkGenerator = new LinkGenerator;
		$return=NULL;
		$userID=Yii::$app->user->getId();

		if(Yii::$app->user->isGuest)
		{
			return $LinkGenerator->loginPageLink(Yii::t('default', 'Login to bid'), ['class'=>'btn btn-primary']);
		}
		//ako aukcija još nije počela
		else if($ExtraFunctions->currentTime("ymd-his") < $Auction->start_time)
		{
			return '<span style="color:red; font-weight:bold;">'.Yii::t('default', 'Auction hasnt started yet').'</span>';
		}
		//ako je aukcija završila
		else if($ExtraFunctions->currentTime("ymd-his") > $Auction->end_time)
		{
			//provjeri tko je pobjednik aukcije i uzmi ID iz mg_auction_rating da možeš stvoriti link gdje će vidjeti rating za tu aukciju
			$auctionRating=$Auction->relationAuctionRating;
			//find information about account balance
			$AccountBalance=$Auction->relationAccountBalance;
			$return.="<div>";
			$return.='<div class="alert alert-danger">'.Yii::t('default', 'Auction has ended').'</div>';
			//resolve an issue:
				//only winner and seller can do it
				//only if money wasn't transferred to seller's account yet 
				//only if person who started an auction is not a winner (in other words if there wasn't any bids you cannot resolve an issue)
			if(($auctionRating->IDseller==$userID || $auctionRating->IDwinner==$userID) && $Auction->relationAccountBalance->money_transferred==0 && $auctionRating->IDwinner!=$Auction->IDuser)
			{
				$return.='<p>'
							.LinkGenerator::auctionResolveAnIssue(Yii::t('default', 'Resolve an issue'), $Auction->ID,  ['class'=>'btn btn-block btn-danger']).
						'</p>';
			}
			$return.='<div class="col-md-4 m-b-10">'
						.LinkGenerator::auctionRatingViewLink(Yii::t('default', 'See rating'), $Auction->ID, ['class'=>'btn btn-primary btn-block']).
					'</div>';
			
			//if there were no bids
			if($auctionRating->IDwinner==$Auction->IDuser)
			{
			}
			//ako se stranica prikazuje pokretaču aukcije, seller
			else if($auctionRating->IDseller==$userID)
			{
				//RATE WINNER
				$return.='<div class="col-md-4 m-b-10">'
							.LinkGenerator::auctionRatingUpdateLink(Yii::t('default', 'Rate winner'), $Auction->ID, ['class'=>'btn btn-primary btn-block']).
						'</div>';	
				//CONTACT WINNER				
				$return.='<div class="col-md-4 m-b-10">'
							.LinkGenerator::sendMessageLink($auctionRating->IDwinner, $auctionRating->relationIDwinner->username, ['class'=>'btn btn-primary btn-block']).
						'</div>';					
			}
			//ako je logirani korisnik pobjedio u aukciji može ostaviti feedback, winner
			else if($auctionRating->IDwinner==$userID)
			{
				//RATE SELLER
				$return.='<div class="col-md-4 m-b-10">'
							.LinkGenerator::auctionRatingUpdateLink(Yii::t('default', 'Rate seller'), $Auction->ID, ['class'=>'btn btn-primary btn-block']).
						'</div>';	
								
				//CONTACT SELLER				
				$return.='<div class="col-md-4 m-b-10">'
							.LinkGenerator::sendMessageLink($auctionRating->IDseller, $auctionRating->relationIDseller->username, ['class'=>'btn btn-primary btn-block']).
						'</div>';	
				
				/*PIGEON HAS ARRIVED 
				Show if:
					- pigeon_arrived=0 (that means that he didn't confirm that pigeon arrived)
					- txn_id is not "none" (it means that he has paid for it, transferred money, now he can confirm that pigeon arrived, since that buttons is connected with payKey in AccountBalance)
				
				if($Auction->relationAccountBalance->pigeon_arrived==0 && $AccountBalance->txn_id!=Auction::NO_TXN_ID)
				{
					$return.='<div class="col-md-12 m-b-10">'
								.LinkGenerator::auctionPigeonHasArrived(Yii::t('default', 'Pigeons have arrived'), $auction->ID, ['class'=>'btn btn-success btn-block', 'onclick'=>'return areYouSure()']).
							'</div>';
				}*/
				
				//pay now button
				if(Auction::PAYPAL_SANDBOX==true)
				{
					$ipn_url="http://pippion.test.thetta.com.hr/ipn/ipn-paypal-auction";
					$return_url="http://pippion.test.thetta.com.hr/ipn/return-url-paypal-auction?IDauction=$Auction->ID";
				}
				else
				{
					$ipn_url="https://www.pippion.com/ipn/ipn-paypal-auction";
					$return_url="https://www.pippion.com/ipn/return-url-paypal-auction?IDauction=$Auction->ID";
				}
				
				//PAYPAL BUYNOW
				//check if buyer already paid for that pigeon
				//txn_id is set in IpnController -> actionDelayPaymentsPaypalAuction()
				if($AccountBalance->txn_id==Auction::NO_TXN_ID && $AccountBalance->money_transferred==0)
				{
					//($sandbox, $item_name, $amount, $currency, $custom=NULL, $ipn_url=NULL, $return_url=NULL)
					$return.=
					'<div class="col-md-12 m-b-10 text-center">'.
					Html::beginForm(Url::to(['/auction/pay']), 'POST').
					Html::submitButton('<img src="https://www.paypalobjects.com/webstatic/en_US/btn/btn_paynow_cc_144x47.png">', ['style'=>'background:none; border:none;']).
					Html::hiddenInput('idauction', $Auction->ID, $options = [] ).	
					Html::hiddenInput('memo', "Pippion Auction: ID $Auction->ID.".mt_rand(), $options = [] ).	
					Html::endForm();
					'</div>';
				}
					
			}
			$return.="</div>";
			
			return $return;
		}
		else
		{
			//budući da ću koristiti AuctionBid model, moram stvoriti novi model da ga mogu pridružiti activeField
			$model=new AuctionBid;
			$return=
				'<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row-margin-bottom">
		 		'.Html::beginForm(Url::to(['/auction/bid']), 'post', ['onSubmit'=>'return areYouSure()']).'
				'.Html::activeInput('number', $model, 'price', ['style'=>'width:100px;'] ).'
				'.$this->relationIDcurrency->currency.' ('.$this->relationIDcurrency->country.')
				'.Html::activeHiddenInput($model, 'IDauction', ['value'=>$this->ID]).'
				'.Html::submitButton(Yii::t('default', 'Bid') ,['class'=>'btn btn-primary']).'
				'.Html::endForm().'
				'.LinkGenerator::auctionTermsOfUse(Yii::t('default', 'Agree to the terms'), ['style'=>'text-decoration:underline']).'
				</div>
				
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<strong>'.Yii::t('default', 'Min bid').':</strong> '.Yii::t('default', 'Current bid').' + '.$this->min_bid.' '.$this->relationIDcurrency->currency.' ('.$this->relationIDcurrency->country.') '.Yii::t('default', 'or more').'
				</div>
				
				</div>
				<hr>';
				  
			return $return;
		}
	}


	/*
	* POKUPI SVE INFORMACIJE IZ MG_GOLUB ZA TOG NEKOG GOLUBA DA MOŽEŠ PRIKAZATI
	* $Auction - (Auction model) loaded model of specific Auction
	* @return string, informaciej o golubu
	*/
	public function pigeonInfo($Auction) //RelationIDpigeon
	{
		$info=$Auction->relationIDpigeon;
		$Pigeon = new Pigeon;
		$LinkGenerator = new LinkGenerator;
		$pedigree=NULL;
		
		//if pedigree is auto it means it should generate pedigree automatically on site
		if($info->pedigree=="auto")
		{

			$pedigree.=Html::beginForm(Url::to(['/pigeon/pedigree']), 'post');
			$pedigree.=Html::hiddenInput("pigeonnumber", $info->pigeonnumber);
			$pedigree.=Html::hiddenInput('user', $this->IDuser);
			$pedigree.=Html::submitButton('<img src="/images/pdf.gif">', ['class'=>'btn btn-white'] );
			$pedigree.=Html::endForm();

		}
		else
			$pedigree.='<a href="'.Auction::UPLOAD_DIR_PEDIGREE.$info->pedigree.'" target="_blank"><img src="/images/pdf.gif"></a>';
		
		$return=
		'
			<div class="table-responsive">
			<table class="table table-bordered ">
			  <thead>
			  <tr>
				<th>'.Yii::t('default', 'Country').'</th>
				<th>'.Yii::t('default', 'Pigeon number').'</th>
				<th>'.Yii::t('default', 'Sex').'</th>
				<th>'.Yii::t('default', 'Rasa').'</th>
				<th>'.Yii::t('default', 'Pedigree').'</th>
			  </tr>
			  </thead>
			  <tbody>
			  <tr>
				<td>'.$info->relationPigeonIDcountry->country.'</td>
				<td>'.$info->pigeonnumber.'</td>
				<td>'.$Pigeon->getSex($info->sex).'</td>
				<td>'.$info->breed.'</td>
				<td>'.$pedigree.'</td>
			  </tr>
			  </tbody>
			</table>
			</div>
		';
		
		
		return $return;
	}
	
	
	/*
	* generate auction title
	*/
	public static function auctionTitle($pigeonCountry, $pigeonSex, $pigeonnumber, $titleOrUsername)
	{
		$title=$titleOrUsername.' | ['.$pigeonCountry.'] '.Pigeon::getSex($pigeonSex).'/'.$pigeonnumber;
		
		return $title;
	}
	
	/*
	* sorting auctions
	*/
	public function sortAuctions()
	{
		$fields=
		[
            'pigeon_search' => Yii::t('default', 'Pigeon'),
			//'pigeon_country_search' => Yii::t('default', 'GOLUB_DRZAVA'),
            'start_time' => Yii::t('default', 'Start Time'),
            'end_time' => Yii::t('default', 'End Time'),
            'country' => Yii::t('default', 'Country'),
            'city' => Yii::t('default', 'City'),
            'start_price' => Yii::t('default', 'Start Price'),
            'currency' => Yii::t('default', 'Currency'),
		];
		
		//I guess I did this because I had to strip: sort, asc_desc and field from url params and set them again so they are not added one after another, especailly if there are other params in url
		$url='http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		$query = parse_url($url, PHP_URL_QUERY);
		parse_str($query, $get_array);
		unset($get_array['sort'], $get_array['asc_desc'], $get_array['field']);
		$build_query=http_build_query($get_array);
		$and=(empty($build_query)) ? '' : '&';
		$url='http'.(isset($_SERVER['HTTPS']) ? 's' : '').'://'."{$_SERVER['HTTP_HOST']}/".Yii::$app->controller->id ."/".Yii::$app->controller->action->id."?".$build_query.$and;

		$return=NULL;
		ob_start();
		?>
		<script>
		$(document).ready(function(e) {
            $('#sort-go').click(function()
			{
				var val_asc_desc = $('select[name="asc-desc"]').val();
				var val_fields = $('select[name="fields"]').val();
				var url = '<?= $url ?>sort='+val_asc_desc+val_fields+'&asc_desc='+val_asc_desc+'&field='+val_fields;
				
				window.location.href=url;
			});
        });
		</script>
		<?php
		$return.=ob_get_clean();
		$selection1=(isset($_GET["asc_desc"]))?$_GET["asc_desc"]:null;
		$selection2=(isset($_GET["field"]))?$_GET["field"]:null;
		$return.='<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">'.Html::dropDownList('asc-desc', $selection1, [''=>Yii::t('default', 'Ascending'),'-'=>Yii::t('default', 'Descending')], ['class'=>'form-control']).'</div>';
		$return.='<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">'.Html::dropDownList('fields', $selection2, $fields, ['class'=>'form-control']).'</div>';
		$return.='<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">'.Html:: button(Yii::t('default', 'Sort'),['class'=>'btn btn-cons btn-primary btn-small btn-block', 'id'=>'sort-go'] ).'</div>';
		
		return $return;
	}
	
	
	/*
	* calculate Pippion commission for every pigeon on auction
		5% = <=99€/$ 
		10% = 100€/$ - 999€/$ 
		15% = 1000€/$ - 9999€/$ 
		20% = >=10 000€/$
	* $price - price taken from latest bid on specific auction
	*/
	public static function calculateCommission($price)
	{
		if($price <= 99)
		{
			$pippionCommission=$price*5/100;
		}
		else if($price >= 100 && $price <=999)
		{
			$pippionCommission=$price*10/100;
		}
		else if($price >= 1000 && $price <=9999)
		{
			$pippionCommission=$price*15/100;
		}
		else if($price >= 10000)
		{
			$pippionCommission=$price*20/100;
		}
		
		return round($pippionCommission,2);
	}
	
	/*
	* Show user how much will we take from him on this auction
		5% = <=99€/$ 
		10% = 100€/$ - 999€/$ 
		15% = 1000€/$ - 9999€/$ 
		20% = >=10 000€/$
	* $Auction = Auction model of specific auction
	*/
	public static function takePercentage($Auction)
	{
		$IDuser=Yii::$app->user->getId();
		$relationAuctionBid=$Auction->relationAuctionBid;
		
		//only show this if auction has ended
		if( !empty($relationAuctionBid)
			&&
			($Auction->end_time < ExtraFunctions::currentTime("ymd-his")) 
			&& 
			($Auction->relationAuctionRating->IDwinner==$IDuser || $Auction->relationAuctionRating->IDseller==$IDuser)
		)
		{
			//find latest bid. 
			$lastBid=end($relationAuctionBid);
			$pippionCommission=self::calculateCommission($lastBid->price);
			
			 return
			 '
			 <strong>'.Yii::t('default', 'Commission for Pippion').':</strong> <br>
             <span class="label label-success">'.$pippionCommission." ".$Auction->relationIDcurrency->currency.'</span> 
			 <a href="'.Url::to(['/auction/rules#percentage-take']).'" target="_blank">&nbsp;<i class="fa fa-question-circle"></i></a>
			 ';

		}
	}
	
	/*
	* check if auction ended, and if ended you cannot do some action
	* $Auction - loaded model of Auction
	*/
	public static function didAuctionEnd($Auction, $message)
	{
		//if auction has ended you cannot delete it
		if($Auction->end_time < ExtraFunctions::currentTime("ymd-his"))
			throw new HttpException(403,$message);
	}
	
	
}//CLASS END
