<?php

use yii\db\Migration;
use yii\db\Schema;

class m181128_114844_table_padrao extends Migration {

    public function safeUp() {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bpbi_sistema}}', [
            'id' => Schema::TYPE_PK,
            'campo' => Schema::TYPE_STRING . ' not null',
            'valor' => Schema::TYPE_STRING . ' not null'
                ], $tableOptions);

        Yii::$app->db->createCommand()->batchInsert('{{%bpbi_sistema}}', ['campo', 'valor'], [
            ['name', 'Corretora Unimed'],
            ['description', 'Corretora de seguros em Goiânia, Goiás'],
            ['logo', 'logo.png'],
            ['url', 'bpbi.dev.local'],
            ['maintence', 'FALSE'],
            ['systemEmail', 'bp1@bpone.com.br'],
            ['supportEmail', 'suporte@bpone.com.br'],
            ['version', '1.0'],
            ['pallete', '1'],
            ['urlShareDaysExpiration', '7'],
            ['emailShareDaysExpiration', '7'],
        ])->execute();

        return true;
    }

    public function safeDown() {
        $this->dropTable('{{%bpbi_sistema}}');

        return true;
    }

}
