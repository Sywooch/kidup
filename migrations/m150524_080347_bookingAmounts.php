<?php

use yii\db\Schema;
use yii\db\Migration;

class m150524_080347_bookingAmounts extends Migration
{
    public function up()
    {
        $this->addColumn('booking', 'amount_item', 'DOUBLE');
        $this->addColumn('booking', 'amount_payin' , 'DOUBLE');
        $this->addColumn('booking', 'amount_payin_fee' , 'DOUBLE');
        $this->addColumn('booking', 'amount_payin_fee_tax' , 'DOUBLE');
        $this->addColumn('booking', 'amount_payin_costs' , 'DOUBLE');
        $this->addColumn('booking', 'amount_payout' , 'DOUBLE');
        $this->addColumn('booking', 'amount_payout_fee' , 'DOUBLE');
        $this->addColumn('booking', 'amount_payout_fee_tax' , 'DOUBLE');
        $this->addColumn('payin', 'amount' , 'DOUBLE');

        $this->dropColumn('booking', 'amount_paid');
    }

    public function down()
    {
        echo "m150524_080347_bookingAmounts cannot be reverted.\n";

        return false;
    }
}
