<?php

namespace booking\models;

use app\helpers\Encrypter;
use app\helpers\Event;
use Carbon\Carbon;
use user\models\PayoutMethod;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "payin".
 */
class Payout extends \booking\models\base\Payout
{
    const STATUS_WAITING_FOR_BOOKING_START = 'waiting_for_start';
    const STATUS_TO_BE_PROCESSED = 'to_be_processed';
    const STATUS_PROCESSED = 'processed';
    const STATUS_CANCELLED = 'cancelled';

    const EVENT_PAYOUT_PROCESSED = 'event_payout_processed';

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => function () {
                    return Carbon::now(\Yii::$app->params['serverTimeZone'])->timestamp;
                }
            ],
        ];
    }

    public function createFromBooking(Booking $booking)
    {
        $payout = new Payout();
        $payout->setAttributes([
            'status' => self::STATUS_WAITING_FOR_BOOKING_START,
            'amount' => $booking->amount_payout,
            'currency_id' => 1,
            'user_id' => $booking->item->owner_id,
            'created_at' => time(),
        ]);
        $payout->save();
        $booking->payout_id = $payout->id;
        return $booking->save();
    }

    public function markAsProcessed()
    {
        $this->status = self::STATUS_PROCESSED;
        $this->processed_at = time();
        if ($this->save()) {
            Event::trigger($this, self::EVENT_PAYOUT_PROCESSED);
            return true;
        } else {
            Yii::error("Payout markAsProcessed error");
            return false;
        }
    }

    public function exportDankseBank($key)
    {
        if ($this->status !== self::STATUS_TO_BE_PROCESSED) {
            return false;
        }
        // http://www.danskebank.com/en-uk/ci/Products-Services/Transaction-Services/Online-Services/Integration-Services/Documents/Formats/FormatDescriptionCSV_DK_LocalBulk/CSV_DK_LocalBulk.pdf
        $field = [];

        $field[1] = 'CMBO';
        $field[2] = '11658814'; // kidup account
        $payoutMethod = PayoutMethod::find()->where(['user_id' => $this->user_id])->one();
        if ($payoutMethod === null) {
            // todo: what here?
            return false;
        }
        // 4 number bank identifier + bank account number (no spaces in between)
        $field[3] = Encrypter::decrypt($payoutMethod->identifier_2_encrypted, $key).Encrypter::decrypt($payoutMethod->identifier_1_encrypted,$key);
        $field[4] = str_replace(".", ",", $this->amount); // to account
        $field[6] = "DKK";
        $field[7] = "N"; // todo check
        $field[13] = "J"; // todo check

        $field[24] = \Yii::t('payout.payout_identification', 'KidUp Payout {payoutId}', [
            'payoutId' => (string)$this->id
        ]);
        $field[83] = \Yii::t('payout.bank_message_receiver', 'Thanks for using KidUp');
        $str = [];
        for ($i = 1; $i <= 86; $i++) {
            if (!isset($field[$i])) {
                $str[] = '';
            } else {
                $str[] = $field[$i];
            }
        }

        $res = "\"" . implode('","', $str) . '"';
        return $res;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooking()
    {
        return $this->hasOne(Booking::className(), ['payin_id' => 'id']);
    }
}
