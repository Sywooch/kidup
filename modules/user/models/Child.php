<?php

namespace user\models;

use Carbon\Carbon;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "child".
 */
class Child extends base\Child
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => function () {
                    return Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp;
                }
            ],
        ];
    }
}
