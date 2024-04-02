<?php

use yii\db\Migration;
use yii\db\Schema;

class m190122_140707_configuracao_grafico extends Migration {

    public function safeUp() {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bpbi_grafico_configuracao}}', [
            'id' => Schema::TYPE_PK,
            'view' => Schema::TYPE_STRING . ' not null',
            'tipo' => Schema::TYPE_STRING . ' not null',
            'data' => Schema::TYPE_TEXT,
            'data_serie' => Schema::TYPE_TEXT,
            'is_serie' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
                ], $tableOptions);

        return true;
    }

    public function safeDown() {
        $this->dropTable('{{%bpbi_grafico_configuracao}}');

        return true;
    }

}
