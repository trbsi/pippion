<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\RacingTableCategory;

/**
 * RacingTableCategorySearch represents the model behind the search form about `backend\models\RacingTableCategory`.
 */
class RacingTableCategorySearch extends RacingTableCategory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'IDuser'], 'integer'],
            [['category'], 'safe'],
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
        $query = RacingTableCategory::find();
		$query->where(['IDuser'=>Yii::$app->user->getId()]);
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

      /*  $query->andFilterWhere([
            'ID' => $this->ID,
            'IDuser' => $this->IDuser,
        ]);*/

        $query->andFilterWhere(['like', 'category', $this->category]);

        return $dataProvider;
    }
}
