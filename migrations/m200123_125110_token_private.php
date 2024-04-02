<?php

use yii\db\Migration;
use yii\db\Schema;

class m200123_125110_token_private extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%bpbi_url_share}}','password',Schema::TYPE_STRING);
        $this->addColumn('{{%bpbi_consulta}}','privado',Schema::TYPE_BOOLEAN . ' not null default FALSE');
        $this->addColumn('{{%bpbi_painel}}','privado',Schema::TYPE_BOOLEAN . ' not null default FALSE');

        return true;
    }

    public function safeDown()
    {
        $this->dropColumn('{{%bpbi_url_share}}','password');
        $this->dropColumn('{{%bpbi_consulta}}','privado');
        $this->dropColumn('{{%bpbi_painel}}','privado');

        return true;
    }
}
