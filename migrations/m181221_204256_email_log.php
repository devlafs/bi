<?php

use yii\db\Migration;
use yii\db\Schema;

class m181221_204256_email_log extends Migration {

    public function safeUp() {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bpbi_email_log}}', [
            'id' => Schema::TYPE_PK,
            'id_email' => Schema::TYPE_INTEGER . ' not null',
            'destinatario' => Schema::TYPE_STRING . ' not null',
            'log' => Schema::TYPE_TEXT . ' not null',
            'status' => Schema::TYPE_INTEGER . ' not null',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
                ], $tableOptions);

        $this->addForeignKey('emlo_emai_fk', '{{%bpbi_email_log}}', 'id_email', '{{%bpbi_email}}', 'id');

        return true;
    }

    public function safeDown() {
        $this->dropTable('{{%bpbi_email_log}}');

        return true;
    }

}
