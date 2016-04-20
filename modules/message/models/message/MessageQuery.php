<?php
namespace message\models\message;

use app\components\models\BaseQuery;
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