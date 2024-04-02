<?php

use yii\db\Migration;

class m181229_223333_permissao_print extends Migration {

    public function safeUp() {
        Yii::$app->db->createCommand()->batchInsert('{{%bpbi_permissao_consulta}}', ['nome', 'descricao', 'gerenciador', 'constante'], [
            ['6. Exportar Dados', 'Possui permissão para exportar os dados a consulta', 'exportar', '<controller>consulta</controller><action>export-pdf</action><action>export-excel</action>'],
                ]
        )->execute();

        return true;
    }

    public function safeDown() {
        echo "m181229_223333_permissao_print cannot be reverted.\n";

        return true;
    }

}
