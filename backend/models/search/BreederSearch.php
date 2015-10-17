<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Breeder;
use backend\models\CountryList;
use backend\models\LastVisit;
use backend\models\Subscription;
use dektrium\user\models\User;
/**
 * BreederSearch represents the model behind the search form about `backend\models\Breeder`.
 */
class BreederSearch extends Breeder
{
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'IDuser', 'country', 'verified'], 'integer'],
            [['name_of_breeder', 'town', 'address', 'tel1', 'tel2', 'mob1', 'mob2', 'email1', 'email2', 'fax', 'website'], 'safe'],
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
		//my code to get table name by clling table schema function
		$userTable=User::getTableSchema();
		$countryTable=CountryList::getTableSchema();
		$lastVisitTable=LastVisit::getTableSchema();
		$subscriptionTable=Subscription::getTableSchema();
		$breederTable=Breeder::getTableSchema();
		
        $query = Breeder::find();
		
		//http://www.ramirezcobos.com/2014/04/16/displaying-sorting-and-filtering-model-relations-on-a-gridview-yii2/
		$query->with(['relationIDuser','relationCountry', 'relationLastVisit'/*, 'relationSubscription'*/]);
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => [
				'pageSize' => 100,
			],
        ]);
		
		$dataProvider->sort->attributes['IDuser']=
		[
			'asc'=>[$userTable->name.'.username'=>SORT_ASC],
			'desc'=>[$userTable->name.'.username'=>SORT_DESC],
		];
		
		$dataProvider->sort->attributes['country']=
		[
			'asc'=>[$countryTable->name.'.country_name'=>SORT_ASC],
			'desc'=>[$countryTable->name.'.country_name'=>SORT_DESC],
		];

		//From LastVisit table
		$dataProvider->sort->attributes['last_visit']=
		[
			'asc'=>[$lastVisitTable->name.'.last_visit'=>SORT_ASC],
			'desc'=>[$lastVisitTable->name.'.last_visit'=>SORT_DESC],
		];

		//from Subcription table
		$dataProvider->sort->attributes['end_date']=
		[
			'asc'=>[$subscriptionTable->name.'.end_date'=>SORT_ASC],
			'desc'=>[$subscriptionTable->name.'.end_date'=>SORT_DESC],
		];


        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            $breederTable->name.'.IDuser' => $this->IDuser,
            'country' => $this->country,
            'verified' => $this->verified,
        ]);

        $query->andFilterWhere(['like', 'name_of_breeder', $this->name_of_breeder])
            ->andFilterWhere(['like', 'town', $this->town])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'tel1', $this->tel1])
            ->andFilterWhere(['like', 'tel2', $this->tel2])
            ->andFilterWhere(['like', 'mob1', $this->mob1])
            ->andFilterWhere(['like', 'mob2', $this->mob2])
            ->andFilterWhere(['like', 'email1', $this->email1])
            ->andFilterWhere(['like', 'email2', $this->email2])
            ->andFilterWhere(['like', 'fax', $this->fax])
            ->andFilterWhere(['like', 'website', $this->website])
			;

        return $dataProvider;
    }
}
