<?php

use yii\db\Schema;
use yii\db\Migration;

class m150806_152049_encryptedBankDetails extends Migration
{
    public function up()
    {
        $this->addColumn('payout_method', 'identifier_1_encrypted', Schema::TYPE_STRING);
        $this->addColumn('payout_method', 'identifier_2_encrypted', Schema::TYPE_STRING);
    }

    public function down()
    {
        echo "m150806_152049_encryptedBankDetails cannot be reverted.\n";

        return false;
    }

}
