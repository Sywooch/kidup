<?php

namespace app\modules\item\models;

use Yii;

/**
 * This is the model class for table "category".
 */
class Category extends \app\models\base\Category
{
    const TYPE_MAIN = 'main';
    const TYPE_SPECIAL = 'special';
    const TYPE_AGE = 'age';

    /**
     * This is going to need a more structured approach in the future
     * @return array
     */
    public function translations()
    {
        return [
            \Yii::t('categories', 'name'),
            \Yii::t('categories', '< 6 months'),
            \Yii::t('categories', '6-12 months'),
            \Yii::t('categories', '1-2 years'),
            \Yii::t('categories', '2-4 years'),
            \Yii::t('categories', '4-6 years'),
            \Yii::t('categories', '6-9 years'),
            \Yii::t('categories', '9+ years'),
            \Yii::t('categories', 'Furniture'),
            \Yii::t('categories', 'Safety Seats'),
            \Yii::t('categories', 'Toys'),
            \Yii::t('categories', 'Strollers'),
            \Yii::t('categories', 'Safety'),
            \Yii::t('categories', 'Parents'),
            \Yii::t('categories', 'Cribs & Beds'),
            \Yii::t('categories', 'Sports & Games'),
            \Yii::t('categories', 'Travelling'),
            \Yii::t('categories', 'Electronic'),
            \Yii::t('categories', 'Bikes'),
            \Yii::t('categories', 'Packages'),
            \Yii::t('categories', 'Outdoor'),
            \Yii::t('categories', 'Clothing'),
            \Yii::t('categories', 'Other'),
            \Yii::t('categories', 'Pet-free home')
        ];
    }
}
