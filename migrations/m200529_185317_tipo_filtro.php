<?php

use yii\db\Migration;
use yii\db\Schema;

class m200529_185317_tipo_filtro extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%bpbi_indicador_campo}}', 'tipo_lista', Schema::TYPE_BOOLEAN . ' not null default FALSE');

        return true;
    }

    public function safeDown()
    {
        $this->dropColumn('{{%bpbi_indicador_campo}}', 'tipo_lista');

        return true;
    }
}
