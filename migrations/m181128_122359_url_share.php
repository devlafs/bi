<?php

use yii\db\Migration;
use yii\db\Schema;

class m181128_122359_url_share extends Migration {

    public function safeUp() {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bpbi_url_share}}', [
            'id' => Schema::TYPE_PK,
            'id_consulta' => Schema::TYPE_INTEGER . ' not null',
            'id_usuario' => Schema::TYPE_INTEGER . ' not null',
            'token' => Schema::TYPE_STRING . ' not null',
            'type' => Schema::TYPE_STRING . ' not null',
            'email' => Schema::TYPE_STRING . ' null',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
                ], $tableOptions);

        $this->addForeignKey('ursh_cons_fk', '{{%bpbi_url_share}}', 'id_consulta', '{{%bpbi_consulta}}', 'id');

        $this->createTable('{{%bpbi_url_share_access}}', [
            'id' => Schema::TYPE_PK,
            'id_url' => Schema::TYPE_INTEGER . ' not null',
            'ip' => Schema::TYPE_STRING . ' not null',
            'is_expired' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
                ], $tableOptions);

        $this->addForeignKey('ursa', '{{%bpbi_url_share_access}}', 'id_url', '{{%bpbi_url_share}}', 'id');

        return true;
    }

    public function safeDown() {
        $this->dropTable('{{%bpbi_url_share_access}}');
        $this->dropTable('{{%bpbi_url_share}}');

        return true;
    }

}
