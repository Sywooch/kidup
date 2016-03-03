<?php
namespace message\models;
use app\models\BaseQuery;
use Yii;
/**
 * This is the model class for table "item".
 */
class MessageQuery extends BaseQuery
{
    public function receiverUserId($id)
    {
        return $this->andWhere(['receiver_user_id' => $id]);
    }
}