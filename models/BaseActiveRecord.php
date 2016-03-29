<?php

namespace app\models;

use app\helpers\Event;
use Codeception\Lib\Interfaces\ActiveRecord;
use yii\web\NotFoundHttpException;


/**
 * This is the model class for table "item".
 */
class BaseActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * Attempts to find single item, throws a 404 if the entity is not found
     * @param $params
     * @throws NotFoundHttpException
     * @return static
     */
    public static function findOneOr404($params){
        $res = parent::findOne($params);
        if(count($res) == 0){
            throw new NotFoundHttpException();
        }
        return $res;
    }

    /**
     * Simply here for the sake of autocompleting the return type
     * @return static|null
     */
    public static function findOne($condition)
    {
        return parent::findOne($condition);
    }

    public function beforeSave($insert)
    {
        Event::trigger(__CLASS__, \yii\db\ActiveRecord::EVENT_AFTER_INSERT);
        return parent::beforeSave($insert);
    }
    
}
