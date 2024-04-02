<?php

use yii\db\Migration;
use yii\db\Schema;

class m200122_121440_config_color extends Migration
{
    public function safeUp() {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bpbi_consulta_item_cor}}', [
            'id' => Schema::TYPE_PK,
            'ordem' => Schema::TYPE_INTEGER . ' not null',
            'id_consulta' => Schema::TYPE_INTEGER . ' not null',
            'id_campo' => Schema::TYPE_INTEGER . ' not null',
            'valor' => Schema::TYPE_STRING . ' not null',
            'cor' => Schema::TYPE_STRING . ' not null',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
        ], $tableOptions);

        $this->addForeignKey('coicor_cons_fk', '{{%bpbi_consulta_item_cor}}', 'id_consulta', '{{%bpbi_consulta}}', 'id');

        $this->addForeignKey('coicor_camp_fk', '{{%bpbi_consulta_item_cor}}', 'id_campo', '{{%bpbi_indicador_campo}}', 'id');

        $sql = <<<SQL

        UPDATE `bpbi_permissao_geral` SET `constante`='<controller>consulta</controller><action>alterar</action><action>preview</action><action>update-pallete</action><action>open-filter-update</action><action>and-filter-update</action><action>or-filter-update</action><action>get-type-update</action><action>get-field-update</action><action>save-filter-update</action><action>permission-consulta</action><action>save-config-consulta</action><action>config-field</action><action>salvar-configuracoes</action><action>config-color</action><action>config-color-row</action><action>salvar-cores</action>' WHERE `nome`='Alterar' AND `gerenciador` = '2. Consultas' AND id > 0;

SQL;
        $this->execute($sql);

        return true;
    }

    public function safeDown() {
        $this->dropTable('{{%bpbi_consulta_item_cor}}');

        return true;
    }
}
