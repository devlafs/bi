<?php

use yii\db\Migration;

class m181228_185356_duplicar_consulta extends Migration {

    public function safeUp() {
        Yii::$app->db->createCommand()->batchInsert('{{%bpbi_permissao_geral}}', ['nome', 'descricao', 'gerenciador', 'constante'], [
            ['Duplicar', 'Possui permissão para duplicar as consultas existentes', 'Consulta', '<controller>ajax</controller><action>duplicate-consulta</action>']
                ]
        )->execute();

        return true;
    }

    public function safeDown() {
        echo "m181228_185356_duplicar_consulta cannot be reverted.\n";

        return true;
    }

}
