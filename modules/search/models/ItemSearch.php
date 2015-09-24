<?php
namespace app\modules\search\models;

use app\models\base\Category;
use app\models\base\Feature;
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
        $params[':q'] = $input[count($input) - 1] . "*";
        $query->where("MATCH(text) AGAINST(:q IN BOOLEAN MODE)");
        $query->andWhere(['IN', 'component_type', ['sub-cat', 'main-cat']]);
        $query->params($params);
        $query->andWhere(['language_id' => \Yii::$app->language]);

        $suggestions = $query->groupBy('text')->orderBy('score DESC')->limit(10)->all();
        $res = [];
        foreach ($suggestions as $suggestion) {
            if (strpos(implode(" ", $input), $suggestion->text) !== false) {
                continue;
            }
            if(strlen(implode(" ", array_slice($input, 1, count($input) - 1))) > 0){
                $res[] = ['text' => implode(" ", array_slice($input, 1, count($input) - 1)) . " " . $suggestion->text];
            }else{
                $res[] = ['text' => $suggestion->text];
            }
        }
        return $res;

    }

    private function tokenizeInput($in)
    {
        $in = htmlspecialchars_decode($in);
        return explode(" ", $in);
    }

    /**
     * @param Item $item
     */
    public static function updateSearch()
    {
        ItemSearch::deleteAll();

        $categories = Category::find()->all();
        foreach ($categories as $cat) {
            if ($cat->itemCount > 0 && $cat->parent_id !== null) {
                (new self())->make(self::COMPONENT_SUB_CATEGORY, $cat->id, $cat->name);
            } else {
                if ($cat->parent_id === null) {
                    (new self())->make(self::COMPONENT_MAIN_CATEGORY, $cat->id, $cat->name);
                }
            }
        }

        // still to do here: features
    }

    /**
     * Makes a single reference (helper function)
     * @param string $component
     * @param int $componentId
     * @param string $text
     * @param Language $language
     */
    private function make($component, $componentId, $text)
    {
        $langs = Language::find()->all();
        foreach ($langs as $lang) {
            $is = new ItemSearch();
            $is->component_type = $component;
            $is->component_id = $componentId;
            $is->text = self::t($text, $lang->language_id);
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