<?php

namespace app\modules\admin\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\base\Item as ItemModel;

/**
 * Item represents the model behind the search form about `app\models\base\Item`.
 */
class Item extends ItemModel
{
    public function rules()
    {
        return [
            [['id', 'price_day', 'price_week', 'price_month', 'owner_id', 'currency_id', 'is_available', 'location_id', 'created_at', 'updated_at', 'min_renting_days', 'category_id'], 'integer'],
            [['name', 'description'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ItemModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'price_day' => $this->price_day,
            'price_week' => $this->price_week,
            'price_month' => $this->price_month,
            'owner_id' => $this->owner_id,
            'currency_id' => $this->currency_id,
            'is_available' => $this->is_available,
            'location_id' => $this->location_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'min_renting_days' => $this->min_renting_days,
            'category_id' => $this->category_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
