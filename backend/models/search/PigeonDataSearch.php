<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PigeonData;
use backend\models\Pigeon;
use backend\helpers\ExtraFunctions;

/**
 * PigeonDataSearch represents the model behind the search form about `backend\models\PigeonData`.
 */
class PigeonDataSearch extends PigeonData
{
	public $pigeonnumber_search;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'IDuser', 'IDpigeon'], 'integer'],
            [['pigeondata', 'year', 'date_created', 'pigeonnumber_search'], 'safe'],
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
		$pigeonTable=Pigeon::getTableSchema();
		$pigeonDataTable=PigeonData::getTableSchema();
		
        $query = PigeonData::find();
		$query->where([$pigeonDataTable->name.'.IDuser'=>Yii::$app->user->getId()]);
		$query->joinWith(['relationIDpigeon']);
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination'=>
			[
				'pageSize'=>50,
			],
			'sort'=>['defaultOrder'=>['date_created'=>SORT_DESC]],
        ]);
		
		$dataProvider->sort->attributes['pigeonnumber_search']=[
			'asc'=>[$pigeonTable->name.".pigeonnumber"=>SORT_ASC],
			'desc'=>[$pigeonTable->name.".pigeonnumber"=>SORT_DESC],
		];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            //'ID' => $this->ID,
            //'IDuser' => $this->IDuser,
            //'IDpigeon' => $this->IDpigeon,
            $pigeonDataTable->name.'.year' => $this->year,
            //$pigeonDataTable->name.'.date_created' => $this->date_created,
        ]);

        $query->andFilterWhere(['like', 'pigeondata', $this->pigeondata]);
        $query->andFilterWhere(['like', $pigeonTable->name.'.pigeonnumber', $this->pigeonnumber_search]);
        $query->andFilterWhere(['between', $pigeonDataTable->name.'.date_created', $ExtraFunctions->between_0_24($this->date_created, "beginning"), $ExtraFunctions->between_0_24($this->date_created, "end")]);

        return $dataProvider;
    }
}
