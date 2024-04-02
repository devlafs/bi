<?php

use yii\db\Migration;
use yii\db\Schema;

class m200115_175847_planilha extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bpbi_metadado}}', [
            'id' => Schema::TYPE_PK,
            'nome' => Schema::TYPE_STRING . ' not null',
            'descricao' => Schema::TYPE_TEXT . ' null',
            'caminho' => Schema::TYPE_STRING . ' null',
            'is_incremental' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'executed_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
        ], $tableOptions);

        Yii::$app->db->createCommand()->batchInsert('{{%bpbi_permissao_geral}}',
            ['nome', 'descricao', 'gerenciador', 'constante', 'column'],
            [
                ['Visualizar', 'Possui permissão para visualizar os metadados existentes', '12. Metadados', '<controller>metadado</controller><action>index</action><action>view</action>', 1],
                ['Cadastrar', 'Possui permissão para cadastrar novos metadados', '12. Metadados', '<controller>metadado</controller><action>create</action>', 2],
                ['Alterar', 'Possui permissão para alterar os metadados existentes', '12. Metadados', '<controller>metadado</controller><action>update</action>', 3],
                ['Excluir', 'Possui permissão para excluir os metadados existentes', '12. Metadados', '<controller>metadado</controller><action>delete</action>', 4],
            ]
        )->execute();

        return true;
    }

    public function safeDown()
    {
        $this->dropTable('{{%bpbi_metadado}}');

        return true;
    }
}
