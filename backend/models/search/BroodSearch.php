<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\BroodBreeding;
use backend\models\BroodRacing;
use backend\models\CoupleRacing;
use backend\models\CoupleBreeding;
use backend\helpers\ExtraFunctions;
/**
 * BroodBreedingSearch represents the model behind the search form about `backend\models\BroodBreeding`.
 */
class BroodSearch extends BroodRacing
{
	private $_MODEL;
	private $_MODEL_CHOOSE;
	public $search_couple;
	public $search_year;
	
	public function __construct($model) 
	{
		if($model=="BroodRacing")
		{
			$this->_MODEL = new BroodRacing;
			$this->_MODEL_CHOOSE=$model;
		}
		else if($model=="BroodBreeding")
		{
			$this->_MODEL = new BroodBreeding;
			$this->_MODEL_CHOOSE=$model;
		}
	}
   
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'IDuser', 'IDcouple', 'IDcountry'], 'integer'],
            [['IDD', 'firstegg', 'hatchingdate', 'ringnumber', 'color', 'search_couple', 'search_year'], 'safe'],
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
		if($this->_MODEL_CHOOSE=="BroodRacing")
		{
			$coupleTable=CoupleRacing::getTableSchema();
			$broodTable=BroodRacing::getTableSchema();
		}
		else
		{
			$coupleTable=CoupleBreeding::getTableSchema();
			$broodTable=BroodBreeding::getTableSchema();
		}
		$ExtraFunctions = new ExtraFunctions;
		
        $query = $this->_MODEL->find();
		$query->where([$broodTable->name.'.IDuser'=>Yii::$app->user->getId()]);
		$query->andWhere('ringnumber > :c1', [':c1'=>'""']);//don't show young pigeons who don't have ring number
		$query->with(['relationIDcountry']);
		
		if($this->_MODEL_CHOOSE=="BroodRacing")
			$query->joinWith(['relationIDcoupleRacing', 'relationIDbrood_racing']);
		else
			$query->joinWith(['relationIDcoupleBreeding', 'relationIDbrood_breeding']);
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination'=>
			[
				'pageSize'=>50,
			],
        ]);
		
		$dataProvider->sort->defaultOrder = ['IDcountry' => SORT_ASC, 'ringnumber'=>SORT_ASC];
		$dataProvider->sort->attributes['search_couple']=
		[
			'asc'=>[$coupleTable->name.'.couplenumber'=>SORT_ASC],
			'desc'=>[$coupleTable->name.'.couplenumber'=>SORT_DESC],
		];
		
		$dataProvider->sort->attributes['search_year']=
		[
			'asc'=>[$broodTable->name.'.hatchingdate'=>SORT_ASC],
			'desc'=>[$broodTable->name.'.hatchingdate'=>SORT_DESC],
		];
		
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'IDuser' => $this->IDuser,
            //'IDcouple' => $this->IDcouple,
            'firstegg' => $this->firstegg,
            'hatchingdate' => $this->hatchingdate,
            'IDcountry' => $this->IDcountry,
        ]);

        $query->andFilterWhere(['like', 'IDD', $this->IDD])
            ->andFilterWhere(['like', 'ringnumber', $this->ringnumber])
			->andFilterWhere(['like', 'color', $this->color]);

		$query->andFilterWhere(['like', $coupleTable->name.'.couplenumber', $this->search_couple]);
		
		$query->andFilterWhere(['between', $broodTable->name.'.hatchingdate', $ExtraFunctions->between_1_365($this->search_year, "beginning"), $ExtraFunctions->between_1_365($this->search_year, "end")]);

        return $dataProvider;
    }
}
