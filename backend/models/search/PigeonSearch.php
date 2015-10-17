<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Pigeon;
use backend\models\PigeonList;
use backend\models\Status;
use backend\models\PigeonCountry;
use yii\helpers\ArrayHelper;
/**
 * PigeonSearch represents the model behind the search form about `backend\models\Pigeon`.
 */
class PigeonSearch extends Pigeon
{
	//those are attributes for searching mother and father
	public $searchmother;
	public $searchfather;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'IDuser', 'IDcountry', 'IDstatus'], 'integer'],
            [['pigeonnumber', 'sex', 'color', 'breed', 'name', 'year'], 'safe'],
			[['searchmother', 'searchfather'],'safe'],//for searching mother and father
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
		$statusTable=Status::getTableSchema();
		$countryTable=PigeonCountry::getTableSchema();
		$pigeonListTable=PigeonList::getTableSchema();
		
        $query = Pigeon::find()
		->where([$pigeonTable->name.'.IDuser'=>Yii::$app->user->getId()])
		->joinWith(['relationIDcountry', 'relationIDstatus', 'relationPigeonListIDpigeon.relationIDfather', 'relationPigeonListIDpigeon.relationIDmother'])
		;
		
		//if session is false, that means: don't show pigeons with status "from pedigree", so you have to choose pigeons where status is different from pedigree status
		if($_SESSION['show_frompedigree'] == false)
		{
			//first check if user has selected any of his statuses to be status from pedigree
			$frompedigree=Status::find()->where(['frompedigree'=>1, 'IDuser'=>Yii::$app->user->getId()])->one();
			if(!empty($frompedigree))
			{
				//now don't show those pigeons who are frompedigree
				$query->andWhere("$pigeonTable->name.IDstatus <> :id", [':id'=>$frompedigree->ID]);
			}

		}
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination'=>
			[
				'pageSize'=>100,
			],
			'sort'=> ['defaultOrder' => ['IDcountry'=>SORT_ASC, 'pigeonnumber'=>SORT_ASC]]
        ]);

		//for attribute IDstatus sort it by this relationIDstatus
		$dataProvider->sort->attributes['IDstatus']=[
			'asc'=>[$statusTable->name.'.status'=>SORT_ASC],
			'desc'=>[$statusTable->name.'.status'=>SORT_DESC],
		];

		$dataProvider->sort->attributes['IDcountry']=[
			'asc'=>[$countryTable->name.'.country'=>SORT_ASC],
			'desc'=>[$countryTable->name.'.country'=>SORT_DESC],
		];

		/*$dataProvider->sort->attributes['searchfather']=[
			'asc'=>[$pigeonTable->name.'.pigeonnumber'=>SORT_ASC],
			'desc'=>[$pigeonTable->name.'.pigeonnumber'=>SORT_DESC],
		];

		$dataProvider->sort->attributes['searchmother']=[
			'asc'=>[$pigeonTable->name.'.pigeonnumber'=>SORT_ASC],
			'desc'=>[$pigeonTable->name.'.pigeonnumber'=>SORT_DESC],
		];*/

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
		
		//if user wants to search father/mother
		if(!empty($this->searchfather))
		{
			$IDpigeon=$this->getFatherMotherRows($this->searchfather, 'IDfather');
			$query->andWhere(['in', $pigeonTable->name.'.ID', $IDpigeon]); //['in', 'id', [1, 2, 3]] will generate id IN (1, 2, 3)
		}	
		if(!empty($this->searchmother))
		{
			$IDpigeon=$this->getFatherMotherRows($this->searchmother, 'IDmother');
			$query->andWhere(['in', $pigeonTable->name.'.ID', $IDpigeon]); //['in', 'id', [1, 2, 3]] will generate id IN (1, 2, 3)
		}	
		
        $query->andFilterWhere([
            //'ID' => $this->ID,
            //'IDuser' => $this->IDuser,
            'year' => $this->year,
            'IDcountry' => $this->IDcountry,
            'IDstatus' => $this->IDstatus,
        ]);
		

        $query->andFilterWhere(['like', $pigeonTable->name.'.pigeonnumber', $this->pigeonnumber])
            ->andFilterWhere(['like', 'sex', $this->sex])
            ->andFilterWhere(['like', 'color', $this->color])
            ->andFilterWhere(['like', 'breed', $this->breed])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
	
	
	/*
	* return IDs of all pigeons whose father or mother is the one user searched for
	* $tableColumn = 'IDfather' or 'IDmother'
	*/
	private function getFatherMotherRows($pigeonnumber, $tableColumn)
	{
		//get id of a father
		$ID=Pigeon::find()->where(['pigeonnumber'=>$pigeonnumber])->one();
		//now search all rows where IDfather or IDmother is equal to found ID and pick only IDpigeon
		foreach(PigeonList::find()->select('IDpigeon')->where([$tableColumn=>$ID->ID])->all() as $value)
		{
			$array[]=$value->IDpigeon;
		}
		
		
		return $array;
	}
}
