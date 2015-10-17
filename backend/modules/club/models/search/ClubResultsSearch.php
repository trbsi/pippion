<?php

namespace backend\modules\club\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\club\models\ClubResults;

/**
 * ClubResultsSearch represents the model behind the search form about `backend\modules\club\models\ClubResults`.
 */
class ClubResultsSearch extends ClubResults
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'IDclub', 'IDuser', 'result_type'], 'integer'],
            [['pdf_file', 'place', 'distance', 'year', 'date_created', 'description'], 'safe'],
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
        $query = ClubResults::find();
		$query->where(["IDclub"=>$clubModel->ID]);
		$query->with(['relationIDclub']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => ['attributes' => ['place', 'distance', 'year', 'date_created'], 'defaultOrder'=>['year'=>SORT_DESC, 'date_created'=>SORT_ASC]],
			'pagination'=>
			[
				'pageSize'=>30,
			]

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
            'IDuser' => $this->IDuser,
            'year' => $this->year,
            'date_created' => $this->date_created,
			'result_type'=>$this->result_type,
        ]);

        $query->andFilterWhere(['like', 'pdf_file', $this->pdf_file])
            ->andFilterWhere(['like', 'place', $this->place])
            ->andFilterWhere(['like', 'distance', $this->distance])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
