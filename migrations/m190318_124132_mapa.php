<?php

use yii\db\Migration;
use yii\db\Schema;

class m190318_124132_mapa extends Migration {

    public function safeUp() {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bpbi_mapa}}', [
            'id' => Schema::TYPE_PK,
            'identificador' => Schema::TYPE_STRING . ' not null',
            'nome' => Schema::TYPE_STRING . ' not null',
            'descricao' => Schema::TYPE_TEXT . ' null',
            'latitude' => Schema::TYPE_DECIMAL . '(10, 8) not null',
            'longitude' => Schema::TYPE_DECIMAL . '(11, 8) not null',
            'zoom' => Schema::TYPE_INTEGER . ' not null',
            'corfundo_ativo' => Schema::TYPE_STRING . " not null default '#007EC3'",
            'corfundo_inativo' => Schema::TYPE_STRING . " not null default '#eceeef'",
            'corborda' => Schema::TYPE_STRING . " not null default '#004c89'",
            'file' => Schema::TYPE_STRING . ' not null',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
                ], $tableOptions);

        $this->createTable('{{%bpbi_mapa_campo}}', [
            'id' => Schema::TYPE_PK,
            'id_mapa' => Schema::TYPE_INTEGER . ' null',
            'id_campo' => Schema::TYPE_INTEGER . ' null',
            'tag' => Schema::TYPE_STRING . ' not null',
            'descricao' => Schema::TYPE_TEXT . ' null',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
                ], $tableOptions);

        $this->addForeignKey('mapcam_map_fk', '{{%bpbi_mapa_campo}}', 'id_mapa', '{{%bpbi_mapa}}', 'id');

        $this->addForeignKey('mapcam_indcam_fk', '{{%bpbi_mapa_campo}}', 'id_campo', '{{%bpbi_indicador_campo}}', 'id');

        return true;
    }

    public function safeDown() {
        $this->dropTable('{{%bpbi_mapa_campo}}');

        $this->dropTable('{{%bpbi_mapa}}');

        return true;
    }

}
