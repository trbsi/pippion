<?php
namespace backend\helpers;
use Yii;
use backend\models\Breeder;
use yii\helpers\Url;
use yii\helpers\Html;

class LinkGenerator
{
	
	/*
	* Link to login page
	*/
	public function loginPageLink($text, $options=[] )
	{
		return Html::a($text, Url::to(['/user/security/login']), $options);
	}
	
	/*
	* page where user can see all bids on specific auction
	* $id - auction ID (ID in mg_auction)
	*/
	public function auctionAllBidsLink($text, $id, $options=[] )
	{
		return Html::a($text, Url::to(['/auction/all-bids', 'id'=>$id]), $options);
	}
	
	/*
	* view just one specific rating between seller and winner
	* $id - auction ID (ID in mg_auction)
	*/
	public static function auctionRatingViewLink($text, $id, $options=[] )
	{
		return Html::a('<i class="fa fa-star"></i>&nbsp;'.$text, Url::to(['/auction-rating/view', 'id'=>$id]), $options);
	}
	
	/*
	* updating rating for specific auction (adding feedback and rating to seller or winner)
	* $id - auction ID (ID in mg_auction)
	*/
	public static function auctionRatingUpdateLink($text, $id, $options=[] )
	{
		return Html::a('<i class="fa fa-star-half-o"></i>&nbsp;'.$text, Url::to(['/auction-rating/update', 'id'=>$id]), $options);
	}
	
	/*
	* link for checking all ratings for specific user
	* $id - is ID of user which rating we want to show
	*/
	public function auctionRatingLink($text, $id, $options=[] )
	{
		$options['target']='_blank';
		return Html::a($text, Url::to(['/auction-rating/index', 'user'=>$id]), $options);
	}
	
	/*
	* create link to breeders profile
	* $id - id in mg_users
	*/
	public static function breederLink($text, $id, $options=[])
	{	
		if(empty($options['target']))
			$options['target']='_blank';
		if(empty($options['class']))
			$options['class']='label';
		return Html::a($text, Url::to(['/breeder/view', 'id'=>$id]), $options);
	}
	
	
	/*
	* other auctions by specific user. When someone chooses to see all auctions of other specific user
	* $user - ID of a user, when someone wants to see all auctions from specific user
	*/
	public function otherAuctionsLink($text, $id, $options=[])
	{	
		$options['target']='_blank';
		return Html::a($text, Url::to(['/auction/auctions-by-user', 'user'=>$id]), $options);
	}
	
	
	/*
	* create link for auctions mg_acution
	* $id is ID in table
	*/
	public static function auctionLink($text, $id, $options=[])
	{
		if(empty($options['target']))
			$options['target']='_blank';
		return Html::a($text, Url::to(['/auction/view', 'id'=>$id]), $options);
		
	}
	
	/*
	* link to a specific pigeon
	* ID - ID in mg_pigeon
	*/
	public static function pigeonLink($text, $id, $options=[])
	{
		$options['target']='_self';
		return Html::a($text, Url::to(['/pigeon/view', 'id'=>$id]), $options);
		
	}
	
	/*
	* link to a specific status
	* ID - ID in mg_status
	*/
	public static function statusLink($text, $id, $options=[])
	{
		$options['target']='_self';
		return Html::a($text, Url::to(['/status/view', 'id'=>$id]), $options);
		
	}
	
	/*
	* link to send a message to specific user
	*/
	public static function sendMessageLink($iduser, $username, $options=[])
	{
		if(empty($options['target']))
			$options['target']='_blank';
		if(empty($options['class']))
			$options['class']='label label-important';
			
		$text='<i class="fa fa-envelope"></i>&nbsp;'.Yii::t('default', 'Contact')." ".$username;
		return Html::a($text, Url::to(['/messages/messages/inbox', 'iduser'=>$iduser, 'username'=>$username]), $options);
		
	}
	
	/* 
	* auction "Resolve an Issue" link
	* $IDauction - ID in Auction
	*/
	public static function auctionResolveAnIssue($text, $IDauction, $options=[])
	{
		return Html::a('<i class="fa fa-thumbs-down"></i>&nbsp;'.$text, Url::to(['/resolve-issue/create', 'id'=>$IDauction]), $options);
		
	}

	/* 
	* auction "Resolve an Issue" link
	* $IDauction - ID in Auction
	*/
	public static function auctionPigeonHasArrived($text, $IDauction, $options=[])
	{
		return Html::a('<i class="fa fa-twitter"></i>&nbsp;'.$text, Url::to(['/auction/pigeon-arrived', 'id'=>$IDauction]), $options);
	}
	
	/*
	* Generate link for index page of clubs where people can search for all clubs
	*/
	public static function clubListOfClubs($text, $options=[])
	{
		return Html::a($text, Url::to(['/club/club/index', 'club_page'=>'0']), $options);
	}
	
	/*
	* Generate link for individual club page
	* $club_link is in Club(mg_club) column "club_link"
	*/
	public static function clubIndividualClubpage($text, $club_link, $options=[])
	{
		return Html::a($text, Url::to(["/club/club/view", "club_page"=>$club_link]), $options);
	}
	
	/*
	* Link to the terms of use and rules of auctions
	*/
	public static function auctionTermsOfUse($text, $options=[])
	{
		$options['target']='_blank';
		return Html::a($text, Url::to(["/auction/rules"]), $options);
	}
	
	
}