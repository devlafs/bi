<?php

use yii\db\Migration;
use yii\db\Schema;

class m190129_175357_permissao_menu extends Migration {

    public function init() {
        $this->db = 'userDb';
        parent::init();
    }

    public function safeUp() {
        $this->addColumn('{{%admin_perfil}}', 'bpbi_menu_consulta', Schema::TYPE_JSON);
        $this->addColumn('{{%admin_perfil}}', 'bpbi_menu_painel', Schema::TYPE_JSON);

        return true;
    }

    public function safeDown() {
        $this->dropColumn('{{%admin_perfil}}', 'bpbi_menu_consulta');
        $this->dropColumn('{{%admin_perfil}}', 'bpbi_menu_painel');

        return true;
    }

}
