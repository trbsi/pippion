<?php

namespace backend\modules\messages\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\messages\models\Messages;

/**
 * MessagesSearch represents the model behind the search form about `backend\modules\messages\models\Messages`.
 */
class MessagesSearch extends Messages
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'user_one', 'user_two', 'user_one_read', 'user_two_read'], 'integer'],
            [['last_updated'], 'safe'],
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
        $query = Messages::returnUndeletedMessages();
		$query->with(['relationUserTwo', 'relationUserOne']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=>['defaultOrder'=>['last_updated'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'user_one' => $this->user_one,
            'user_two' => $this->user_two,
            'user_one_read' => $this->user_one_read,
            'user_two_read' => $this->user_two_read,
        ]);

        return $dataProvider;
    }
}
