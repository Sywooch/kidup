<?php

namespace app\models\helpers;

use yii\base\Model;

class TypeAheadData extends Model
{

    public static function local()
    {
        return [
            'yyyy',
            'xxxx',
//            'zzzz' => ['value' => 'zzzz']
        ];
    }
}