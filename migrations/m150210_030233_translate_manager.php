<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * @author Lajos Molnár <lajax.m@gmail.com>
 * @since 1.0
 */
class m150210_030233_translate_manager extends Migration {

    /**
     * @var array The language table contains the list of languages.
     */
    private $_languages = [
        ['da-DK', 'da', 'dk', 'Dansk', 'Danish', 1],
        ['en-US', 'en', 'us', 'English (US)', 'English (US)', 1],
    ];

//    public function init(){
//        $this->db = 'db-i18n';
//        return parent::init();
//    }

    public function up() {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->batchInsert('language', [
            'language_id',
            'language',
            'country',
            'name',
            'name_ascii',
            'status'
        ], $this->_languages);

    }

}