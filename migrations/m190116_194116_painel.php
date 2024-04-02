<?php

use yii\db\Migration;
use yii\db\Schema;

class m190116_194116_painel extends Migration {

    public function safeUp() {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bpbi_painel}}', [
            'id' => Schema::TYPE_PK,
            'id_pasta' => Schema::TYPE_INTEGER . ' null',
            'nome' => Schema::TYPE_STRING . ' not null',
            'descricao' => Schema::TYPE_TEXT . ' null',
            'data' => Schema::TYPE_JSON,
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
                ], $tableOptions);

        $this->addForeignKey('pain_past_fk', '{{%bpbi_painel}}', 'id_pasta', '{{%bpbi_pasta}}', 'id');

        return true;
    }

    public function safeDown() {
        $this->dropTable('{{%bpbi_painel}}');

        return true;
    }

}
