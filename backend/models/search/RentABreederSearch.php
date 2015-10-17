<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\RentABreeder;

/**
 * RentABreederSearch represents the model behind the search form about `backend\models\RentABreeder`.
 */
class RentABreederSearch extends RentABreeder
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'IDuser', 'IDcountry', 'active'], 'integer'],
            [['city', 'breeder_picture', 'extra_info'], 'safe'],
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
	 * $active //if isset GET[admin] search for non active ads, else search for active ads
	 * $IDuser //if isset GET[mine] search only ads from currently logged users
     */
    public function search($params, $active, $IDuser=NULL)
    {
        $query = RentABreeder::find();
		$query->where(['active'=>$active]);
			if($IDuser!=NULL)
		$query->andWhere(['IDuser'=>$IDuser]);
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=>['attributes'=>['IDcountry', 'city']]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'IDuser' => $this->IDuser,
            'IDcountry' => $this->IDcountry,
            'active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'breeder_picture', $this->breeder_picture])
            ->andFilterWhere(['like', 'extra_info', $this->extra_info]);

        return $dataProvider;
    }
}
