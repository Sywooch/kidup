<?php

namespace item\models\wishListItem;

use Yii;

class WishListItemError extends \app\extended\base\Exception
{
}

;

/**
 * This is the model class for table "WishListItem".
 */
class WishListItem extends WishListItemBase
{
    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->created_at = time();
        }
        return parent::beforeValidate();
    }

    public function beforeDelete()
    {
        if (!$this->getUserCanModify()) {
            throw new WishListItemError("You are not allowed to modify this withlist item");
        }
        return parent::beforeDelete();
    }

    public function getUserCanModify()
    {
        if ($this->user_id == \Yii::$app->user->id) {
            return true;
        }
        if (\Yii::$app->user->identity->getIsAdmin()) {
            return true;
        }
        return false;
    }
}
