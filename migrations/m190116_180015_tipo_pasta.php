<?php

use yii\db\Migration;
use yii\db\Schema;

class m190116_180015_tipo_pasta extends Migration {

    public function safeUp() {
        $this->addColumn('{{%bpbi_pasta}}', 'tipo', Schema::TYPE_STRING . ' not null default "CONSULTA"');

        return true;
    }

    public function safeDown() {
        $this->addColumn('{{%bpbi_pasta}}', 'tipo');

        return true;
    }

}
