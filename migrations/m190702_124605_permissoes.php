<?php

use yii\db\Migration;

class m190702_124605_permissoes extends Migration
{
    public function safeUp()
    {
        $sql = <<<SQL
                
        UPDATE `bpbi_permissao_consulta` SET  `descricao`='Possui permissão para exportar os dados da consulta', `constante`='<controller>consulta</controller><action>export-pdf</action><action>export-excel</action><action>export-csv</action>' WHERE `gerenciador`='exportar' AND id > 0;
        UPDATE `bpbi_permissao_geral` SET  `constante`='<controller>painel</controller><action>alterar</action><action>permission-painel</action><action>save-config-painel</action>' WHERE `gerenciador`='3. Painéis' AND `nome`='Alterar' AND id > 0;
            
SQL;
        Yii::$app->db->createCommand($sql)->execute();
        
        Yii::$app->db->createCommand()->batchInsert('{{%bpbi_permissao_geral}}', 
            ['nome', 'descricao', 'gerenciador', 'constante', 'column'],
            [
                ['Agendar carga', 'Possui permissão para agendar a carga de dados para próxima execução', '5. Cubos', '<controller>indicador</controller><action>carga</action>', 6],

                ['Visualizar', 'Possui permissão para visualizar os templates existentes', '10. Temp. de Emails', '<controller>template-email</controller><action>index</action><action>view</action>', 1],
                ['Cadastrar', 'Possui permissão para cadastrar novos templates', '10. Temp. de Emails', '<controller>template-email</controller><action>create</action>', 2],
                ['Alterar', 'Possui permissão para alterar os templates existentes', '10. Temp. de Emails', '<controller>template-email</controller><action>update</action>', 3],
                ['Excluir', 'Possui permissão para excluir os templates existentes', '10. Temp. de Emails', '<controller>template-email</controller><action>delete</action>', 4],

                ['Visualizar', 'Possui permissão para visualizar os logs de atividades', '11. Logs', '<controller>log</controller><action>index</action><action>view</action>', 1],
            ]
        )->execute();
        
        return true;
    }

    public function safeDown()
    {
        echo "m190702_124605_permissoes cannot be reverted.\n";

        return true;
    }
}
