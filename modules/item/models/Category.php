<?php

namespace app\modules\item\models;

use Yii;

/**
 * This is the model class for table "category".
 * @property string $translatedName
 */
class Category extends \app\models\base\Category
{
    public function getTranslatedName()
    {
        $lower = str_replace(" ", "", strtolower($this->name));
        return \Yii::$app->getI18n()->translate('item.category.main_' . $lower, $lower, [], \Yii::$app->language);
    }
}
