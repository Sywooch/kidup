<?php

namespace admin\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use mail\models\base\MailTemplate as MailTemplateModel;

/**
 * MailTemplate represents the model behind the search form about `mail\models\base\MailTemplate`.
 */
class MailTemplate extends MailTemplateModel
{
    public function rules()
    {
        return [
            [['id', 'revision', 'created_at', 'updated_at'], 'integer'],
            [['template'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = MailTemplateModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'revision' => $this->revision,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'template', $this->template]);

        return $dataProvider;
    }
}
