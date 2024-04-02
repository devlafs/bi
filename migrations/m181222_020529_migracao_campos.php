<?php

use yii\db\Migration;
use yii\db\mysql\Schema;

class m181222_020529_migracao_campos extends Migration {

    public function safeUp() {
        $this->addColumn('{{%bpbi_conexao}}', 'id_importacao', Schema::TYPE_INTEGER . ' null');

        $this->addColumn('{{%bpbi_indicador}}', 'id_importacao', Schema::TYPE_INTEGER . ' null');

        $this->addColumn('{{%bpbi_indicador_campo}}', 'id_importacao', Schema::TYPE_INTEGER . ' null');

        return true;
    }

    public function safeDown() {
        $this->dropColumn('{{%bpbi_conexao}}', 'id_importacao');

        $this->dropColumn('{{%bpbi_indicador}}', 'id_importacao');

        $this->dropColumn('{{%bpbi_indicador_campo}}', 'id_importacao');

        return true;
    }

}
