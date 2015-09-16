<?php

use yii\db\Migration;

class m150206_173308_init extends Migration
{
    public function up()
    {
        $tables = Yii::$app->db->schema->getTableNames();
        $dbType = $this->db->driverName;
        $tableOptions_mysql = "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        $tableOptions_mssql = "";
        $tableOptions_pgsql = "";
        $tableOptions_sqlite = "";

        /* MYSQL */
        if (!in_array('item', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%item}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'name' => 'VARCHAR(140) NULL',
                    'description' => 'TEXT NULL',
                    'price_day' => 'INT(11) NULL',
                    'price_week' => 'INT(11) NULL',
                    'price_month' => 'INT(11) NULL',
                    'owner_id' => 'INT(11) NULL',
                    'condition' => 'INT(11) NULL',
                    'currency_id' => 'INT(11) NULL',
                    'is_available' => 'TINYINT(1) NULL',
                    'location_id' => 'INT(11) NULL',
                    'created_at' => 'INT(11) NOT NULL',
                    'updated_at' => 'INT(11) NOT NULL',
                    'min_renting_days' => 'INT(11) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('language', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%language}}', [
                    'language_id' => 'VARCHAR(5) NOT NULL',
                    0 => 'PRIMARY KEY (`language_id`)',
                    'language' => 'VARCHAR(3) NOT NULL',
                    'name' => 'VARCHAR(32) NOT NULL',
                    'name_ascii' => 'VARCHAR(32) NOT NULL',
                    'status' => 'VARCHAR(128) NOT NULL',
                    'country' => 'VARCHAR(3) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('media', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%media}}', [
                    'id' => 'BIGINT(20) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'user_id' => 'INT(11) NOT NULL',
                    'storage' => 'VARCHAR(25) NOT NULL',
                    'type' => 'VARCHAR(45) NOT NULL',
                    'description' => 'VARCHAR(256) NULL',
                    'created_at' => 'INT(11) NOT NULL',
                    'updated_at' => 'INT(11) NOT NULL',
                    'file_name' => 'VARCHAR(128) NOT NULL',
                ], $tableOptions_mysql);
            }
        }



