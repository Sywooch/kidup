<?php
namespace app\modules\search\models;

use app\models\base\Item;
use app\models\base\Language;
use yii\web\BadRequestHttpException;

/**
 * Class ItemSearch
 * @package app\modules\search\models
 * @author kevin91nl
 */
class ItemSearch extends \app\models\base\ItemSearch
{
    const COMPONENT_MAIN_CATEGORY = 'main-cat';
    const COMPONENT_SUB_CATEGORY = 'sub-cat';
    const COMPONENT_FEATURE = 'feature';

    /**
     * Returns suggestions for autocomplete
     * @param string $input
     * @throws BadRequestHttpException
     */
    public function getSuggestions($input)
    {
        if (!is_string($input)) {
            throw new BadRequestHttpException("Input should be a string");
        }

        $input = $this->tokenizeInput($input);
        $query = ItemSearch::find()->select("*, SUM(MATCH(text) AGAINST(:q IN BOOLEAN MODE)) as score");
        $params[':q'] = '';
        foreach ($input as $i) {
            $params[':q'] .= $i . "*"; // adds a *, so there is partial wordmatch (from start only though)
        }
        $query->where("MATCH(text) AGAINST(:q IN BOOLEAN MODE)");
        $query->params($params);
        $query->andWhere(['language_id' => \Yii::$app->language]);

        return $query->groupBy('component_type')->orderBy('score DESC')->limit(10)->all();
    }

    private function tokenizeInput($in)
    {
        $in = htmlspecialchars_decode($in);
        return explode(" ", $in);
    }

    /**
     * Updates / creates all search references for an item
     * @param Item $item
     */
    public static function updateSearch(Item $item)
    {
        self::removeFromSearch($item);

        (new self())->make(self::COMPONENT_MAIN_CATEGORY, $item->category->parent->id, $item->category->parent->name,
            $item);
        (new self())->make(self::COMPONENT_SUB_CATEGORY, $item->category->id, $item->category->name, $item);
        foreach ($item->singularFeatures as $singular) {
            (new self())->make(self::COMPONENT_FEATURE, $singular->id, $singular->name, $item);
        }
        foreach ($item->itemHasFeatures as $ihf) {
            (new self())->make(self::COMPONENT_FEATURE, $ihf->feature_id, $ihf->featureValue->name, $item);
        }
    }

    /**
     * Removes all search references for an item
     * @param Item $item
     * @return int
     */
    public static function removeFromSearch(Item $item)
    {
        return ItemSearch::deleteAll(['item_id' => $item->id]);
    }

    /**
     * Makes a single reference (helper function)
     * @param string $component
     * @param int $componentId
     * @param string $text
     * @param Item $item
     * @param Language $language
     */
    private function make($component, $componentId, $text, Item $item)
    {
        $langs = Language::find()->all();
        foreach ($langs as $lang) {
            $is = new ItemSearch();
            $is->component_type = $component;
            $is->component_id = $componentId;
            $is->text = self::t($text, $lang->language_id);
            $is->item_id = $item->id;
            $is->language_id = $lang->language_id;
            $is->save();
        }
    }

    public static function t($name, $lang = null)
    {
        if ($lang == null) {
            $lang = \Yii::$app->language;
        }

        $curLang = \Yii::$app->language;
        \Yii::$app->language = $lang;
        $translation = \Yii::t("categories_and_features", $name);
        \Yii::$app->language = $curLang;
        return $translation;
    }
}