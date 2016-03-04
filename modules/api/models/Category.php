<?php

namespace api\models;

/**
 * This is the model class for table "item".
 */
class Category extends \item\models\category\Category
{
    public function fields()
    {
        $fields = parent::fields();

        $fields['name'] = function($model){
            /**
             * @var Category $model
             */
            return $model->getTranslatedName();
        };

        return $fields;
    }

    public function extraFields()
    {
        return ['parent'];
    }

    public function getParent(){
        return $this->hasOne(Category::className(), ['id' => 'parent_id']);
    }
}
