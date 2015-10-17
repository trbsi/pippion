<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\FoundPigeons;
use backend\models\PigeonCountry;
use backend\models\CountryList;
use backend\helpers\ExtraFunctions;

/**
 * FoundPigeonsSearch represents the model behind the search form about `backend\models\FoundPigeons`.
 */
class FoundPigeonsSearch extends FoundPigeons
{
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'IDcountry', 'IDuser', 'country'], 'integer'],
            [['pigeonnumber', 'sex', 'year', 'city', 'address', 'zip', 'image_file', 'date_created'], 'safe'],
            [['returned'], 'boolean'],
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
	 * $public - it is public search or not. Sent from actionPublic
     */
    public function search($params, $public=NULL)
    {
		$tablePigeonCountry = PigeonCountry::getTableSchema();
		$tableCountryList = CountryList::getTableSchema();
		$ExtraFunctions = new ExtraFunctions;
		
        $query = FoundPigeons::find();
		$query->joinWith(['relationPigeonIDcountry', 'relationRealIDcountry', 'relationIDuser']);
		$query->orderBy('returned ASC, date_created DESC');	
		//actionIndex
		if($public==NULL)
		{
			$query->where(['IDuser'=>Yii::$app->user->getId()]);
		}
		//actionPublic
		else
		{
		}
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

		$dataProvider->sort->attributes['relationPigeonIDcountry'] = 
		[
			'asc' => [$tablePigeonCountry->name.'.country' => SORT_ASC],
			'desc' => [$tablePigeonCountry->name.'.country'  => SORT_DESC],
		];
	
		$dataProvider->sort->attributes['relationRealIDcountry'] = 
		[
			'asc' => [$tableCountryList->name.'.country_name' => SORT_ASC],
			'desc' => [$tableCountryList->name.'.country_name' => SORT_DESC],
		];
	
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            //'ID' => $this->ID,
            'IDcountry' => $this->IDcountry,
            'year' => $this->year,
            //'IDuser' => $this->IDuser,
            'country' => $this->country,
            'zip' => $this->zip,
            'returned' => $this->returned,
        ]);

        $query->andFilterWhere(['like', 'pigeonnumber', $this->pigeonnumber])
            ->andFilterWhere(['like', 'sex', $this->sex])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'address', $this->address]);
           // ->andFilterWhere(['like', 'image_file', $this->image_file]);
		   
		//because there is mask for that date field, mask created by js (.js-datepicker)
		if($this->date_created!="____-__-__")
           $query->andFilterWhere(['between', 'date_created', $ExtraFunctions->between_0_24($this->date_created, "beginning"), $ExtraFunctions->between_0_24($this->date_created, "end")]);

        return $dataProvider;
    }
}
