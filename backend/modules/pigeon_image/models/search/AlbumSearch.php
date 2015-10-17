<?php

namespace backend\modules\pigeon_image\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\pigeon_image\models\Album;
use backend\modules\club\models\Club;

/**
 * PigeonImageAlbumSearch represents the model behind the search form about `backend\modules\pigeon_image\models\PigeonImageAlbum`.
 */
class AlbumSearch extends Album
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID'], 'integer'],
            [['album'], 'safe'],
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
	 * $club_page sent from  /pigeon-image/album/view?id=20&club_page=some_club to indicate to search only albums from that club
     */
    public function search($params, $club_page=NULL)
    {
        $query = Album::find();
		$query->with(["lastAlbumPhoto"]);
		if($club_page!=NULL)
		{
			$clubModel=Club::findModel($club_page);
			$query->where(['IDclub'=>$clubModel->ID]);
		}
		else
			$query->andWhere(['IDuser'=>Yii::$app->user->getId()]);
			
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=>['defaultOrder'=>['ID'=>SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            //'ID' => $this->ID,
        ]);

        $query->andFilterWhere(['like', 'album', $this->album]);

        return $dataProvider;
    }
}
