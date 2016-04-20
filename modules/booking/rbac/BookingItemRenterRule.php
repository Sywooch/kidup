<?php

namespace booking\rbac;

use yii\rbac\Rule;
use yii\rbac\Item;

/**
 * Checks if a user is the owner of a booking
 */
class BookingItemRenterRule extends Rule
{
    public $name = 'isBookingItemRenter';

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
        $attribute = isset($params['attribute']) ? $params['attribute'] : 'renter_id';
        return $user && isset($params['model']) &&  $user === $params['model']->getAttribute($attribute);
    }
}