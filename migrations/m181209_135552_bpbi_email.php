<?php

use yii\db\Migration;
use yii\db\Schema;

class m181209_135552_bpbi_email extends Migration {

    public function safeUp() {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bpbi_email}}', [
            'id' => Schema::TYPE_PK,
            'assunto' => Schema::TYPE_STRING . ' not null',
            'id_usuario' => Schema::TYPE_INTEGER . ' null',
            'id_perfil' => Schema::TYPE_INTEGER . ' null',
            'id_consulta' => Schema::TYPE_INTEGER . ' not null',
            'email' => Schema::TYPE_STRING . ' null',
            'frequencia' => Schema::TYPE_INTEGER . ' not null',
            'hora' => Schema::TYPE_INTEGER . ' null',
            'dia_semana' => Schema::TYPE_INTEGER . ' null',
            'dia_mes' => Schema::TYPE_INTEGER . ' null',
//            'body' => Schema::TYPE_TEXT . ' not null',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null'
                ], $tableOptions);

        $this->addForeignKey('emai_cons_fk', '{{%bpbi_email}}', 'id_consulta', '{{%bpbi_consulta}}', 'id');

        $this->createTable('{{%bpbi_permissao_geral}}', [
            'id' => Schema::TYPE_PK,
            'nome' => Schema::TYPE_STRING . ' not null',
            'descricao' => Schema::TYPE_TEXT . ' null',
            'gerenciador' => Schema::TYPE_STRING . ' not null',
            'constante' => Schema::TYPE_TEXT . ' not null',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
                ], $tableOptions);

        $this->createTable('{{%bpbi_perfil_permissao}}', [
            'id' => Schema::TYPE_PK,
            'id_perfil' => Schema::TYPE_INTEGER . ' not null',
            'id_permissao' => Schema::TYPE_INTEGER . ' not null',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
                ], $tableOptions);

        $this->addForeignKey('pepe_pege_fk', '{{%bpbi_perfil_permissao}}', 'id_permissao', '{{%bpbi_permissao_geral}}', 'id');

        $this->createTable('{{%bpbi_permissao_consulta}}', [
            'id' => Schema::TYPE_PK,
            'nome' => Schema::TYPE_STRING . ' not null',
            'descricao' => Schema::TYPE_TEXT . ' null',
            'gerenciador' => Schema::TYPE_STRING . ' not null',
            'constante' => Schema::TYPE_TEXT . ' not null',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
                ], $tableOptions);

        $this->createTable('{{%bpbi_consulta_permissao}}', [
            'id' => Schema::TYPE_PK,
            'id_consulta' => Schema::TYPE_INTEGER . ' not null',
            'id_permissao' => Schema::TYPE_INTEGER . ' not null',
            'id_perfil' => Schema::TYPE_INTEGER . ' not null',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
                ], $tableOptions);

        $this->addForeignKey('cope_cons_fk', '{{%bpbi_consulta_permissao}}', 'id_consulta', '{{%bpbi_consulta}}', 'id');

        $this->addForeignKey('cope_peco_fk', '{{%bpbi_consulta_permissao}}', 'id_permissao', '{{%bpbi_permissao_consulta}}', 'id');

        return true;
    }

    public function safeDown() {
        $this->dropTable('{{%bpbi_consulta_permissao}}');
        $this->dropTable('{{%bpbi_permissao_consulta}}');
        $this->dropTable('{{%bpbi_perfil_permissao}}');
        $this->dropTable('{{%bpbi_permissao_geral}}');
        $this->dropTable('{{%bpbi_email}}');

        return true;
    }

}
