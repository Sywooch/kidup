<?php

use yii\db\Migration;
use yii\db\Schema;

class m151123_200644_userReferral extends Migration
{
    public function up()
    {
        $this->createTable('user_referred_user',[
            'referred_user_id' => Schema::TYPE_INTEGER,
            'referring_user_id' => Schema::TYPE_INTEGER,
            'created_at' => Schema::TYPE_INTEGER,
            0 => 'PRIMARY KEY (`referred_user_id`, `referring_user_id`)',
        ]);

        $this->addColumn('user', 'referral_code', Schema::TYPE_STRING);

        $this->execute('SET foreign_key_checks = 0;');
        $this->addForeignKey('fk_user_referred_user_referred_user', 'user_referred_user', 'referred_user_id', 'user', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_user_referred_user_referring_user', 'user_referred_user', 'referring_user_id', 'user', 'id', 'CASCADE', 'NO ACTION');
        $this->execute('SET foreign_key_checks = 1;');

        $users = \user\models\user\User::find()->where('referral_code IS NULL')->all();
        foreach ($users as $user) {
            $user->referral_code = \Yii::$app->security->generateRandomString(8);
            $user->save();
        }
    }

    public function down()
    {
        echo "m151123_200644_userReferral cannot be reverted.\n";

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
