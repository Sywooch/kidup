<?php

namespace item\models;

use Yii;

/**
 * This is the model class for table "category".
 * @property string $translatedName
 */
class Category extends \item\models\base\Category
{
    public function getTranslatedName($lang = null)
    {
        if($lang == null){
            $lang = \Yii::$app->language;
        }
        $lower = str_replace(" ", "_", strtolower($this->name));
        if($this->parent_id === null){
            return \Yii::$app->getI18n()->translate('item.category.main_' . $lower, $this->name, [], $lang);
        }else{
            return \Yii::$app->getI18n()->translate('item.category.sub_category_' . $lower, $this->name, [], $lang);
        }
    }
}
