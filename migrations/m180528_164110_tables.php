<?php

use yii\db\Migration;
use yii\db\Schema;

class m180528_164110_tables extends Migration {

    public function safeUp() {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bpbi_conexao}}', [
            'id' => Schema::TYPE_PK,
            'nome' => Schema::TYPE_STRING . ' not null',
            'tipo' => Schema::TYPE_STRING . ' not null',
            'host' => Schema::TYPE_STRING . ' not null',
            'database' => Schema::TYPE_STRING . ' not null',
            'porta' => Schema::TYPE_STRING . ' null',
            'login' => Schema::TYPE_STRING . ' not null',
            'senha' => Schema::TYPE_STRING . ' not null',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
                ], $tableOptions);

        $this->createTable('{{%bpbi_indicador}}', [
            'id' => Schema::TYPE_PK,
            'id_conexao' => Schema::TYPE_INTEGER . ' not null',
            'tipo' => Schema::TYPE_STRING . ' not null',
            'nome' => Schema::TYPE_STRING . ' not null',
            'descricao' => Schema::TYPE_TEXT . ' null',
            'sql' => Schema::TYPE_TEXT . ' null',
            'caminho' => Schema::TYPE_STRING . ' null',
            'periodicidade' => Schema::TYPE_STRING . ' not null',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'executed_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
                ], $tableOptions);

        $this->addForeignKey('indi_cone_fk', '{{%bpbi_indicador}}', 'id_conexao', '{{%bpbi_conexao}}', 'id');

        $this->createTable('{{%bpbi_indicador_campo}}', [
            'id' => Schema::TYPE_PK,
            'id_indicador' => Schema::TYPE_INTEGER . ' not null',
            'ordem' => Schema::TYPE_INTEGER . ' not null',
            'nome' => Schema::TYPE_STRING . ' not null',
            'tipo' => Schema::TYPE_STRING . " not null default 'texto'",
            'descricao' => Schema::TYPE_TEXT . ' null',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
                ], $tableOptions);

        $this->addForeignKey('camp_indi_fk', '{{%bpbi_indicador_campo}}', 'id_indicador', '{{%bpbi_indicador}}', 'id');

        $this->createTable('{{%bpbi_pasta}}', [
            'id' => Schema::TYPE_PK,
            'id_pasta' => Schema::TYPE_INTEGER . ' null',
            'nome' => Schema::TYPE_STRING . ' not null',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
                ], $tableOptions);

        $this->addForeignKey('past_past_fk', '{{%bpbi_pasta}}', 'id_pasta', '{{%bpbi_pasta}}', 'id');

        $this->createTable('{{%bpbi_consulta}}', [
            'id' => Schema::TYPE_PK,
            'id_indicador' => Schema::TYPE_INTEGER . ' not null',
            'id_pasta' => Schema::TYPE_INTEGER . ' null',
            'nome' => Schema::TYPE_STRING . ' not null',
            'descricao' => Schema::TYPE_TEXT . ' null',
            'condicao' => Schema::TYPE_JSON,
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
                ], $tableOptions);

        $this->addForeignKey('cons_indi_fk', '{{%bpbi_consulta}}', 'id_indicador', '{{%bpbi_indicador}}', 'id');
        $this->addForeignKey('cons_past_fk', '{{%bpbi_consulta}}', 'id_pasta', '{{%bpbi_pasta}}', 'id');

        $this->createTable('{{%bpbi_consulta_item}}', [
            'id' => Schema::TYPE_PK,
            'id_consulta' => Schema::TYPE_INTEGER . ' not null',
            'id_campo' => Schema::TYPE_INTEGER . ' not null',
            'ordem' => Schema::TYPE_INTEGER . ' not null',
            'parametro' => Schema::TYPE_STRING . ' null',
            'ordenacao' => Schema::TYPE_INTEGER . ' not null',
            'tipo_grafico' => Schema::TYPE_STRING . ' null',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
                ], $tableOptions);

        $this->addForeignKey('coit_cons_fk', '{{%bpbi_consulta_item}}', 'id_consulta', '{{%bpbi_consulta}}', 'id');
        $this->addForeignKey('coit_camp_fk', '{{%bpbi_consulta_item}}', 'id_campo', '{{%bpbi_indicador_campo}}', 'id');

        $this->createTable('{{%bpbi_consulta_filtro_usuario}}', [
            'id' => Schema::TYPE_PK,
            'id_usuario' => Schema::TYPE_INTEGER . ' not null',
            'id_consulta' => Schema::TYPE_INTEGER . ' not null',
            'condicao' => Schema::TYPE_JSON,
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
                ], $tableOptions);

        $this->addForeignKey('fius_cons_fk', '{{%bpbi_consulta_filtro_usuario}}', 'id_consulta', '{{%bpbi_consulta}}', 'id');

        $this->createTable('{{%bpbi_consulta_grafico_usuario}}', [
            'id' => Schema::TYPE_PK,
            'id_usuario' => Schema::TYPE_INTEGER . ' not null',
            'id_consulta' => Schema::TYPE_INTEGER . ' not null',
            'campo' => Schema::TYPE_STRING . ' not null',
            'tipo_grafico' => Schema::TYPE_STRING . ' not null',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
                ], $tableOptions);

        $this->addForeignKey('grus_cons_fk', '{{%bpbi_consulta_grafico_usuario}}', 'id_consulta', '{{%bpbi_consulta}}', 'id');

        $this->createTable('{{%bpbi_ultima_consulta}}', [
            'id' => Schema::TYPE_PK,
            'id_usuario' => Schema::TYPE_INTEGER . ' not null',
            'id_consulta' => Schema::TYPE_INTEGER . ' not null',
            'index' => Schema::TYPE_INTEGER . ' not null',
            'token' => Schema::TYPE_TEXT . ' null',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
                ], $tableOptions);

        $this->addForeignKey('ulco_cons_fk', '{{%bpbi_ultima_consulta}}', 'id_consulta', '{{%bpbi_consulta}}', 'id');

        $sql_cache = <<<SQL

        CREATE TABLE bpbi_cache (
            id char(128) NOT NULL PRIMARY KEY,
            expire int(11),
            data MEDIUMBLOB
        );

SQL;

        $this->execute($sql_cache);

        return true;
    }

    public function safeDown() {
        $this->dropTable('{{%bpbi_ultima_consulta}}');

        $this->dropTable('{{%bpbi_consulta_grafico_usuario}}');

        $this->dropTable('{{%bpbi_consulta_filtro_usuario}}');

        $this->dropTable('{{%bpbi_consulta_item}}');

        $this->dropTable('{{%bpbi_consulta}}');

        $this->dropTable('{{%bpbi_pasta}}');

        $this->dropTable('{{%bpbi_indicador_campo}}');

        $this->dropTable('{{%bpbi_indicador}}');

        $this->dropTable('{{%bpbi_conexao}}');

        return true;
    }

}
