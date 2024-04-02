<?php

use yii\db\Migration;
use yii\db\Schema;

class m210901_173405_permissao_rel extends Migration
{
    public function init() {
        $this->db = 'userDb';
        parent::init();
    }

    public function safeUp() {
        $this->addColumn('{{%admin_perfil}}', 'bpbi_menu_relatorio', Schema::TYPE_JSON);

        return true;
    }

    public function safeDown() {
        $this->dropColumn('{{%admin_perfil}}', 'bpbi_menu_relatorio');

        return true;
    }
}
