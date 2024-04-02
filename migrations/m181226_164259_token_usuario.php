<?php

use yii\db\Migration;
use yii\db\Schema;

class m181226_164259_token_usuario extends Migration {

    public function safeUp() {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bpbi_usuario_token}}', [
            'id' => Schema::TYPE_PK,
            'id_usuario' => Schema::TYPE_INTEGER . ' not null',
            'token' => Schema::TYPE_STRING . ' not null',
            'is_used' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
                ], $tableOptions);

        return true;
    }

    public function safeDown() {
        $this->dropTable('{{%bpbi_usuario_token}}');

        return true;
    }

}
