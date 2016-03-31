<?php
use yii\db\Migration;
use yii\db\Schema;

class m160330_200304_create_notification_log extends Migration
{
    public function up()
    {
        // Prefix everything with notification to keep all tables close to each other
        $this->createTable('notification_mail_log', [
            'id' => $this->primaryKey(),
            'receiver_id' => Schema::TYPE_INTEGER,
            'subject' => Schema::TYPE_STRING,
            'to' => Schema::TYPE_STRING,
            'reply_to' => Schema::TYPE_STRING,
            'from' => Schema::TYPE_STRING,
            'variables' => Schema::TYPE_STRING,
            'view' => Schema::TYPE_STRING,
            'created_at' => Schema::TYPE_INTEGER
        ]);
        $this->createTable('notification_mail_click_log', [
            'mail_id' => Schema::TYPE_INTEGER,
            'link_text' => Schema::TYPE_STRING,
            'url' => Schema::TYPE_STRING,
            'created_at' => Schema::TYPE_INTEGER
        ]);
        $this->createTable('notification_push_log', [
            'id' => $this->primaryKey(),
            'receiver_id' => Schema::TYPE_INTEGER,
            'receiver_platform' => Schema::TYPE_STRING,
            'receiver_arn_endpoint' => Schema::TYPE_STRING,
            'variables' => Schema::TYPE_STRING,
            'view' => Schema::TYPE_STRING,
            'created_at' => Schema::TYPE_INTEGER
        ]);
        $this->addForeignKey('fk_notification_mail_click_log_mail', 'notification_mail_click_log', 'mail_id', 'notification_mail_log', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_notification_mail_log_receiver', 'notification_mail_log', 'receiver_id', 'user', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_notification_push_log_receiver', 'notification_push_log', 'receiver_id', 'user', 'id', 'CASCADE', 'NO ACTION');
    }

    public function down()
    {
        $this->dropTable('notification_mail_log');
        $this->dropTable('notification_mail_click_log');
        $this->dropTable('notification_push_log');
    }
}
