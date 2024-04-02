<?php

use yii\db\Migration;
use yii\db\Schema;
use app\models\PermissaoGeral;

class m190122_140715_alteracoes_painel extends Migration {

    public function safeUp() {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bpbi_permissao_painel}}', [
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

        $this->createTable('{{%bpbi_painel_permissao}}', [
            'id' => Schema::TYPE_PK,
            'id_painel' => Schema::TYPE_INTEGER . ' not null',
            'id_permissao' => Schema::TYPE_INTEGER . ' not null',
            'id_perfil' => Schema::TYPE_INTEGER . ' not null',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
                ], $tableOptions);

        $this->addForeignKey('pape_pain_fk', '{{%bpbi_painel_permissao}}', 'id_painel', '{{%bpbi_painel}}', 'id');

        $this->addForeignKey('pape_pepa_fk', '{{%bpbi_painel_permissao}}', 'id_permissao', '{{%bpbi_permissao_painel}}', 'id');

        Yii::$app->db->createCommand()->batchInsert('{{%bpbi_permissao_geral}}', ['nome', 'descricao', 'gerenciador', 'constante'], [
            ['Cadastrar', 'Possui permissão para cadastrar novos painéis', 'Painel', '<controller>ajax</controller><action>painel</action>'],
            ['Alterar', 'Possui permissão para alterar os painéis existentes', 'Painel', '<controller>painel</controller><action>alterar</action><action>permission-painel</action>'],
            ['Duplicar', 'Possui permissão para duplicar os painéis existentes', 'Painel', '<controller>ajax</controller><action>duplicate-painel</action>'],
            ['Excluir', 'Possui permissão para excluir os painéis existentes', 'Painel', '<controller>ajax</controller><action>delete-painel</action>'],
                ]
        )->execute();

        PermissaoGeral::updateAll([
            'nome' => 'Duplicar',
            'gerenciador' => 'gerenciador'
                ], [
            'constante' => '<controller>ajax</controller><action>duplicate-consulta</action>'
        ]);

        $this->dropTable('{{%bpbi_ultima_consulta}}');

        $this->createTable('{{%bpbi_ultima_tela_acesso}}', [
            'id' => Schema::TYPE_PK,
            'id_usuario' => Schema::TYPE_INTEGER . ' not null',
            'view' => Schema::TYPE_INTEGER . ' not null',
            'id_consulta' => Schema::TYPE_INTEGER . ' null',
            'id_painel' => Schema::TYPE_INTEGER . ' null',
            'index' => Schema::TYPE_INTEGER . ' not null',
            'token' => Schema::TYPE_TEXT . ' null',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
                ], $tableOptions);

        $this->addForeignKey('ulta_cons_fk', '{{%bpbi_ultima_tela_acesso}}', 'id_consulta', '{{%bpbi_consulta}}', 'id');

        $this->addForeignKey('ulta_pain_fk', '{{%bpbi_ultima_tela_acesso}}', 'id_painel', '{{%bpbi_painel}}', 'id');

        $this->execute("ALTER TABLE bpbi_url_share MODIFY COLUMN id_consulta " . Schema::TYPE_INTEGER);
        $this->addColumn('{{%bpbi_url_share}}', 'view', Schema::TYPE_INTEGER . ' not null default 1');
        $this->addColumn('{{%bpbi_url_share}}', 'id_painel', Schema::TYPE_INTEGER . ' null');

        $this->addForeignKey('ursh_pain_fk', '{{%bpbi_url_share}}', 'id_painel', '{{%bpbi_painel}}', 'id');

        $this->execute("ALTER TABLE bpbi_email MODIFY COLUMN id_consulta " . Schema::TYPE_INTEGER);
        $this->addColumn('{{%bpbi_email}}', 'view', Schema::TYPE_INTEGER . ' not null default 1');
        $this->addColumn('{{%bpbi_email}}', 'id_painel', Schema::TYPE_INTEGER . ' null');
        $this->addColumn('{{%bpbi_email}}', 'sent_at', Schema::TYPE_TIMESTAMP . ' null');

        $this->addForeignKey('emai_pain_fk', '{{%bpbi_email}}', 'id_painel', '{{%bpbi_painel}}', 'id');

        return true;
    }

    public function safeDown() {
        $this->dropTable('{{%bpbi_painel_permissao}}');
        $this->dropTable('{{%bpbi_permissao_painel}}');

        return true;
    }

}
