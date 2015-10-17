<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ResolveIssue;

/**
 * ResolveIssueSearch represents the model behind the search form about `backend\models\ResolveIssue`.
 */
class ResolveIssueSearch extends ResolveIssue
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'IDauction'], 'integer'],
            [['date_created'], 'safe'],
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
        $query = ResolveIssue::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'IDauction' => $this->IDauction,
            'date_created' => $this->date_created,
        ]);

        return $dataProvider;
    }
}
