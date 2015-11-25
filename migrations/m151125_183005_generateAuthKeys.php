<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Simply generates auth_keys for users who do not have one yet
 * Class m151125_183005_generateAuthKeys
 */
class m151125_183005_generateAuthKeys extends Migration
{
    public function up()
    {
        $users = \user\models\User::find()->where('auth_key = ""')->all();
        foreach ($users as $user) {
            $user->auth_key = \Yii::$app->security->generateRandomString();
            $user->save();
        }
    }

    public function down()
    {
        echo "m151125_183005_generateAuthKeys cannot be reverted.\n";

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
