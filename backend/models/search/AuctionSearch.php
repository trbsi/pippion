<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Auction;
use backend\models\AuctionPigeon;
use backend\models\Pigeon;
use backend\helpers\ExtraFunctions;

/**
 * AuctionSearch represents the model behind the search form about `backend\models\Auction`.
 */
class AuctionSearch extends Auction
{
	public $pigeon_search;
	public $pigeon_country_search;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'IDuser', 'IDpigeon', 'country', 'IDbreeder', 'currency'], 'integer'],
            [['title', 'start_time', 'end_time', 'information', 'city'], 'safe'],
            [['start_price', 'min_bid'], 'number'],
            [['pigeon_search', 'pigeon_country_search'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
	 * $user - user ID sending frm actionAuctionsByUser()
     */
    public function search($params, $user=NULL)
    {
		$ExtraFunctions = new ExtraFunctions;
		$auctionPigeonTable = AuctionPigeon::getTableSchema();
		$auctionTable = Auction::getTableSchema();
		$pigeonTable = Pigeon::getTableSchema();
		
        $query = Auction::find();
		
		$action=Yii::$app->controller->action->id;
		if($action=="index")
		{
			$query->where([$auctionTable->name.'.IDuser'=>Yii::$app->user->getId()]);
		}
		else if($action=="opened")
		{
			$query->where($auctionTable->name.'.end_time > :current_time', [':current_time'=>$ExtraFunctions->currentTime("ymd-his")]);
		}
		else if($action=="closed")
		{
			$query->where($auctionTable->name.'.end_time < :current_time', [':current_time'=>$ExtraFunctions->currentTime("ymd-his")]);
		}
		else if($action=="auctions-by-user")
		{
			$query->where([$auctionTable->name.'.IDuser'=>$user]);
		}
		
		$query->with(['relationIDpigeon', 'relationIDpigeon.relationPigeonIDcountry', 'relationIDuser', 'relationIDcurrency', 'relationAuctionImage', 'relationAuctionBid', 'relationAuctionRating', 'relationAccountBalance']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['start_time'=>SORT_DESC]],//newest first
			'pagination'=>
			[
				'pageSize'=>100,
			]
        ]);
		
		$dataProvider->sort->attributes['pigeon_search'] = [
			// The tables are the ones our relation are configured to
			// in my case they are prefixed with "tbl_"
			'asc' => [$auctionPigeonTable->name.'.pigeonnumber' => SORT_ASC],
			'desc' => [$auctionPigeonTable->name.'.pigeonnumber' => SORT_DESC],
		];
		
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
           
        $query->andFilterWhere([
            ///'ID' => $this->ID,
            'IDuser' => $this->IDuser,
            //'IDpigeon' => $this->IDpigeon,
           // 'start_time' => $this->start_time,
            //'end_time' => $this->end_time,
            'country' => $this->country,
            'IDbreeder' => $this->IDbreeder,
            'start_price' => $this->start_price,
            'min_bid' => $this->min_bid,
            'currency' => $this->currency,
			$auctionPigeonTable->name.".IDcountry" => $this->pigeon_country_search,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'information', $this->information])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['between', 'start_time', $ExtraFunctions->between_0_24($this->start_time, 'beginning'), $ExtraFunctions->between_0_24($this->start_time, 'end')])
            ->andFilterWhere(['between', 'start_time', $ExtraFunctions->between_0_24($this->end_time, 'beginning'), $ExtraFunctions->between_0_24($this->end_time, 'end')])
            ->andFilterWhere(['like', $auctionPigeonTable->name.".pigeonnumber" , $this->pigeon_search]) //search only this pigeonnumber because if you add existing pigeon it will be saved to mg_auction_pigeon so every pigeon can be searched from that table
			;

        return $dataProvider;
    }
}
