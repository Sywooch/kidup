<?php
namespace app\models;
use yii\db\ActiveQuery;
/**
 * This is the model class for table "item".
 */
class BaseQuery extends ActiveQuery
{
    public function orderRandomly()
    {
        return $this->orderBy('RAND()');
    }
}