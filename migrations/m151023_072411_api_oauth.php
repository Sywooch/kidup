<?php

use yii\db\Schema;
use yii\db\Migration;

class m151023_072411_api_oauth extends Migration
{
    public function mysql($yes, $no = '')
    {
        return $this->db->driverName === 'mysql' ? $yes : $no;
    }

    public function primaryKey($columns)
    {
        return 'PRIMARY KEY (' . $this->db->getQueryBuilder()->buildColumns($columns) . ')';
    }

    public function foreignKey($columns, $refTable, $refColumns, $onDelete = null, $onUpdate = null)
    {
        $builder = $this->db->getQueryBuilder();
        $sql = ' FOREIGN KEY (' . $builder->buildColumns($columns) . ')'
            . ' REFERENCES ' . $this->db->quoteTableName($refTable)
            . ' (' . $builder->buildColumns($refColumns) . ')';
        if ($onDelete !== null) {
            $sql .= ' ON DELETE ' . $onDelete;
        }
        if ($onUpdate !== null) {
            $sql .= ' ON UPDATE ' . $onUpdate;
        }
        return $sql;
    }

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $now = $this->mysql('CURRENT_TIMESTAMP', "'now'");
        $on_update_now = $this->mysql("ON UPDATE $now");
        $transaction = $this->db->beginTransaction();

        // custom
//        $this->dropColumn('user', 'auth_key');

        try {
            $this->createTable('{{%oauth_client}}', [
                'client_id' => Schema::TYPE_STRING . '(32) NOT NULL',
                'client_secret' => Schema::TYPE_STRING . '(32) DEFAULT NULL',
                'redirect_uri' => Schema::TYPE_STRING . '(1000) NOT NULL',
                'grant_types' => Schema::TYPE_STRING . '(100) NOT NULL',
                'scope' => Schema::TYPE_STRING . '(2000) DEFAULT NULL',
                'user_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
                $this->primaryKey('client_id'),
            ], $tableOptions);
            $this->createTable('{{%oauth_access_token}}', [
                'access_token' => Schema::TYPE_STRING . '(40) NOT NULL',
                'client_id' => Schema::TYPE_STRING . '(32) NOT NULL',
                'user_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
                'expires' => Schema::TYPE_INTEGER . " NOT NULL",
                'scope' => Schema::TYPE_STRING . '(2000) DEFAULT NULL',
                $this->primaryKey('access_token'),
                $this->foreignKey('client_id', '{{%oauth_client}}', 'client_id', 'CASCADE', 'CASCADE'),
            ], $tableOptions);
            $this->createTable('{{%oauth_refresh_token}}', [
                'refresh_token' => Schema::TYPE_STRING . '(40) NOT NULL',
                'client_id' => Schema::TYPE_STRING . '(32) NOT NULL',
                'user_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
                'expires' => Schema::TYPE_INTEGER . " NOT NULL",
                'scope' => Schema::TYPE_STRING . '(2000) DEFAULT NULL',
                $this->primaryKey('refresh_token'),
                $this->foreignKey('client_id', '{{%oauth_client}}', 'client_id', 'CASCADE', 'CASCADE'),
            ], $tableOptions);
            // insert client data
            $this->batchInsert('{{%oauth_client}}', ['client_id', 'client_secret', 'redirect_uri', 'grant_types'], [
                ['testclient', 'testpass', 'http://fake/', 'client_credentials authorization_code password implicit'],
            ]);
            $transaction->commit();
        } catch (Exception $e) {
            echo 'Exception: ' . $e->getMessage() . '\n';
            $transaction->rollback();
            return false;
        }
        return true;
    }

    public function down()
    {
        $transaction = $this->db->beginTransaction();
        try {
            $this->dropTable('{{%oauth_users}}');
            $this->dropTable('{{%oauth_jwt}}');
            $this->dropTable('{{%oauth_scopes}}');
            $this->dropTable('{{%oauth_authorization_codes}}');
            $this->dropTable('{{%oauth_refresh_tokens}}');
            $this->dropTable('{{%oauth_access_tokens}}');
            $this->dropTable('{{%oauth_public_keys}}');
            $this->dropTable('{{%oauth_clients}}');
            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            echo $e->getMessage();
            echo "\n";
            echo get_called_class() . ' cannot be reverted.';
            echo "\n";
            return false;
        }
        return true;
    }
}
