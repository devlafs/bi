<?php

use yii\db\Migration;
use yii\db\Schema;

class m190131_120133_configuracoes_campos extends Migration {

    public function safeUp() {
        $this->addColumn('{{%bpbi_indicador_campo}}', 'prefixo', Schema::TYPE_STRING);

        $this->addColumn('{{%bpbi_indicador_campo}}', 'sufixo', Schema::TYPE_STRING);

        $this->addColumn('{{%bpbi_indicador_campo}}', 'formato', Schema::TYPE_STRING);

        $this->addColumn('{{%bpbi_indicador_campo}}', 'tipo_numero', Schema::TYPE_INTEGER);

        $this->addColumn('{{%bpbi_indicador_campo}}', 'casas_decimais', Schema::TYPE_INTEGER);

        Yii::$app->db->createCommand("UPDATE bpbi_indicador_campo SET tipo = 'valor' WHERE tipo = 'numero' AND id > 0")->execute();

        return true;
    }

    public function safeDown() {
        $this->dropColumn('{{%bpbi_indicador_campo}}', 'prefixo');

        $this->dropColumn('{{%bpbi_indicador_campo}}', 'sufixo');

        $this->dropColumn('{{%bpbi_indicador_campo}}', 'formato');

        $this->dropColumn('{{%bpbi_indicador_campo}}', 'tipo_numero');

        $this->dropColumn('{{%bpbi_indicador_campo}}', 'casas_decimais');

        return true;
    }

}
