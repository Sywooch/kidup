<?php
namespace search\models;

use \item\models\Category;
use item\models\base\Item;
use user\models\base\Language;
use yii\web\BadRequestHttpException;

/**
 * Class ItemSearch
 * @package \search\models
 * @author kevin91nl
 */
class ItemSearch extends base\ItemSearch
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
                (new self())->make(self::COMPONENT_SUB_CATEGORY, $cat);
            } else {
                if ($cat->parent_id === null) {
                    (new self())->make(self::COMPONENT_MAIN_CATEGORY, $cat);
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
     * @param \user\models\base\Language $language
     */
    private function make($component, Category $category)
    {
        $langs = Language::find()->all();
        foreach ($langs as $lang) {
            $is = new ItemSearch();
            $is->component_type = $component;
            $is->component_id = $category->id;
            $is->text = $category->getTranslationName;
            $is->language_id = $lang->language_id;
            $is->save();
        }
    }
}