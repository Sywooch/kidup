<?php

namespace booking\models\base;

use booking\models\Payin;
use booking\models\Payout;
use item\models\Item;
use message\models\Conversation;
use review\models\Review;
use user\models\base\Currency;
use user\models\User;
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
 * @property Item $item
 * @property Payin $payin
 * @property Payout $payout
 * @property User $renter
 * @property Review[] $reviews
 * @property Conversation $conversation
 */
class Booking extends \yii\db\ActiveRecord
{
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
                ['status', 'item_id', 'time_from', 'time_to', 'updated_at', 'created_at', 'request_expires_at'],
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
            [['promotion_code_id'], 'string', 'max' => 255],
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
                ['renter_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => User::className(),
                'targetAttribute' => ['renter_id' => 'id']
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
            'promotion_code_id' => Yii::t('booking.attributes.promotion_code', 'Promotion Code'),
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
