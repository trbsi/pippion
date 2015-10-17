<?php

namespace backend\modules\club\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\club\models\ClubTables;

/**
 * ClubTablesSearch represents the model behind the search form about `backend\modules\club\models\ClubTables`.
 */
class ClubTablesSearch extends ClubTables
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'IDclub', 'year', 'pdf_file'], 'integer'],
            [['description'], 'safe'],
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
	 * $result_type:
	 		const RESULT_TYPE_TEAM=0; //show team tables (poredak timova)
			const RESULT_TYPE_PIGEON=1; //show pigeon tables (poredak golubova)
     */
    public function search($params, $clubModel, $result_type)
    {
        $query = ClubTables::find();
		$query->where(["IDclub"=>$clubModel->ID, 'result_type'=>$result_type]);
		$query->with(['relationIDclub']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => ['attributes' => ['year'], 'defaultOrder'=>['year'=>SORT_DESC]]
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
            'year' => $this->year,
            'pdf_file' => $this->pdf_file,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
