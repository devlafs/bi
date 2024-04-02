<?php

use yii\db\Migration;
use yii\db\Schema;

class m181218_182430_link_campo extends Migration {

    public function safeUp() {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->addColumn('{{%bpbi_indicador_campo}}', 'link', Schema::TYPE_TEXT . ' null');

        $this->createTable('{{%bpbi_consulta_item_configuracao}}', [
            'id' => Schema::TYPE_PK,
            'ordem' => Schema::TYPE_INTEGER . ' not null',
            'id_consulta' => Schema::TYPE_INTEGER . ' not null',
            'id_item' => Schema::TYPE_INTEGER . ' not null',
            'id_campo' => Schema::TYPE_INTEGER . ' not null',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
                ], $tableOptions);

        $this->addForeignKey('coic_cons_fk', '{{%bpbi_consulta_item_configuracao}}', 'id_consulta', '{{%bpbi_consulta}}', 'id');

        $this->addForeignKey('coic_item_fk', '{{%bpbi_consulta_item_configuracao}}', 'id_item', '{{%bpbi_indicador_campo}}', 'id');

        $this->addForeignKey('coic_camp_fk', '{{%bpbi_consulta_item_configuracao}}', 'id_campo', '{{%bpbi_indicador_campo}}', 'id');

        return true;
    }

    public function safeDown() {
        $this->dropTable('{{%bpbi_consulta_item_configuracao}}');

        $this->dropColumn('{{%bpbi_indicador_campo}}', 'link');

        return true;
    }

}
