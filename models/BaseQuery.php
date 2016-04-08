<?php
namespace app\models;

use yii\db\ActiveQuery;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "item".
 */
class BaseQuery extends ActiveQuery
{
    public function orderRandomly()
    {
        return $this->orderBy('RAND()');
    }

    public function oneOr404($db = null)
    {
        $res = $this->one($db);
        if($res == null){
            throw new NotFoundHttpException();
        }
        return $res;
    }

    public function orderByLastUpdated(){
        return $this->orderBy('updated_at DESC');
    }

    public function orderByLastCreated(){
        return $this->orderBy('created_at DESC');
    }
}