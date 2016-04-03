<?php

namespace app\components\rbac;

use yii\rbac\Rule;
use yii\rbac\Item;

/**
 * Checks if authorID matches user passed via params
 */
class OwnModelRule extends Rule
{
    public $name = 'ownModelRule';

    /**
     * @param int $user
     * @param Item $item
     * @param array $params
     * - model: model to check owner
     * - attribute: attribute that will be compared to user ID
     * @return bool
     */
    public function execute($user, $item, $params)
    {
        $attribute = isset($params['attribute']) ? $params['attribute'] : 'user_id';
        return $user && isset($params['model']) &&  $user === $params['model']->getAttribute($attribute);
    }
}