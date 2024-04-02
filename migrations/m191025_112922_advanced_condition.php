<?php

use yii\db\Migration;
use yii\db\Schema;

class m191025_112922_advanced_condition extends Migration
{
    public function safeUp()
    {
        $this->addColumn("bpbi_consulta", 'condicao_avancada', Schema::TYPE_TEXT);

        return true;
    }

    public function safeDown()
    {
        $this->dropColumn("bpbi_consulta", 'condicao_avancada');

        return true;
    }
}
