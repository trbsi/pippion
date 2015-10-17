<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use backend\models\Pigeon;
use backend\models\CoupleRacing;
use backend\models\CoupleBreeding;
use backend\models\BroodRacing;
use backend\models\BroodBreeding;

/**
 * CoupleSearch represents the model behind the search form about `backend\models\CoupleRacing`.
 */
class CoupleSearch extends CoupleRacing
{
	
	private $_MODEL;
	private $_MODEL_CHOOSE;
	
	public $search_male;
	public $search_female;
	
	public function __construct($model)
	{
		if($model=="CoupleRacing")
		{
			$this->_MODEL = new CoupleRacing;
			$this->_MODEL_CHOOSE = "CoupleRacing";
		}
		else if($model=="CoupleBreeding")
		{
			$this->_MODEL = new CoupleBreeding;
			$this->_MODEL_CHOOSE = "CoupleBreeding";
		}
		else
		{
			$this->_MODEL = new CoupleRacing;
			$this->_MODEL_CHOOSE = "CoupleRacing";
		}
	}
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'IDuser', 'male', 'female'], 'integer'],
            [['couplenumber', 'year', 'search_female', 'search_male'], 'safe'],
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
		$pigeonTable=Pigeon::getTableSchema();
		if($this->_MODEL_CHOOSE=="CoupleRacing")
		{
			$coupleTable=CoupleRacing::getTableSchema();
			$broodTable=BroodRacing::getTableSchema();
		}
		else
		{
			$coupleTable=CoupleBreeding::getTableSchema();
			$broodTable=BroodBreeding::getTableSchema();
		}
		

        $query = $this->_MODEL->find();
		$query -> where([$coupleTable->name.'.IDuser'=>Yii::$app->user->getId()]);
		$query->with(['relationFemale', 'relationMale']);
		
		// join with table alias

		/*$query->joinWith(
		[
			'relationMale'=> function ($query) use ($pigeonTable)
			{
				$query->from(['t1'=>$pigeonTable->name]);
			},
			'relationFemale'=> function ($query)  use ($pigeonTable)
			{
				$query->from(['t2'=>$pigeonTable->name]);
			}
		]);*/
		 
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		
		$dataProvider->sort->defaultOrder=['couplenumber'=>SORT_ASC];
		$dataProvider->sort->attributes['search_male']=[
			'asc'=>['t1.pigeonnumber'=>SORT_ASC],
			'desc'=>['t1.pigeonnumber'=>SORT_DESC],
		];
		$dataProvider->sort->attributes['search_female']=[
			'asc'=>['t2.pigeonnumber'=>SORT_ASC],
			'desc'=>['t2.pigeonnumber'=>SORT_DESC],
		];
		
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
          //  'ID' => $this->ID,
          //  'IDuser' => $this->IDuser,
           // 'male' => $this->male,
           // 'female' => $this->female,
            'year' => $this->year,
        ]);

        $query->andFilterWhere(['like', 'couplenumber', $this->couplenumber]);
        $query->andFilterWhere(['like', 't1.pigeonnumber', $this->search_male]);
        $query->andFilterWhere(['like', 't2.pigeonnumber', $this->search_female]);

        return $dataProvider;
    }
}
