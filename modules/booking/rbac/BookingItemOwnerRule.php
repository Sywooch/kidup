<?php

namespace booking\rbac;

use yii\rbac\Rule;
use yii\rbac\Item;

/**
 * Checks if a user is the owner of a booking
 */
class BookingItemOwnerRule extends Rule
{
    public $name = 'isBookingItemOwner';

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
        return $user === $params['model']->getRelation('item')->getRelation('owner');
    }
}