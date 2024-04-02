<?php

use yii\db\Migration;
use yii\db\Schema;

class m210901_173408_permissao_rel2 extends Migration
{
    public function safeUp()
    {
        $this->addColumn("{{%bpbi_ultima_tela_acesso}}", "id_relatorio_data", Schema::TYPE_INTEGER . ' null');
        $this->addColumn("{{%bpbi_perfil_complemento}}", "id_relatorio_data", Schema::TYPE_INTEGER . ' null');
        $this->addColumn("{{%bpbi_url_share}}", "id_relatorio_data", Schema::TYPE_INTEGER . ' null');

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bpbi_relatorio_data}}', [
            'id' => Schema::TYPE_PK,
            'id_relatorio' => Schema::TYPE_INTEGER . ' not null',
            'id_pasta' => Schema::TYPE_INTEGER . ' null',
            'nome' => Schema::TYPE_STRING . ' not null',
            'javascript' => Schema::TYPE_TEXT,
            'descricao' => Schema::TYPE_TEXT . ' null',
            'limite' => Schema::TYPE_TEXT . ' null',
            'email_externo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'tempo_expiracao_email' => Schema::TYPE_INTEGER . ' null',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
        ], $tableOptions);

        $this->addForeignKey('reda_rela_fk', '{{%bpbi_relatorio_data}}', 'id_relatorio', '{{%bpbi_relatorio}}', 'id');
        $this->addForeignKey('reda_past_fk', '{{%bpbi_relatorio_data}}', 'id_pasta', '{{%bpbi_pasta}}', 'id');

        $this->createTable('{{%bpbi_permissao_relatorio}}', [
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

        $this->createTable('{{%bpbi_relatorio_permissao}}', [
            'id' => Schema::TYPE_PK,
            'id_relatorio_data' => Schema::TYPE_INTEGER . ' not null',
            'id_permissao' => Schema::TYPE_INTEGER . ' not null',
            'id_perfil' => Schema::TYPE_INTEGER . ' not null',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
        ], $tableOptions);

        $this->addForeignKey('copr_rela_fk', '{{%bpbi_relatorio_permissao}}', 'id_relatorio_data', '{{%bpbi_relatorio_data}}', 'id');

        $this->addForeignKey('copr_peco_fk', '{{%bpbi_relatorio_permissao}}', 'id_permissao', '{{%bpbi_permissao_relatorio}}', 'id');

        Yii::$app->db->createCommand()->batchInsert('{{%bpbi_permissao_relatorio}}', ['nome', 'descricao', 'gerenciador', 'constante'], [
                ['1. Visualizar Relatório', 'Possui permissão para visualizar o relatório', 'visualizar', '<controller>relatorio-data</controller><action>visualizar</action>'],
                ['2. Gerar Url Pública', 'Possui permissão para gerar uma URL pública para visualização do relatório', 'url', '<controller>ajax</controller><action>generate-url-publica</action>'],
                ['3. Compartilhar por email', 'Possui permissão para compartilhar o relatório por email', 'email', '<controller>ajax</controller><action>send-url-publica</action>']
            ]
        )->execute();

        return true;
    }

    public function safeDown()
    {
        $this->dropColumn("{{%bpbi_ultima_tela_acesso}}", "id_relatorio_data");
        $this->dropColumn("{{%bpbi_perfil_complemento}}", "id_relatorio_data");
        $this->dropColumn("{{%bpbi_url_share}}", "id_relatorio_data");

        $this->dropTable('{{%bpbi_relatorio_permissao}}');
        $this->dropTable('{{%bpbi_permissao_relatorio}}');
        $this->dropTable('{{%bpbi_relatorio_data}}');

        return true;
    }
}
