<?php

namespace item\models;

use Yii;

/**
 * This is the model class for table "category".
 * @property string $translatedName
 */
class Category extends \item\models\base\Category
{
    public function getTranslatedName()
    {
        $lower = str_replace(" ", "_", strtolower($this->name));
        if($this->parent_id === null){
            return \Yii::$app->getI18n()->translate('item.category.main_' . $lower, $this->name, [], \Yii::$app->language);
        }else{
            return \Yii::$app->getI18n()->translate('item.category.sub_category_' . $lower, $this->name, [], \Yii::$app->language);
        }
    }
}
