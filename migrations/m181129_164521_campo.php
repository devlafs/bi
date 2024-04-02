<?php

use yii\db\Migration;
use yii\db\Schema;

class m181129_164521_campo extends Migration {

    public function safeUp() {
        $this->addColumn('{{%bpbi_indicador_campo}}', 'campo', Schema::TYPE_STRING);

        $sql = <<<SQL
                
            UPDATE bpbi_indicador_campo SET campo = nome WHERE id > 0;
            ALTER TABLE bpbi_indicador_campo MODIFY campo varchar(255) NOT NULL;
                
SQL;
        $this->execute($sql);

        return true;
    }

    public function safeDown() {
        $this->dropColumn('{{%bpbi_indicador_campo}}', 'campo');

        return true;
    }

}
