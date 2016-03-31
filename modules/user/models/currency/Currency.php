<?php

namespace user\models\currency;

use user\models\user\User;
use Yii;

/**
 * This is the base-model class for table "currency".
 *
 * @property integer $id
 * @property string $name
 * @property string $abbr
 * @property string $forex_name
 *
 * @property \booking\models\booking\Booking[] $bookings
 * @property \user\models\country\Country[] $countries
 * @property \item\models\item\Item[] $items
 * @property \booking\models\payin\Payin[] $payins
 * @property \booking\models\payout\PayoutBase[] $payouts
 * @property \user\models\profile\Profile[] $profiles
 */
class Currency extends CurrencyBase
{
    /**
     * Gets the currency of a user, or the default if the user is guest or none is set
     * @param User $user
     * @return Currency|\yii\db\ActiveRecord
     */
    public static function getUserOrDefault(User $user = null)
    {
        if($user == null){
            return self::getDefault();
        }
        if (isset($user->profile)) {
            if ($user->profile->currency) {
                return $user->profile->currency;
            }
        }
        return self::getDefault();
    }

    /**
     * Returns the default currency
     * @return static
     * @throws \yii\web\NotFoundHttpException
     */
    public static function getDefault()
    {
        return Currency::find()->oneOr404();
    }
}
