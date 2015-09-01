<?php

use yii\db\Schema;
use yii\db\Migration;

class m150824_092746_addLocationApt extends Migration
{
    public function up()
    {
        $this->addColumn('location', 'street_suffix', Schema::TYPE_STRING);
        $this->addColumn('profile', 'location_id', Schema::TYPE_INTEGER);

        $this->createIndex('idx_location', 'profile', 'location_id', 0);

        $this->execute('SET foreign_key_checks = 0');
        $this->addForeignKey('fk_profile_3408_00', 'profile', 'location_id', 'location', 'id', 'CASCADE', 'NO ACTION');
        $this->execute('SET foreign_key_checks = 1');

        // update all existing
        $users = \app\modules\user\models\User::find()->all();
        foreach ($users as $u) {
            $location = \app\modules\item\models\Location::findOne(['user_id' => $u->id]);
            if($location == null) continue;
            $profile = $u->profile;
            $profile->location_id = $location->id;
            $profile->save();
        }

    }

    public function down()
    {
        echo "m150824_092746_addLocationApt cannot be reverted.\n";

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
