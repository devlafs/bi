<?php

use yii\db\Migration;
use yii\db\Schema;

class m190606_204301_campo_config extends Migration {

    public function safeUp() {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->addColumn('{{%bpbi_indicador_campo}}', 'separador_decimal', Schema::TYPE_STRING . " NULL DEFAULT ','");

        $this->addColumn('{{%bpbi_indicador_campo}}', 'separador_milhar', Schema::TYPE_STRING . " NULL DEFAULT '.'");

        $this->addColumn('{{%bpbi_indicador_campo}}', 'variavel_formula', Schema::TYPE_STRING);

        $this->addColumn('{{%bpbi_indicador_campo}}', 'agrupar_valor', Schema::TYPE_BOOLEAN . ' not null default TRUE');

        $this->addColumn('{{%bpbi_painel}}', 'javascript', Schema::TYPE_TEXT);

        $this->addColumn('{{%bpbi_consulta}}', 'javascript', Schema::TYPE_TEXT);

        $this->addColumn('{{%bpbi_indicador}}', 'hora_inicial', Schema::TYPE_STRING . ' not null default "00:00"');

        $this->createTable('{{%bpbi_indicador_carga_historico}}', [
            'id' => Schema::TYPE_PK,
            'id_indicador' => Schema::TYPE_INTEGER . " not null",
            'tipo_carga' => Schema::TYPE_STRING . ' not null',
            'total' => Schema::TYPE_INTEGER,
            'success' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'message' => Schema::TYPE_TEXT,
            'started_at' => Schema::TYPE_TIMESTAMP . ' null',
            'finished_at' => Schema::TYPE_TIMESTAMP . ' null',
                ], $tableOptions);

        $this->addForeignKey('incahi_indi_fk', '{{%bpbi_indicador_carga_historico}}', 'id_indicador', '{{%bpbi_indicador}}', 'id');

        return true;
    }

    public function safeDown() {
        $this->dropColumn('{{%bpbi_indicador_campo}}', 'separador_decimal');

        $this->dropColumn('{{%bpbi_indicador_campo}}', 'separador_milhar');

        $this->dropColumn('{{%bpbi_indicador_campo}}', 'variavel_formula');

        $this->dropColumn('{{%bpbi_indicador_campo}}', 'agrupar_valor');

        $this->dropColumn('{{%bpbi_painel}}', 'javascript');

        $this->dropColumn('{{%bpbi_consulta}}', 'javascript');

        $this->dropColumn('{{%bpbi_indicador}}', 'hora_inicial');

        $this->dropTable('{{%bpbi_indicador_carga_historico}}');

        return true;
    }

}
