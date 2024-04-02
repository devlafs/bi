<?php

use yii\db\Migration;
use yii\db\Schema;

class m191026_200655_table_ajuda extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bpbi_ajuda_categoria}}', [
            'id' => Schema::TYPE_PK,
            'nome' => Schema::TYPE_STRING . ' not null',
            'ordem' => Schema::TYPE_INTEGER . ' not null'
        ], $tableOptions);

        $this->createTable('{{%bpbi_ajuda}}', [
            'id' => Schema::TYPE_PK,
            'id_categoria' => Schema::TYPE_INTEGER . ' not null',
            'titulo' => Schema::TYPE_STRING . ' not null',
            'ordem' => Schema::TYPE_INTEGER . ' not null',
            'texto' => 'LONGTEXT not null'
        ], $tableOptions);

        $this->addForeignKey('ajud_ajca_fk', '{{%bpbi_ajuda}}', 'id_categoria', '{{%bpbi_ajuda_categoria}}', 'id');

        return true;
    }

    public function safeDown()
    {
        $this->dropTable('{{%bpbi_ajuda}}');
        $this->dropTable('{{%bpbi_ajuda_categoria}}');

        return true;
    }
}
