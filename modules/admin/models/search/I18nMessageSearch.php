<?php
namespace admin\models\search;
use Yii;
use yii\data\ActiveDataProvider;
use admin\models\I18nMessage;
use admin\models\I18nSource;

class I18nMessageSearch extends I18nMessage
{
    const MESSAGE_TRANSLATED_YES = 'yes';
    const MESSAGE_TRANSLATED_NO  = 'no';
    public $category;
    public $source;
    public $translationStatus;
    public $prefix;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category', 'source', 'language', 'translation', 'translationStatus'], 'safe'],
            ['prefix', 'in', 'range' => ['cabinet', 'shop', 'brand', 'bonus']]
        ];
    }
    /**
     * @inheritdoc
     */
    public function search($params)
    {
        $query = I18nMessage::find()->joinWith(['i18nSource' => function($query) {
            $query->from(['i18nSource' => i18nSource::tableName()]);
        }]);
        $dataProvider = new ActiveDataProvider(['query' => $query]);
        if($this->language)
            $query->filterWhere(['language' => $this->language]);
        if ($this->prefix) {
            $query->andFilterWhere([
                'or',
                ['like', 'i18nSource.category', $this->prefix . '.'],
                ['like', 'i18nSource.category', $this->prefix . '/'],
            ]);
        }
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query
            ->andFilterWhere(['like', 'translation', $this->translation])
            ->andFilterWhere(['like', 'i18nSource.message', $this->source]);
        if ($this->category) {
            if ($this->prefix) {
                $query->andFilterWhere([
                    'or',
                    ['like', 'i18nSource.category', $this->prefix . '.' . $this->category],
                    ['like', 'i18nSource.category', $this->prefix . '/' . $this->category],
                ]);
            } else {
                $query->andFilterWhere(['like', 'i18nSource.category', $this->category]);
            }
        }
        if($this->translationStatus === static::MESSAGE_TRANSLATED_YES)
            $query->andWhere('translation IS NOT NULL AND translation <> ""');
        elseif($this->translationStatus === static::MESSAGE_TRANSLATED_NO)
            $query->andWhere('translation IS NULL OR translation = ""');
        return $dataProvider;
    }
}