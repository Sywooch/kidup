<?php

namespace api\models;
use images\components\ImageHelper;

/**
 * This is the model class for table "item".
 */
class Media extends \item\models\media\Media
{

    public function fields()
    {
        return [
            'id',
            'img' => function ($model) {
                // legacy
                return ImageHelper::urlSet($model->file_name, true);
            },
            'image' => function ($model) {
                return ImageHelper::url($model->file_name);
            },
            'img_url' => function ($model) {
                // legacy
                return ImageHelper::url($model->file_name);
            },
            'order' => function($model){
                $ihm = $model->itemHasMedia;
                if(count($ihm) > 0){
                    return $ihm[0]->order;
                }
                return false;
            }
        ];

    }
}
