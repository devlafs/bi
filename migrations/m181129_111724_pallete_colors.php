<?php

use yii\db\Migration;
use yii\db\Schema;

class m181129_111724_pallete_colors extends Migration {

    public function safeUp() {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bpbi_pallete}}', [
            'id' => Schema::TYPE_PK,
            'nome' => Schema::TYPE_STRING . ' not null',
            'color1' => Schema::TYPE_STRING . ' not null',
            'color2' => Schema::TYPE_STRING . ' not null',
            'file' => Schema::TYPE_STRING . ' not null',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
                ], $tableOptions);

        Yii::$app->db->createCommand()->batchInsert('{{%bpbi_pallete}}', ['nome', 'color1', 'color2', 'file'], [
            ['Jiraya', '#c1232b', '#b5c334', 'infographic'],
            ['Orochimaru', '#2ec7c9', '#b6a2de', 'macarons'],
//            ['Gray', '#757575', '#c7c7c7', 'gray'],
            ['Zetsu', '#408829', '#68a54a', 'green'],
            ['Sasuke', '#1790cf', '#1bb2d8', 'blue'],
//            ['Red', '#d8361b', '#f16b4c', 'red'],
            ['Naruto', '#c12e34', '#e6b600', 'shine'],
            ['Nagato', '#44b7d3', '#e42b6d', 'helianthus'],
            ['Itachi', '#e01f54', '#b8d2c7', 'roma'],
//            ['Mint', '#8aedd5', '#93bc9e', 'mint'],
            ['Gaara', '#ed9678', '#e7dac9', 'macarons2'],
            ['Sakura', '#e52c3c', '#f7b1ab', 'sakura'],
//            ['Default', '#000', '#fff', 'default']
        ])->execute();

        $this->addColumn("{{%bpbi_consulta}}", "id_pallete", Schema::TYPE_INTEGER);

        $this->addForeignKey('cons_pall_fk', '{{%bpbi_consulta}}', 'id_pallete', '{{%bpbi_pallete}}', 'id');

        return true;
    }

    public function safeDown() {
        $this->dropForeignKey('cons_pall_fk', '{{%bpbi_consulta}}');
        $this->dropColumn('{{%bpbi_consulta}}', 'id_pallete');
        $this->dropTable('{{%bpbi_pallete}}');

        return true;
    }

}
