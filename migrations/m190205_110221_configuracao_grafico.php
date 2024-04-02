<?php

use yii\db\Migration;
use yii\db\Schema;

class m190205_110221_configuracao_grafico extends Migration {

    public function safeUp() {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bpbi_consulta_campo_configuracao}}', [
            'id' => Schema::TYPE_PK,
            'id_consulta' => Schema::TYPE_INTEGER . ' not null',
            'id_campo' => Schema::TYPE_INTEGER . ' not null',
            'tipo' => Schema::TYPE_STRING . " not null",
            'view' => Schema::TYPE_STRING . " not null",
            'data' => Schema::TYPE_TEXT . ' null',
            'is_serie' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'data_serie' => Schema::TYPE_TEXT . ' null',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
                ], $tableOptions);

        $this->addForeignKey('cocc_cons_fk', '{{%bpbi_consulta_campo_configuracao}}', 'id_consulta', '{{%bpbi_consulta}}', 'id');

        $this->addForeignKey('cocc_inca_fk', '{{%bpbi_consulta_campo_configuracao}}', 'id_campo', '{{%bpbi_indicador_campo}}', 'id');

        return true;
    }

    public function safeDown() {
        $this->dropTable('{{%bpbi_consulta_campo_configuracao}}');

        return true;
    }

}
