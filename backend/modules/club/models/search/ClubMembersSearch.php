<?php

namespace backend\modules\club\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\club\models\ClubMembers;

/**
 * ClubMembersSearch represents the model behind the search form about `backend\modules\club\models\ClubMembers`.
 */
class ClubMembersSearch extends ClubMembers
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'IDclub'], 'integer'],
            [['name', 'address', 'tel', 'mob', 'email'], 'safe'],
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
	 * $clubModel - model from mg_club (Club)
	 */
    public function search($params, $clubModel)
    {
        $query = ClubMembers::find();
		$query->where(['IDclub'=>$clubModel->ID]);
		$query->with(['relationIDclub']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'IDclub' => $this->IDclub,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'tel', $this->tel])
            ->andFilterWhere(['like', 'mob', $this->mob])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
