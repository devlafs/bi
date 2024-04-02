<?php

use yii\db\Migration;
use yii\db\Schema;

class m190510_141012_email_confg extends Migration {

    public function safeUp() {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bpbi_template_email}}', [
            'id' => Schema::TYPE_PK,
            'nome' => Schema::TYPE_STRING . ' not null',
            'tipo' => Schema::TYPE_INTEGER . " not null",
            'tags' => Schema::TYPE_JSON . " not null",
            'html' => Schema::TYPE_TEXT . ' not null',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
                ], $tableOptions);

        $this->addColumn("{{%bpbi_email}}", "send_pdf", Schema::TYPE_BOOLEAN . ' not null default FALSE');

        $this->addColumn("{{%bpbi_email}}", "send_weekends", Schema::TYPE_BOOLEAN . ' not null default FALSE');

        $this->addColumn("{{%bpbi_email}}", "id_template", Schema::TYPE_INTEGER);

        $this->addForeignKey('emai_tempemai_fk', '{{%bpbi_email}}', 'id_template', '{{%bpbi_template_email}}', 'id');

        return true;
    }

    public function safeDown() {
        $this->dropColumn("{{%bpbi_email}}", "send_pdf");

        $this->dropColumn("{{%bpbi_email}}", "send_weekends");

        $this->dropColumn("{{%bpbi_email}}", "id_template");

        $this->dropTable('{{%bpbi_template_email}}');

        return true;
    }

}
