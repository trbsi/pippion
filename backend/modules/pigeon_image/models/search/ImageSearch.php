<?php

namespace backend\modules\pigeon_image\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\pigeon_image\models\Image;

/**
 * PigeonImageSearch represents the model behind the search form about `backend\modules\pigeon_image\models\PigeonImage`.
 */
class ImageSearch extends Image
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'IDuser', 'image_file', 'IDalbum'], 'integer'],
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
    public function search($params, $IDalbum)
    {
        $query = Image::find();
		$query->where(['IDalbum'=>$IDalbum]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=>['defaultOrder'=>['ID'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
           // 'ID' => $this->ID,
            //'IDuser' => $this->IDuser,
            'image_file' => $this->image_file,
            'IDalbum' => $this->IDalbum,
        ]);

        return $dataProvider;
    }
}
