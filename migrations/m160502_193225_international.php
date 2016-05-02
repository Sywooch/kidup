<?php

use yii\db\Migration;

class m160502_193225_international extends Migration
{
    public function up()
    {
        $curEUR = new \user\models\currency\Currency();
        $curEUR->name = "Euro";
        $curEUR->abbr = "€";
        $curEUR->forex_name = "EUR";
        $curEUR->save();

        $curUSD = new \user\models\currency\Currency();
        $curUSD->name = "US Dollar";
        $curUSD->abbr = "$";
        $curUSD->forex_name = "USD";
        $curUSD->save();

        $curGBP = new \user\models\currency\Currency();
        $curGBP->name = "Great British Pound";
        $curGBP->abbr = "£";
        $curGBP->forex_name = "GBP";
        $curGBP->save();

        $cntry = new \user\models\country\Country();
        $cntry->name = "USA";
        $cntry->code = "US";
        $cntry->main_language_id = "en-US";
        $cntry->currency_id = $curUSD->id;
        $cntry->vat = 20;
        $cntry->phone_prefix = "+1";
        $cntry->save();

        $cntry = new \user\models\country\Country();
        $cntry->name = "United Kingdom";
        $cntry->code = "GB";
        $cntry->main_language_id = "en-US";
        $cntry->currency_id = $curGBP->id;
        $cntry->phone_prefix = "+44";
        $cntry->vat = 20;
        $cntry->save();

        $nl = \user\models\country\Country::findOne(['id' => 2]);
        $nl->currency_id = $curEUR->id;
        $nl->main_language_id = 'en-US';
        $nl->vat = 21;
        $nl->save();
    }

    public function down()
    {
        echo "m160502_193225_international cannot be reverted.\n";

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
