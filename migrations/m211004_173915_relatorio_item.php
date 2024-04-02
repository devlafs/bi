<?php

use yii\db\Migration;
use yii\db\Schema;

class m211004_173915_relatorio_item extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bpbi_relatorio_data_item}}', [
            'id' => Schema::TYPE_PK,
            'id_relatorio_data' => Schema::TYPE_INTEGER . ' not null',
            'id_campo' => Schema::TYPE_INTEGER . ' not null',
            'ordem' => Schema::TYPE_INTEGER . ' not null',
            'parametro' => Schema::TYPE_STRING . ' null',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
        ], $tableOptions);

        $this->addForeignKey('redi_reda_fk', '{{%bpbi_relatorio_data_item}}', 'id_relatorio_data', '{{%bpbi_relatorio_data}}', 'id');
        $this->addForeignKey('redi_reca_fk', '{{%bpbi_relatorio_data_item}}', 'id_campo', '{{%bpbi_relatorio_campo}}', 'id');

        $this->addColumn('{{%bpbi_relatorio_data}}', 'condicao', Schema::TYPE_JSON);

        return true;
    }

    public function safeDown()
    {
        $this->dropTable('{{%bpbi_relatorio_data_item}}');
        $this->dropColumn('{{%bpbi_relatorio_data}}', 'condicao');

        return true;
    }
}
