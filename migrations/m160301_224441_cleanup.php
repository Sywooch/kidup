<?php

use yii\db\Migration;

class m160301_224441_cleanup extends Migration
{
    public function up()
    {
        $this->dropTable("item_search");
        $this->dropTable("ip_location");
        $this->dropColumn("payout_method", "address");
        $this->dropColumn("payout_method", "bank_name");
        $this->dropColumn("booking", "promotion_code_id");
        $this->dropColumn("invoice", "status");
        $this->dropColumn("item", "min_renting_days");
        $this->dropColumn("media", "storage");
        $this->dropColumn("media", "type");
        $this->dropColumn("media", "description");
        $this->dropColumn("oauth_access_token", "scope");
        $this->dropColumn("oauth_refresh_token", "scope");
        $this->dropColumn("payin", "data");
        $this->dropColumn("payin", "type");
    }

    public function down()
    {
        echo "m160301_224441_cleanup cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
