<?php

use yii\db\Migration;
use yii\db\Schema;

class m190215_173246_tipo_campo extends Migration {

    public function safeUp() {
        $this->dropColumn('{{%bpbi_indicador_campo}}', 'tipo_numero');

        $this->addColumn('{{%bpbi_consulta_item}}', 'tipo_numero', Schema::TYPE_INTEGER . ' not null default 1');

        return true;
    }

    public function safeDown() {
        $this->dropColumn('{{%bpbi_consulta_item}}', 'tipo_numero');

        return true;
    }

}
