<?php

use yii\db\Migration;
use yii\db\Schema;

class m150910_141237_promotionCodes extends Migration
{
    public function up()
    {
        $this->createTable('promotion_code',[
            'id' => Schema::TYPE_STRING,
            'type_target' => Schema::TYPE_SMALLINT, // renter_fee, renter_total, owner_fee
            'type_amount' => Schema::TYPE_SMALLINT, // renter_fee, renter_total, owner_fee
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'valid_until' => Schema::TYPE_INTEGER,
            'count_total' => Schema::TYPE_INTEGER,
            'count_left' => Schema::TYPE_INTEGER,
            0 => 'PRIMARY KEY (`id`)',
        ]);

        $this->createTable('user_has_promotion_code',[
            'user_id' => Schema::TYPE_INTEGER,
            'promotion_code_id' => Schema::TYPE_STRING, // renter_fee, renter_total, owner_fee
            0 => 'PRIMARY KEY (`user_id`, `promotion_code_id`)',
        ]);

        $this->addColumn('booking', 'promotion_code_id', Schema::TYPE_STRING);

        $this->execute('SET foreign_key_checks = 0');

        $this->createIndex('idx_promotion_code_1', 'promotion_code', 'id', 0);
        $this->createIndex('idx_user_has_promotion_code_1', 'user_has_promotion_code', 'user_id', 0);
        $this->createIndex('idx_user_has_promotion_code_2', 'user_has_promotion_code', 'promotion_code_id', 0);
        $this->createIndex('idx_booking_promotion_code_1', 'booking', 'promotion_code_id', 0);
        $this->addForeignKey('fk_user_has_promotion_code_promotion_code', 'user_has_promotion_code', 'promotion_code_id', 'promotion_code', 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk_user_has_promotion_code_user', 'user_has_promotion_code', 'user_id', 'user', 'id', 'CASCADE', 'NO ACTION');
        $this->execute('SET foreign_key_checks = 1');
    }

    public function down()
    {
        echo "m150910_141237_promotionCodes cannot be reverted.\n";

        return false;
    }

}
