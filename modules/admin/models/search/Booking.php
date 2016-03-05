<?php

namespace admin\models\search;

use booking\models\booking\Booking as BookingModel;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Booking represents the model behind the search form about `\booking\models\booking\Booking`.
 */
class Booking extends BookingModel
{
    public function rules()
    {
        return [
            [['id', 'item_id', 'renter_id', 'currency_id', 'time_from', 'time_to', 'updated_at', 'created_at', 'payin_id', 'payout_id', 'request_expires_at'], 'integer'],
            [['status', 'refund_status', 'item_backup', 'amount_item', 'amount_payin', 'amount_payin_fee', 'amount_payin_fee_tax', 'amount_payin_costs', 'amount_payout', 'amount_payout_fee', 'amount_payout_fee_tax', 'promotion_code_id'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = BookingModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'item_id' => $this->item_id,
            'renter_id' => $this->renter_id,
            'currency_id' => $this->currency_id,
            'time_from' => $this->time_from,
            'time_to' => $this->time_to,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'payin_id' => $this->payin_id,
            'payout_id' => $this->payout_id,
            'request_expires_at' => $this->request_expires_at,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'refund_status', $this->refund_status])
            ->andFilterWhere(['like', 'item_backup', $this->item_backup])
            ->andFilterWhere(['like', 'amount_item', $this->amount_item])
            ->andFilterWhere(['like', 'amount_payin', $this->amount_payin])
            ->andFilterWhere(['like', 'amount_payin_fee', $this->amount_payin_fee])
            ->andFilterWhere(['like', 'amount_payin_fee_tax', $this->amount_payin_fee_tax])
            ->andFilterWhere(['like', 'amount_payin_costs', $this->amount_payin_costs])
            ->andFilterWhere(['like', 'amount_payout', $this->amount_payout])
            ->andFilterWhere(['like', 'amount_payout_fee', $this->amount_payout_fee])
            ->andFilterWhere(['like', 'amount_payout_fee_tax', $this->amount_payout_fee_tax])
            ->andFilterWhere(['like', 'promotion_code_id', $this->promotion_code_id]);

        return $dataProvider;
    }
}
