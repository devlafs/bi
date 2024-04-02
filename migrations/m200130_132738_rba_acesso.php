<?php

use yii\db\Migration;
use yii\db\Schema;

class m200130_132738_rba_acesso extends Migration
{
    public function init() {
        $this->db = 'userDb';
        parent::init();
    }

    public function safeUp() {
//        $this->addColumn('{{%rba_acesso}}', 'bpbi', Schema::TYPE_BOOLEAN . ' not null default FALSE');

        return true;
    }

    public function safeDown() {
        $this->dropColumn('{{%rba_acesso}}', 'bpbi');

        return true;
    }
}
