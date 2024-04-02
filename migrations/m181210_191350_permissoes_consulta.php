<?php

use yii\db\Migration;

class m181210_191350_permissoes_consulta extends Migration {

    public function safeUp() {
        Yii::$app->db->createCommand()->batchInsert('{{%bpbi_permissao_consulta}}', ['nome', 'descricao', 'gerenciador', 'constante'], [
            ['1. Visualizar Consulta', 'Possui permissão para visualizar a consulta', 'visualizar', '<controller>consulta</controller><action>visualizar</action>'],
            ['2. Personalizar Filtros', 'Possui permissão para aplicar filtros personalizados na consulta', 'filtro', '<controller>ajax</controller><action>open-filter-view</action><action>and-filter-view</action><action>or-filter-view</action><action>get-type-view</action><action>get-field-view</action><action>save-filter-view</action>'],
            ['3. Personalizar Gráficos', 'Possui permissão para aplicar gráficos personalizados na consulta', 'grafico', '<controller>ajax</controller><action>change-user-graph</action>'],
            ['4. Gerar Url Pública', 'Possui permissão para gerar uma URL pública para visualização da consulta', 'url', '<controller>ajax</controller><action>generate-url-publica</action>'],
            ['5. Compartilhar por email', 'Possui permissão para compartilhar a consulta por email', 'email', '<controller>ajax</controller><action>send-url-publica</action>']
                ]
        )->execute();

        return true;
    }

    public function safeDown() {
        echo "m181210_191350_permissoes_consulta cannot be reverted.\n";

        return true;
    }

}
