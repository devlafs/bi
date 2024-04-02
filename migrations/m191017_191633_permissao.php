<?php

use yii\db\Migration;

class m191017_191633_permissao extends Migration
{
    public function safeUp()
    {
        $sql = <<<SQL
                
        UPDATE `bpbi_permissao_consulta` SET  `descricao`='Possui permissão para exportar os dados da consulta', `constante`='<controller>consulta</controller><action>export-pdf</action><action>export-excel</action><action>export-csv</action>', `gerenciador`='exportar' WHERE id = 6 AND id > 0;
            
SQL;
        Yii::$app->db->createCommand($sql)->execute();

        return true;
    }

    public function safeDown()
    {
        echo "m191017_191633_permissao cannot be reverted.\n";

        return true;
    }
}
