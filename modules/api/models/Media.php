<?php

namespace api\models;
use images\components\ImageHelper;

/**
 * This is the model class for table "item".
 */
class Media extends \item\models\Media
{

    public function fields()
    {
        return [
            'id',
            'img' => function ($model) {
                return ImageHelper::urlSet($model->file_name, true);
            },
        ];

    }
}