        /* MYSQL */
        if (!in_array('booking', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%booking}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'status' => 'VARCHAR(50) NOT NULL',
                    'item_id' => 'INT(11) NOT NULL',
                    'renter_id' => 'INT(11) NULL',
                    'currency_id' => 'INT(11) NULL',
                    'refund_status' => 'VARCHAR(20) NULL',
                    'time_from' => 'INT(11) NOT NULL',
                    'time_to' => 'INT(11) NOT NULL',
                    'item_backup' => 'MEDIUMTEXT NULL',
                    'updated_at' => 'INT(11) NOT NULL',
                    'created_at' => 'INT(11) NOT NULL',
                    'payin_id' => 'INT(11) NULL',
                    'payout_id' => 'INT(11) NULL',
                    'amount_item' => 'DOUBLE NULL',
                    'amount_payin' => 'DOUBLE NULL',
                    'amount_payin_fee' => 'DOUBLE NULL',
                    'amount_payin_fee_tax' => 'DOUBLE NULL',
                    'amount_payin_costs' => 'DOUBLE NULL',
                    'amount_payout' => 'DOUBLE NULL',
                    'amount_payout_fee' => 'DOUBLE NULL',
                    'amount_payout_fee_tax' => 'DOUBLE NULL',
                    'request_expires_at' => 'INT(11) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('category', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%category}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'name' => 'VARCHAR(50) NOT NULL',
                    'type' => 'VARCHAR(25) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('child', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%child}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'name' => 'VARCHAR(45) NULL',
                    'birthday' => 'INT(11) NULL',
                    'gender' => 'VARCHAR(10) NULL',
                    'user_id' => 'INT(11) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('conversation', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%conversation}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'initiater_user_id' => 'INT(11) NOT NULL',
                    'target_user_id' => 'INT(11) NOT NULL',
                    'title' => 'VARCHAR(50) NOT NULL',
                    'created_at' => 'INT(11) NOT NULL',
                    'updated_at' => 'INT(11) NULL',
                    'booking_id' => 'INT(11) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('country', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%country}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'name' => 'VARCHAR(100) NOT NULL',
                    'code' => 'VARCHAR(2) NOT NULL',
                    'main_language_id' => 'VARCHAR(5) NOT NULL',
                    'currency_id' => 'INT(11) NOT NULL',
                    'phone_prefix' => 'INT(11) NOT NULL',
                    'vat' => 'DOUBLE NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('currency', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%currency}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'name' => 'VARCHAR(50) NOT NULL',
                    'abbr' => 'VARCHAR(5) NOT NULL',
                    'forex_name' => 'VARCHAR(5) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('invoice', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%invoice}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'invoice_number' => 'INT(11) NULL',
                    'data' => 'TEXT NULL',
                    'created_at' => 'INT(11) NULL',
                    'updated_at' => 'INT(11) NULL',
                    'status' => 'VARCHAR(45) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('ip_location', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%ip_location}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'ip' => 'VARCHAR(255) NOT NULL',
                    'latitude' => 'DOUBLE NULL',
                    'longitude' => 'DOUBLE NULL',
                    'city' => 'VARCHAR(255) NULL',
                    'country' => 'VARCHAR(255) NULL',
                    'street_name' => 'VARCHAR(255) NULL',
                    'street_number' => 'VARCHAR(255) NULL',
                    'data' => 'TEXT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('item_has_category', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%item_has_category}}', [
                    'item_id' => 'INT(11) NOT NULL',
                    0 => 'PRIMARY KEY (`item_id`, `category_id`)',
                    'category_id' => 'INT(11) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('item_has_media', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%item_has_media}}', [
                    'item_id' => 'INT(11) NOT NULL',
                    0 => 'PRIMARY KEY (`item_id`,`media_id`)',
                    'media_id' => 'BIGINT(20) NOT NULL',
                    'order' => 'TINYINT(4) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('location', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%location}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'type' => 'INT(2) NOT NULL',
                    'country' => 'INT(11) NULL',
                    'city' => 'VARCHAR(100) NULL',
                    'zip_code' => 'VARCHAR(50) NULL',
                    'street_name' => 'VARCHAR(256) NULL',
                    'street_number' => 'VARCHAR(10) NULL',
                    'longitude' => 'FLOAT NOT NULL',
                    'latitude' => 'FLOAT NOT NULL',
                    'user_id' => 'INT(11) NOT NULL',
                    'created_at' => 'INT(11) NOT NULL',
                    'updated_at' => 'INT(11) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('mail_account', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%mail_account}}', [
                    'name' => 'VARCHAR(128) NOT NULL',
                    0 => 'PRIMARY KEY (`name`)',
                    'user_id' => 'INT(11) NOT NULL',
                    'conversation_id' => 'INT(11) NOT NULL',
                    'created_at' => 'INT(11) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('mail_log', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%mail_log}}', [
                    'id' => 'VARCHAR(64) NOT NULL',
                    0 => 'PRIMARY KEY (`id`)',
                    'data' => 'TEXT NULL',
                    'email' => 'VARCHAR(256) NOT NULL',
                    'type' => 'VARCHAR(45) NOT NULL',
                    'created_at' => 'INT(11) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('mail_message', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%mail_message}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'message' => 'TEXT NOT NULL',
                    'from_email' => 'VARCHAR(128) NOT NULL',
                    'message_id' => 'INT(11) NOT NULL',
                    'subject' => 'VARCHAR(512) NULL',
                    'created_at' => 'INT(11) NULL',
                    'mail_account_name' => 'VARCHAR(128) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('message', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%message}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'conversation_id' => 'INT(11) NOT NULL',
                    'message' => 'TEXT NOT NULL',
                    'sender_user_id' => 'INT(11) NOT NULL',
                    'read_by_receiver' => 'TINYINT(1) NOT NULL',
                    'receiver_user_id' => 'INT(11) NULL',
                    'updated_at' => 'INT(11) NOT NULL',
                    'created_at' => 'INT(11) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('migration', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%migration}}', [
                    'version' => 'VARCHAR(180) NOT NULL',
                    0 => 'PRIMARY KEY (`version`)',
                    'apply_time' => 'INT(11) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('payin', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%payin}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'data' => 'TEXT NULL',
                    'type' => 'VARCHAR(25) NULL',
                    'user_id' => 'INT(11) NOT NULL',
                    'status' => 'VARCHAR(45) NULL',
                    'currency_id' => 'INT(11) NOT NULL',
                    'created_at' => 'INT(11) NULL',
                    'updated_at' => 'INT(11) NULL',
                    'nonce' => 'VARCHAR(1024) NULL',
                    'braintree_backup' => 'TEXT NULL',
                    'amount' => 'DOUBLE NULL',
                    'invoice_id' => 'INT(11) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('payout', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%payout}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'status' => 'VARCHAR(45) NOT NULL',
                    'amount' => 'DOUBLE NOT NULL',
                    'currency_id' => 'INT(11) NOT NULL',
                    'user_id' => 'INT(11) NOT NULL',
                    'processed_at' => 'INT(11) NULL',
                    'created_at' => 'INT(11) NOT NULL',
                    'updated_at' => 'INT(11) NULL',
                    'invoice_id' => 'INT(11) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('payout_method', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%payout_method}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'address' => 'VARCHAR(45) NULL',
                    'bank_name' => 'VARCHAR(256) NOT NULL',
                    'payee_name' => 'VARCHAR(256) NOT NULL',
                    'country_id' => 'INT(11) NOT NULL',
                    'type' => 'VARCHAR(45) NOT NULL',
                    'identifier_1' => 'VARCHAR(45) NOT NULL',
                    'identifier_2' => 'VARCHAR(45) NOT NULL',
                    'created_at' => 'INT(11) NOT NULL',
                    'updated_at' => 'INT(11) NOT NULL',
                    'user_id' => 'INT(11) NOT NULL',
                    'identifier_1_encrypted' => 'VARCHAR(255) NULL',
                    'identifier_2_encrypted' => 'VARCHAR(255) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('profile', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%profile}}', [
                    'user_id' => 'INT(11) NOT NULL',
                    0 => 'PRIMARY KEY (`user_id`)',
                    'description' => 'TEXT NULL',
                    'first_name' => 'VARCHAR(128) NOT NULL',
                    'last_name' => 'VARCHAR(256) NOT NULL',
                    'img' => 'VARCHAR(256) NULL',
                    'phone_country' => 'VARCHAR(5) NULL',
                    'phone_number' => 'VARCHAR(50) NULL',
                    'email_verified' => 'TINYINT(1) NOT NULL',
                    'phone_verified' => 'TINYINT(1) NOT NULL',
                    'identity_verified' => 'TINYINT(1) NOT NULL',
                    'location_verified' => 'TINYINT(1) NOT NULL',
                    'language' => 'VARCHAR(6) NULL',
                    'currency_id' => 'INT(11) NULL',
                    'birthday' => 'INT(11) NULL',
                    'nationality' => 'INT(11) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('review', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%review}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'value' => 'TEXT NULL',
                    'reviewer_id' => 'INT(11) NOT NULL',
                    'reviewed_id' => 'INT(11) NOT NULL',
                    'type' => 'VARCHAR(45) NOT NULL',
                    'booking_id' => 'INT(11) NOT NULL',
                    'item_id' => 'INT(11) NULL',
                    'created_at' => 'INT(11) NOT NULL',
                    'updated_at' => 'INT(11) NULL',
                    'is_public' => 'TINYINT(4) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('setting', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%setting}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'type' => 'VARCHAR(50) NULL',
                    'value' => 'VARCHAR(256) NULL',
                    'user_id' => 'INT(11) NOT NULL',
                    'created_at' => 'INT(11) NULL',
                    'updated_at' => 'INT(11) NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('social_account', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%social_account}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'provider' => 'VARCHAR(255) NOT NULL',
                    'client_id' => 'VARCHAR(255) NOT NULL',
                    'data' => 'TEXT NULL',
                    'user_id' => 'INT(11) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('token', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%token}}', [
                    'user_id' => 'INT(11) NOT NULL',
                    'code' => 'VARCHAR(32) NOT NULL',
                    'type' => 'SMALLINT(6) NOT NULL',
                    'created_at' => 'INT(11) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('user', $tables)) {
            if ($dbType == "mysql") {
                $this->createTable('{{%user}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'email' => 'VARCHAR(255) NOT NULL',
                    'password_hash' => 'VARCHAR(60) NOT NULL',
                    'auth_key' => 'VARCHAR(32) NOT NULL',
                    'confirmed_at' => 'INT(11) NULL',
                    'unconfirmed_email' => 'VARCHAR(255) NULL',
                    'blocked_at' => 'INT(11) NULL',
                    'registration_ip' => 'VARCHAR(45) NULL',
                    'flags' => 'INT(11) NOT NULL',
                    'status' => 'INT(11) NOT NULL',
                    'role' => 'INT(11) NOT NULL',
                    'created_at' => 'INT(11) NOT NULL',
                    'updated_at' => 'INT(11) NOT NULL',
                ], $tableOptions_mysql);
            }
        }

        $this->createIndex('idx_item_id_2392_00', 'booking', 'item_id', 0);
        $this->createIndex('idx_renter_id_2392_01', 'booking', 'renter_id', 0);
        $this->createIndex('idx_currency_id_2392_02', 'booking', 'currency_id', 0);
        $this->createIndex('idx_payin_id_2392_03', 'booking', 'payin_id', 0);
        $this->createIndex('idx_payout_id_2392_04', 'booking', 'payout_id', 0);
        $this->createIndex('idx_user_id_2484_05', 'child', 'user_id', 0);
        $this->createIndex('idx_initiater_user_id_2513_06', 'conversation', 'initiater_user_id', 0);
        $this->createIndex('idx_target_user_id_2513_07', 'conversation', 'target_user_id', 0);
        $this->createIndex('idx_currency_id_2542_08', 'country', 'currency_id', 0);
        $this->createIndex('idx_main_language_id_2542_09', 'country', 'main_language_id', 0);
        $this->createIndex('idx_category_id_2634_10', 'item_has_category', 'category_id', 0);
        $this->createIndex('idx_item_id_2634_11', 'item_has_category', 'item_id', 0);
        $this->createIndex('idx_media_id_2652_12', 'item_has_media', 'media_id', 0);
        $this->createIndex('idx_item_id_2652_13', 'item_has_media', 'item_id', 0);
        $this->createIndex('idx_longitude_2678_14', 'location', 'longitude', 0);
        $this->createIndex('idx_user_id_2678_15', 'location', 'user_id', 0);
        $this->createIndex('idx_country_2678_16', 'location', 'country', 0);
        $this->createIndex('idx_user_id_2699_17', 'mail_account', 'user_id', 0);
        $this->createIndex('idx_conversation_id_2699_18', 'mail_account', 'conversation_id', 0);
        $this->createIndex('idx_message_id_2732_19', 'mail_message', 'message_id', 0);
        $this->createIndex('idx_mail_account_name_2733_20', 'mail_message', 'mail_account_name', 0);
        $this->createIndex('idx_conversation_id_2805_21', 'message', 'conversation_id', 0);
        $this->createIndex('idx_sender_user_id_2805_22', 'message', 'sender_user_id', 0);
        $this->createIndex('idx_receiver_user_id_2806_23', 'message', 'receiver_user_id', 0);
        $this->createIndex('idx_user_id_286_24', 'payin', 'user_id', 0);
        $this->createIndex('idx_currency_id_2861_25', 'payin', 'currency_id', 0);
        $this->createIndex('idx_invoice_id_2861_26', 'payin', 'invoice_id', 0);
        $this->createIndex('idx_currency_id_2887_27', 'payout', 'currency_id', 0);
        $this->createIndex('idx_user_id_2887_28', 'payout', 'user_id', 0);
        $this->createIndex('idx_invoice_id_2887_29', 'payout', 'invoice_id', 0);
        $this->createIndex('idx_country_id_2917_30', 'payout_method', 'country_id', 0);
        $this->createIndex('idx_user_id_2917_31', 'payout_method', 'user_id', 0);
        $this->createIndex('idx_currency_id_296_32', 'profile', 'currency_id', 0);
        $this->createIndex('idx_user_id_296_33', 'profile', 'user_id', 0);
        $this->createIndex('idx_nationality_296_34', 'profile', 'nationality', 0);
        $this->createIndex('idx_reviewer_id_2996_35', 'review', 'reviewer_id', 0);
        $this->createIndex('idx_item_id_2996_36', 'review', 'item_id', 0);
        $this->createIndex('idx_reviewed_id_2996_37', 'review', 'reviewed_id', 0);
        $this->createIndex('idx_booking_id_2996_38', 'review', 'booking_id', 0);
        $this->createIndex('idx_user_id_3019_39', 'setting', 'user_id', 0);
        $this->createIndex('idx_UNIQUE_provider_3081_40', 'social_account', 'provider, client_id', 1);
        $this->createIndex('idx_user_id_3081_41', 'social_account', 'user_id', 0);
        $this->createIndex('idx_user_id_3104_42', 'token', 'user_id', 0);

        $this->createIndex('idx_owner_id_7951_00','item','owner_id',0);
        $this->createIndex('idx_currency_id_7952_01','item','currency_id',0);
        $this->createIndex('idx_location_id_7952_02','item','location_id',0);
        $this->createIndex('idx_owner_id_7952_03','item','owner_id',0);
        $this->createIndex('idx_user_id_8005_04','media','user_id',0);


        $this->execute('SET foreign_key_checks = 0');
        $this->addForeignKey('fk_currency_2378_00', '{{%booking}}', 'currency_id', '{{%currency}}', 'id', 'CASCADE',
            'NO ACTION');
        $this->addForeignKey('fk_item_2379_01', '{{%booking}}', 'item_id', '{{%item}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_payin_2379_02', '{{%booking}}', 'payin_id', '{{%payin}}', 'id', 'CASCADE',
            'NO ACTION');
        $this->addForeignKey('fk_payout_2379_03', '{{%booking}}', 'payout_id', '{{%payout}}', 'id', 'CASCADE',
            'NO ACTION');
        $this->addForeignKey('fk_user_2379_04', '{{%booking}}', 'renter_id', '{{%user}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_user_2478_05', '{{%child}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_user_2505_06', '{{%conversation}}', 'initiater_user_id', '{{%user}}', 'id', 'CASCADE',
            'NO ACTION');
        $this->addForeignKey('fk_user_2505_07', '{{%conversation}}', 'target_user_id', '{{%user}}', 'id', 'CASCADE',
            'NO ACTION');
        $this->addForeignKey('fk_currency_2538_08', '{{%country}}', 'currency_id', '{{%currency}}', 'id', 'CASCADE',
            'NO ACTION');
        $this->addForeignKey('fk_language_2538_09', '{{%country}}', 'main_language_id', '{{%language}}', 'language_id',
            'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_category_2629_010', '{{%item_has_category}}', 'category_id', '{{%category}}', 'id',
            'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_item_2629_011', '{{%item_has_category}}', 'item_id', '{{%item}}', 'id', 'CASCADE',
            'NO ACTION');
        $this->addForeignKey('fk_item_2647_012', '{{%item_has_media}}', 'item_id', '{{%item}}', 'id', 'CASCADE',
            'NO ACTION');
        $this->addForeignKey('fk_media_2647_013', '{{%item_has_media}}', 'media_id', '{{%media}}', 'id', 'CASCADE',
            'NO ACTION');
        $this->addForeignKey('fk_country_2672_014', '{{%location}}', 'country', '{{%country}}', 'id', 'CASCADE',
            'NO ACTION');
        $this->addForeignKey('fk_user_2672_015', '{{%location}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_user_2695_016', '{{%mail_account}}', 'user_id', '{{%user}}', 'id', 'CASCADE',
            'NO ACTION');
        $this->addForeignKey('fk_conversation_2695_017', '{{%mail_account}}', 'conversation_id', '{{%conversation}}',
            'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_mail_account_2726_018', '{{%mail_message}}', 'mail_account_name', '{{%mail_account}}',
            'name', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_message_2726_019', '{{%mail_message}}', 'message_id', '{{%message}}', 'id', 'CASCADE',
            'NO ACTION');
        $this->addForeignKey('fk_user_2798_020', '{{%message}}', 'sender_user_id', '{{%user}}', 'id', 'CASCADE',
            'NO ACTION');
        $this->addForeignKey('fk_user_2799_021', '{{%message}}', 'receiver_user_id', '{{%user}}', 'id', 'CASCADE',
            'NO ACTION');
        $this->addForeignKey('fk_conversation_2799_022', '{{%message}}', 'conversation_id', '{{%conversation}}', 'id',
            'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_invoice_2854_023', '{{%payin}}', 'invoice_id', '{{%invoice}}', 'id', 'CASCADE',
            'NO ACTION');
        $this->addForeignKey('fk_user_2854_024', '{{%payin}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_currency_2854_025', '{{%payin}}', 'currency_id', '{{%currency}}', 'id', 'CASCADE',
            'NO ACTION');
        $this->addForeignKey('fk_invoice_2882_026', '{{%payout}}', 'invoice_id', '{{%invoice}}', 'id', 'CASCADE',
            'NO ACTION');
        $this->addForeignKey('fk_currency_2882_027', '{{%payout}}', 'currency_id', '{{%currency}}', 'id', 'CASCADE',
            'NO ACTION');
        $this->addForeignKey('fk_user_2883_028', '{{%payout}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_country_2912_029', '{{%payout_method}}', 'country_id', '{{%country}}', 'id', 'CASCADE',
            'NO ACTION');
        $this->addForeignKey('fk_user_2912_030', '{{%payout_method}}', 'user_id', '{{%user}}', 'id', 'CASCADE',
            'NO ACTION');
        $this->addForeignKey('fk_country_2955_031', '{{%profile}}', 'nationality', '{{%country}}', 'id', 'CASCADE',
            'NO ACTION');
        $this->addForeignKey('fk_user_2956_032', '{{%profile}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_currency_2956_033', '{{%profile}}', 'currency_id', '{{%currency}}', 'id', 'CASCADE',
            'NO ACTION');
        $this->addForeignKey('fk_booking_2991_034', '{{%review}}', 'booking_id', '{{%booking}}', 'id', 'CASCADE',
            'NO ACTION');
        $this->addForeignKey('fk_item_2991_035', '{{%review}}', 'item_id', '{{%item}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_user_2992_036', '{{%review}}', 'reviewer_id', '{{%user}}', 'id', 'CASCADE',
            'NO ACTION');
        $this->addForeignKey('fk_user_2992_037', '{{%review}}', 'reviewed_id', '{{%user}}', 'id', 'CASCADE',
            'NO ACTION');
        $this->addForeignKey('fk_user_3015_038', '{{%setting}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_user_3101_039', '{{%token}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_location_7946_00','{{%item}}', 'location_id', '{{%location}}', 'id', 'CASCADE', 'NO ACTION' );
        $this->addForeignKey('fk_currency_7946_01','{{%item}}', 'currency_id', '{{%currency}}', 'id', 'CASCADE', 'NO ACTION' );
        $this->addForeignKey('fk_user_7947_02','{{%item}}', 'owner_id', '{{%user}}', 'id', 'CASCADE', 'NO ACTION' );
        $this->addForeignKey('fk_user_8002_03','{{%media}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'NO ACTION' );
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {

        return false;
    }
}
