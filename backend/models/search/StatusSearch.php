<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Status;

/**
 * StatusSearch represents the model behind the search form about `backend\models\Status`.
 */
class StatusSearch extends Status
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'IDuser', 'frompedigree'], 'integer'],
            [['status'], 'safe'],
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
        $query = Status::find();
		$query->where(['IDuser'=>Yii::$app->user->getId()]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination'=>[
				'pageSize'=>25,
			],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            //'IDuser' => $this->IDuser,
            'frompedigree' => $this->frompedigree,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
