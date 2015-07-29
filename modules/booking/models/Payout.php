<?php

namespace app\modules\booking\models;

use app\components\Event;
use app\modules\user\models\PayoutMethod;
use Carbon\Carbon;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Json;

/**
 * This is the model class for table "payin".
 */
class Payout extends \app\models\base\Payout
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

    public function createFromBooking(Booking $booking){
        $payout = new Payout();
        $payout->setAttributes([
            'status' => self::STATUS_WAITING_FOR_BOOKING_START,
            'amount' => $booking->amount_payout,
            'currency_id' => 1,
            'user_id' => $booking->item->owner_id,
            'created_at' => time(),
        ]);
        $payout->save();
        $booking->payout_id = $this->id;
        if(!$booking->save()){
            \yii\helpers\VarDumper::dump($booking,10,true); exit();
        }
        return $booking->save();
    }

    public function markAsProcessed(){
        $this->status = self::STATUS_PROCESSED;
        $this->processed_at = time();
        if($this->save()){
            Event::trigger($this, self::EVENT_PAYOUT_PROCESSED);
            return true;
        }else{
            Yii::error("Payout markAsProcessed error");
            return false;
        }
    }

    public function exportDankseBank(){
        if($this->status !== self::STATUS_TO_BE_PROCESSED) return false;
        // http://www.danskebank.com/en-uk/ci/Products-Services/Transaction-Services/Online-Services/Integration-Services/Documents/Formats/FormatDescriptionCSV_DK_LocalBulk/CSV_DK_LocalBulk.pdf
        $field = [];
        $field[1] = 'CMBOD';
        $field[2] = '1244214124'; // kidup account
        $payoutMethod = PayoutMethod::find()->where(['user_id' => $this->booking->item->owner_id])->one();
        $field[3] = $payoutMethod->identifier_2 . '+' . $payoutMethod->identifier_1; // to account
        $field[4] = $this->amount; // to account
        $field[20] = "KidUp Payout ".$this->id;

        $str = [];
        for($i = 1; $i < 76; $i++){
            if(!isset($field[$i])){
                $str[] = '';
            }else{
                $str[] = $field[$i];
            }
        }

        return '"'.implode('","', $str).'",';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooking()
    {
        return $this->hasOne(Booking::className(), ['payin_id' => 'id']);
    }
}
