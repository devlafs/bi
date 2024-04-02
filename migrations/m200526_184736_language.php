<?php

use yii\db\Migration;
use yii\db\Schema;

class m200526_184736_language extends Migration
{
    public function init() {
        $this->db = 'userDb';
        parent::init();
    }

    public function safeUp()
    {
        $this->addColumn('{{%admin_usuario}}', 'language', Schema::TYPE_STRING . " not null default 'pt-BR'");
        return true;
    }

    public function safeDown()
    {
        $this->dropColumn('{{%admin_usuario}}', 'language');
        return true;
    }
}
