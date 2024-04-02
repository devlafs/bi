<?php

use yii\db\Migration;

class m190829_204407_tamanho_campo extends Migration
{
    public function safeUp()
    {
        $this->execute("ALTER TABLE bpbi_indicador_campo MODIFY campo VARCHAR(1024);");

        return true;
    }

    public function safeDown()
    {
        echo "m190829_204407_tamanho_campo cannot be reverted.\n";

        return true;
    }
}
