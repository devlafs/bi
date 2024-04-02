<?php

use yii\db\Migration;
use yii\db\Schema;

class m200522_175709_condicao_painel extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%bpbi_painel}}', 'condicao', Schema::TYPE_JSON);

        return true;
    }

    public function safeDown()
    {
        $this->dropColumn('{{%bpbi_painel}}', 'condicao');

        return true;
    }
}
