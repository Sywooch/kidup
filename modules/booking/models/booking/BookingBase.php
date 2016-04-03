<?php

namespace booking\models\booking;

use booking\models\payin\Payin;
use booking\models\payout\Payout;
use item\models\item\Item;
use message\models\conversation\Conversation;
use review\models\Review;
use user\models\currency\Currency;
use user\models\user\User;
use Yii;

/**
 * This is the base-model class for table "booking".
 *
 * @property integer $id
 * @property string $status
 * @property integer $item_id
 * @property integer $renter_id
 * @property integer $currency_id
 * @property string $refund_status
 * @property integer $time_from
 * @property integer $time_to
 * @property string $item_backup
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $payin_id
 * @property integer $payout_id
 * @property double $amount_item
 * @property double $amount_payin
 * @property double $amount_payin_fee
 * @property double $amount_payin_fee_tax
 * @property double $amount_payin_costs
 * @property double $amount_payout
 * @property double $amount_payout_fee
 * @property double $amount_payout_fee_tax
 * @property integer $request_expires_at
 * @property string $promotion_code_id
 *
 * @property Currency $currency
 * @property \item\models\item\Item $item
 * @property Payin $payin
 * @property Payout $payout
 * @property User $renter
 * @property Review[] $reviews
 * @property \message\models\conversation\Conversation $conversation
 */
class BookingBase extends \app\models\BaseActiveRecord
{

