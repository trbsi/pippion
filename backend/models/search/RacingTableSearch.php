<?php

namespace backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\RacingTable;
use backend\models\Pigeon;
use backend\models\RacingTableCategory;
use backend\helpers\ExtraFunctions;

/**
 * RacingTableSearch represents the model behind the search form about `backend\models\RacingTable`.
 */
class RacingTableSearch extends RacingTable
{
	public $search_by_year;
	public $search_pigeon;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'participated_competitors', 'participated_pigeons', 'won_place', 'IDcategory', 'IDuser', 'IDpigeon'], 'integer'],
            [['racing_date', 'place_of_release', 'search_by_year', 'search_pigeon'], 'safe'],
            [['distance', 'search_by_year', 'participated_competitors', 'participated_pigeons', 'won_place'], 'number'],
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
	 * if target isset than it is actionView, otherwise it is actionIndex
	 * $target - this target is normally null in actionIndex because I need to show there everything and group, in actionView and actionDownloadPrint it is not null because I need to know what to show: just one category, one pigeon or both (category+pigeon)
	 * $pageSize - using in actionPrint because I need to print every result, so I'm seding 1000 as page size
     */
    public function search($params, $target=NULL, $pageSize=NULL)
    {
		$racingtableTable=RacingTable::getTableSchema();
		$pigeonTable=Pigeon::getTableSchema();
		$racingtablecategoryTable=RacingTableCategory::getTableSchema();
		$ExtraFunctions = new ExtraFunctions;
		
        $query = RacingTable::find();
		$query->where([$racingtableTable->name.'.IDuser'=>Yii::$app->user->getId()]);
		$query->joinWith(['relationIDpigeon', 'relationIDcategory']);
		
		//actionIndex()
		if($target==NULL)
		{
			//only group by in index, not in view
			$query->groupBy(['IDpigeon', 'IDcategory']);
		}
		//actionIndex()

		//actionView()
		if($target!=NULL)
		{
			if($target['target']=='pigeon')
			{
				$pid=(int)$target['pid'];
				$query->andWhere([$racingtableTable->name.'.IDpigeon'=>$pid]); 
			}
			else if($target['target']=='category')
			{
				$cid=(int)$target['cid'];
				$query->andWhere([$racingtableTable->name.'.IDcategory'=>$cid]); 
			}
			else
			{
				$pid=(int)$target['pid'];
				$cid=(int)$target['cid'];
				$query->andWhere([$racingtableTable->name.'.IDcategory'=>$cid, $racingtableTable->name.'.IDpigeon'=>$pid]); 
			}
		}
		//actionView()
		
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination'=>
			[
				'pageSize'=>($pageSize==NULL) ? 50 : $pageSize,
			]
        ]);
		
		
		$dataProvider->sort->attributes['search_pigeon']=
		[
			'asc'=>[$pigeonTable->name.'.pigeonnumber'=>SORT_ASC],
			'desc'=>[$pigeonTable->name.'.pigeonnumber'=>SORT_DESC],
		];
		
		$dataProvider->sort->attributes['IDcategory']=
		[
			'asc'=>[$racingtablecategoryTable->name.'.category'=>SORT_ASC],
			'desc'=>[$racingtablecategoryTable->name.'.category'=>SORT_DESC],
		];
		
		$dataProvider->sort->attributes['search_by_year']=
		[
			'asc'=>[$racingtableTable->name.'.racing_date'=>SORT_ASC],
			'desc'=>[$racingtableTable->name.'.racing_date'=>SORT_DESC],
		];
		
		//actionIndex()
		if($target==NULL)
		{
			$dataProvider->sort->defaultOrder = ['IDpigeon' => SORT_ASC];
		}
		//actionIndex()

		//actionView()
		if($target!=NULL)
		{
			$dataProvider->sort->defaultOrder = ['racing_date' => SORT_DESC];
		}
		//actionView()
		
		
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            //'ID' => $this->ID,
            'racing_date' => $this->racing_date,
            'distance' => $this->distance,
            'participated_competitors' => $this->participated_competitors,
            'participated_pigeons' => $this->participated_pigeons,
            'won_place' => $this->won_place,
            'IDcategory' => $this->IDcategory,
           // 'IDuser' => $this->IDuser,
            //'IDpigeon' => $this->IDpigeon,
        ]);

        $query->andFilterWhere(['like', 'place_of_release', $this->place_of_release]);
        $query->andFilterWhere(['like', $pigeonTable->name.'.pigeonnumber', $this->search_pigeon]);
        $query->andFilterWhere(['between', 'racing_date', $ExtraFunctions->between_1_365($this->search_by_year, "beginning"), $ExtraFunctions->between_1_365($this->search_by_year, "end")]);

        return $dataProvider;
    }
	
	

}
