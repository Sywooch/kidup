<?php

namespace app\models;

use app\helpers\Event;
use Carbon\Carbon;
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
    public static function findOneOr404($params)
    {
        $res = parent::findOne($params);
        if (count($res) == 0) {

            throw new NotFoundHttpException("We could not find '".self::friendlyClassName() ."' with the specified parameters");
        }
        return $res;
    }

    public static function friendlyClassName(){
        $expl = explode("\\", parent::className());
        return $expl[count($expl)-1];
    }

    /**
     * Simply here for the sake of autocompleting the return type
     * @return static|null
     */
    public static function findOne($condition)
    {
        return parent::findOne($condition);
    }


    public static function find()
    {
        return new BaseQuery(get_called_class());
    }

    // Overwrite event hooks

    public function beforeSave($insert)
    {
        Event::trigger(__CLASS__,
            $insert ? \yii\db\ActiveRecord::EVENT_BEFORE_INSERT : \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE);
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        Event::trigger(__CLASS__,
            $insert ? \yii\db\ActiveRecord::EVENT_AFTER_INSERT : \yii\db\ActiveRecord::EVENT_AFTER_UPDATE);
        parent::afterSave($insert, $changedAttributes);
    }

    public function beforeDelete()
    {
        Event::trigger(__CLASS__, \yii\db\ActiveRecord::EVENT_BEFORE_DELETE);
        return parent::beforeDelete();
    }

    public function afterDelete()
    {
        Event::trigger(__CLASS__, \yii\db\ActiveRecord::EVENT_AFTER_DELETE);
        return parent::afterDelete();
    }

    public function beforeValidate()
    {
        if ($this->hasAttribute('created_at')) {
            if ($this->getAttribute('created_at') === null) {
                $this->setAttribute('created_at', Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp);
            }
        }

        if ($this->hasAttribute('updated_at')) {
            $this->setAttribute('updated_at', Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp);
        }
        return parent::beforeValidate();
    }

}