    const SCENARIO_INIT = 'init';
    const SCENARIO_OWNER_RESPOND = 'owner_respond';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'booking';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                // sets the default request_expires_at values
                'request_expires_at',
                'default',
                'value' => function ($model, $attribute) {
                    $expireDate = time() + 6 * 24 * 60 * 60;
                    if ($this->time_to < $expireDate && $this->time_to > time() - 24 * 60 * 60) {
                        return $this->time_to - 24 * 60 * 60;
                    } else {
                        if ($this->time_to < $expireDate) {
                            return $this->time_to - 5 * 60; // 5 min
                        }
                    }
                    return $expireDate;
                }
            ],
            [
                [
                    'status',
                    'item_id',
                    'time_from',
                    'time_to',
                    'updated_at',
                    'created_at',
                    'renter_id',
                    'request_expires_at',
                    'amount_item',
                    'amount_payin',
                    'amount_payin_fee',
                    'amount_payin_fee_tax',
                    'amount_payin_costs',
                    'amount_payout',
                    'amount_payout_fee',
                    'amount_payout_fee_tax'
                ],
                'required'
            ],
            [
                [
                    'item_id',
                    'renter_id',
                    'currency_id',
                    'time_from',
                    'time_to',
                    'updated_at',
                    'created_at',
                    'payin_id',
                    'payout_id',
                    'request_expires_at'
                ],
                'integer'
            ],
            [['item_backup'], 'string'],
            [
                [
                    'amount_item',
                    'amount_payin',
                    'amount_payin_fee',
                    'amount_payin_fee_tax',
                    'amount_payin_costs',
                    'amount_payout',
                    'amount_payout_fee',
                    'amount_payout_fee_tax'
                ],
                'number'
            ],
            [['status'], 'string', 'max' => 50],
            [['refund_status'], 'string', 'max' => 20],
            [
                ['currency_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Currency::className(),
                'targetAttribute' => ['currency_id' => 'id']
            ],
            [
                ['item_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Item::className(),
                'targetAttribute' => ['item_id' => 'id']
            ],
            [
                ['payin_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Payin::className(),
                'targetAttribute' => ['payin_id' => 'id']
            ],
            [
                ['payout_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Payout::className(),
                'targetAttribute' => ['payout_id' => 'id']
            ],
            [
                ['renter_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['renter_id' => 'id']
            ],
            [
                // bookings should atleast last one day
                'time_to',
                function ($attribute, $params) {
                    if ($this->time_to < $this->time_from - 1 * 24 * 60 * 60) {
                        $this->addError('time_to', 'The booking should atleast span over 1 day');
                    }
                }
            ],
            [
                // bookings can only be in the future
                'time_from',
                function ($attribute, $params) {
                    if ($this->$attribute > time()) {
                        $this->addError('time_from', 'The booking can only start in the future');
                    }
                },
                'on' => [self::SCENARIO_INIT]
            ],
            [
                // bookings cannot overlap with other accepted bookings
                'time_to',
                function () {
                    $overlapping = Booking::find()
                        ->where(':from < time_to and :to > time_from and item_id = :item_id and status = :status',
                            [
                                ':from' => $this->time_from,
                                ':to' => $this->time_to,
                                ':item_id' => $this->item_id,
                                ':status' => Booking::ACCEPTED
                            ])->count();
                    if ($overlapping > 0) {
                        $this->addError('time_to', 'The item is already booked in that period');
                    }
                },
                'on' => [self::SCENARIO_INIT, self::SCENARIO_OWNER_RESPOND]
            ],

            [
                // an item can only be booked if the item is available
                'item_id',
                function () {
                    $item = Item::findOne($this->item_id);
                    if (!$item->isAvailable()) {
                        $this->addError("item_id", "This item is not available for rent");
                    }
                },
                'on' => [self::SCENARIO_INIT]
            ],
            [
                // an booking request can only be replied to if not expired
                'request_expires_at',
                function () {
                    if($this->request_expires_at < time()){
                        $this->addError("request_expires_at", "This item is not available for rent");
                    }
                },
                'on' => [self::SCENARIO_OWNER_RESPOND]
            ],
            [
                // an booking request can only be replied to if not expired
                'request_expires_at',
                function () {
                    if($this->status != Booking::PENDING){
                        $this->addError("status", "A booking can only be accepted when the status is pending");
                    }
                },
                'on' => [self::SCENARIO_OWNER_RESPOND]
            ],
            [
                // an booking request can only be replied to if not expired
                'renter_id',
                function () {
                    if($this->item->owner_id == $this->renter_id){
                        $this->addError("renter_id", "You cannot book your own item");
                    }
                },
                'on' => [self::SCENARIO_INIT]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('booking.attributes.id', 'ID'),
            'status' => Yii::t('booking.attributes.status', 'Status'),
            'item_id' => Yii::t('booking.attributes.item_id', 'Item'),
            'renter_id' => Yii::t('booking.attributes.renter_id', 'Renter'),
            'currency_id' => Yii::t('booking.attributes.currency_id', 'Currency'),
            'refund_status' => Yii::t('booking.attributes.refund_status', 'Refund Status'),
            'time_from' => Yii::t('booking.attributes.time_booking_starts', 'Time From'),
            'time_to' => Yii::t('booking.attributes.time_booking_ends', 'Time To'),
            'item_backup' => Yii::t('booking.attributes.item_backup', 'Item Backup'),
            'updated_at' => Yii::t('booking.attributes.updated_at', 'Updated At'),
            'created_at' => Yii::t('booking.attributes.created_at', 'Created At'),
            'payin_id' => Yii::t('booking.attributes.payin_id', 'Payin'),
            'payout_id' => Yii::t('booking.attributes.payout_id', 'Payout'),
            'amount_item' => Yii::t('booking.attributes.amount_for_item', 'Amount Item'),
            'amount_payin' => Yii::t('booking.attributes.amount_received_from_renters', 'Amount Payin'),
            'amount_payin_fee' => Yii::t('booking.attributes.amount_kidup_fee_payin', 'Amount Payin Fee'),
            'amount_payin_fee_tax' => Yii::t('booking.attributes.amount_payin_fee_tax', 'Amount Payin Fee Tax'),
            'amount_payin_costs' => Yii::t('booking.attributes.amount_costs_made_for_payin', 'Amount Payin Costs'),
            'amount_payout' => Yii::t('booking.attributes.amount_payout_to_owner', 'Amount Payout'),
            'amount_payout_fee' => Yii::t('booking.attributes.amount_payout_kidup_fee', 'Amount Payout Fee'),
            'amount_payout_fee_tax' => Yii::t('booking.attributes.amount_payout_fee_tex', 'Amount Payout Fee Tax'),
            'request_expires_at' => Yii::t('booking.attributes.request_expires_at', 'Request Expires At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayin()
    {
        return $this->hasOne(Payin::className(), ['id' => 'payin_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayout()
    {
        return $this->hasOne(Payout::className(), ['id' => 'payout_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRenter()
    {
        return $this->hasOne(User::className(), ['id' => 'renter_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Review::className(), ['booking_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversation()
    {
        return $this->hasOne(Conversation::className(), ['booking_id' => 'id']);
    }
}
