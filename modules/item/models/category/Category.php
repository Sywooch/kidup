<?php

namespace item\models\category;

use admin\models\I18nMessage;
use Yii;

/**
 * This is the model class for table "category".
 * @property string $translatedName
 */
class Category extends CategoryBase
{
    public function getTranslatedName($lang = null)
    {
        $lower = str_replace(" ", "_", strtolower($this->name));
        if($this->parent_id === null){
            $index_name = 'item.category.main_' . $lower;
        }else{
            $index_name = 'item.category.sub_category_' . $lower;
        }

        return I18nMessage::findCustomMessage($index_name, $this->name, $lang);
    }
}

