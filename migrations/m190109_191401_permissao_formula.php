<?php

use yii\db\Migration;

class m190109_191401_permissao_formula extends Migration {

    public function safeUp() {
        Yii::$app->db->createCommand()->batchInsert('{{%bpbi_permissao_geral}}', ['nome', 'descricao', 'gerenciador', 'constante'], [
            ['Cadastrar Fórmulas', 'Possui permissão para cadastrar novas fórmulas nos cubos', 'Campos de Cubos', '<controller>indicador-campo</controller><action>create</action>'],
                ]
        )->execute();

        return true;
    }

    public function safeDown() {
        echo "m190109_191401_permissao_formula cannot be reverted.\n";

        return true;
    }

}
