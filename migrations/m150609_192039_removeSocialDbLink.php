<?php
use yii\db\Migration;

class m150609_192039_removeSocialDbLink extends Migration {
    public function up(){
        $this->dropForeignKey('fk_social_account_user1', 'social_account');
    }
}
