<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AuctionRating;

/**
 * AuctionRatingSearch represents the model behind the search form about `backend\models\AuctionRating`.
 */
class AuctionRatingSearch extends AuctionRating
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'IDauction', 'winner_rating', 'seller_rating', 'IDwinner', 'IDseller'], 'integer'],
            [['winner_feedback', 'seller_feedback'], 'safe'],
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
	 * $id - is ID of user which rating we want to show
     */
    public function search($params, $user=NULL)
    {
        $query = AuctionRating::find();
		if($user!=NULL)
		{
			$query->where("IDwinner=:id OR IDseller=:id", [':id'=>$user]);
		}
		else
		{
			$IDuser=Yii::$app->user->getId();
			if($_SESSION["rating_show_only"]=="both")
			{
				$query->where("IDwinner=:id OR IDseller=:id", [':id'=>$IDuser]);
			}
			else if($_SESSION["rating_show_only"]=="winner")
			{
				$query->where(['IDwinner'=>$IDuser]);
			}
			else if($_SESSION["rating_show_only"]=="seller")
			{
				$query->where(['IDseller'=>$IDuser]);
			}
			else
			{
				$query->where("IDwinner=:id OR IDseller=:id", [':id'=>$IDuser]);
			}
			
		}
		
		$query->andWhere("IDwinner!=IDseller");
		$query->with(['relationIDauction.relationIDuser', 'relationIDauction.relationIDpigeon.relationPigeonIDcountry', 'relationIDwinner', 'relationIDseller', 'relationIDauction.relationAuctionImage']);
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=>['defaultOrder'=>['ID'=>SORT_DESC]]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            //'ID' => $this->ID,
            'IDauction' => $this->IDauction,
            'winner_rating' => $this->winner_rating,
            'seller_rating' => $this->seller_rating,
            'IDwinner' => $this->IDwinner,
            'IDseller' => $this->IDseller,
        ]);

        $query->andFilterWhere(['like', 'winner_feedback', $this->winner_feedback])
            ->andFilterWhere(['like', 'seller_feedback', $this->seller_feedback]);

        return $dataProvider;
    }
}
