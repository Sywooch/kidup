<?php

namespace item\models;

use admin\models\I18nMessage;
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
            $index_name = 'item.category.main_' . $lower;
        }else{
            $index_name = 'item.category.sub_category_' . $lower;
        }

        $i18n = I18nMessage::find()->where([
            'i18n_source.category' => $index_name,
            'i18n_source.message' => $this->name,
            'language' => $lang
        ])->innerJoinWith('i18nSource')->one();
        if($i18n == null || $i18n->translation == null){
            $i18n = \Yii::$app->getI18n()->translate($index_name, $this->name, [], $lang);
        }else{
            $i18n = $i18n->translation;
        }
        return $i18n;
    }
}
