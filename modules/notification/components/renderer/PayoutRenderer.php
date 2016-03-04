<?php
namespace notification\components\renderer;

use booking\models\base\Payout;
use notification\components\Renderer;

class PayoutRenderer
{

    /**
     * Load all payout render variables.
     *
     * @param Payout $payout The payout.
     * @return array All the render variables.
     */
    public function loadPayout(Payout $payout) {
        $result = [];

        // Payout
        $result['total_payout_amount'] = $payout->amount;

        return $result;
    }

}