<?php

namespace backend\modules\club\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\club\models\Club;

/**
 * ClubSearch represents the model behind the search form about `backend\modules\club\models\Club`.
 */
class ClubSearch extends Club
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'IDcountry'], 'integer'],
            [['club', 'club_link', 'date_created', 'about', 'contact'], 'safe'],
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
     */
    public function search($params)
    {
        $query = Club::find();
		$query->where("club!='index'"); //do not search word "index"
		$query->with(['relationIDcountry']);
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=>['defaultOrder'=>['date_created'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'IDcountry' => $this->IDcountry,
            'date_created' => $this->date_created,
        ]);

        $query->andFilterWhere(['like', 'club', $this->club])
            ->andFilterWhere(['like', 'club_link', $this->club_link])
            ->andFilterWhere(['like', 'about', $this->about])
            ->andFilterWhere(['like', 'contact', $this->contact]);

        return $dataProvider;
    }
}

?>