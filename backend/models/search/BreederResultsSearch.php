<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\BreederResults;
use backend\helpers\ExtraFunctions;

/**
 * BreederResultsSearch represents the model behind the search form about `backend\models\BreederResults`.
 */
class BreederResultsSearch extends BreederResults
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'IDuser'], 'integer'],
            [['breeder_result', 'year', 'date_created'], 'safe'],
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
		$ExtraFunctions = new ExtraFunctions;
		
        $query = BreederResults::find();
		$query->where(['IDuser' => Yii::$app->user->getId()]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => [
				'pageSize' => 50,
			],
			'sort'=>[
				'defaultOrder'=>['date_created'=>SORT_DESC]
			],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            //'IDuser' => Yii::$app->user->getId(),
            'year' => $this->year,
            //'date_created' => $this->date_created,
        ]);

        $query->andFilterWhere(['like', 'breeder_result', $this->breeder_result]);
        $query->andFilterWhere(['between', 'date_created', $ExtraFunctions->between_0_24($this->date_created, "beginning"),  $ExtraFunctions->between_0_24($this->date_created, "end")]);

        return $dataProvider;
    }
}
