<?php

use yii\db\Migration;
use yii\db\Schema;

class m210507_184427_relatorios extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql')
        {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bpbi_relatorio}}',
            [
                'id' => Schema::TYPE_PK,
                'id_conexao' => Schema::TYPE_INTEGER . ' not null',
                'nome' => Schema::TYPE_STRING . ' not null',
                'sql' => Schema::TYPE_TEXT . ' not null',
                'descricao' => Schema::TYPE_TEXT . ' null',
                'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
                'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
                'created_at' => Schema::TYPE_TIMESTAMP . ' null',
                'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
                'created_by' => Schema::TYPE_INTEGER . ' null',
                'updated_by' => Schema::TYPE_INTEGER . ' null',
            ], $tableOptions);

        $this->addForeignKey("rela_cone_fk", "{{%bpbi_relatorio}}", "id_conexao", "{{%bpbi_conexao}}", "id");

        $this->createTable('{{%bpbi_relatorio_campo}}',
            [
                'id' => Schema::TYPE_PK,
                'id_relatorio' => Schema::TYPE_INTEGER . ' not null',
                'ordem' => Schema::TYPE_INTEGER . ' not null',
                'nome' => Schema::TYPE_STRING . ' not null',
                'campo' => Schema::TYPE_STRING . ' not null',
                'tipo' => Schema::TYPE_INTEGER . ' not null',
                'descricao' => Schema::TYPE_TEXT . ' null',
                'options' => Schema::TYPE_JSON . ' null',
                'function' => Schema::TYPE_STRING . ' null',
                'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
                'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
                'created_at' => Schema::TYPE_TIMESTAMP . ' null',
                'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
                'created_by' => Schema::TYPE_INTEGER . ' null',
                'updated_by' => Schema::TYPE_INTEGER . ' null',
            ], $tableOptions);

        $this->addForeignKey("reca_rela_fk", "{{%bpbi_relatorio_campo}}", "id_relatorio", "{{%bpbi_relatorio}}", "id");


        return true;
    }

    public function safeDown()
    {
        $this->dropTable('{{%bpbi_relatorio_campo}}');
        $this->dropTable('{{%bpbi_relatorio}}');

        return true;
    }
}
