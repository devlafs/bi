<?php

use yii\db\Migration;
use yii\db\Schema;

class m191024_194005_perfil_complemento extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bpbi_perfil_complemento}}', [
            'id' => Schema::TYPE_PK,
            'id_perfil' => Schema::TYPE_INTEGER . ' not null',
            'pagina_inicial' => Schema::TYPE_INTEGER . ' null',
            'id_consulta' => Schema::TYPE_INTEGER . ' null',
            'id_painel' => Schema::TYPE_INTEGER . ' null'
        ], $tableOptions);

        $this->addForeignKey('percom_cons_fk', '{{%bpbi_perfil_complemento}}', 'id_consulta', '{{%bpbi_consulta}}', 'id');

        $this->addForeignKey('percom_pain_fk', '{{%bpbi_perfil_complemento}}', 'id_painel', '{{%bpbi_painel}}', 'id');

        return true;
    }

    public function safeDown()
    {
        $this->dropTable('{{%bpbi_perfil_complemento}}');

        return true;
    }
}
