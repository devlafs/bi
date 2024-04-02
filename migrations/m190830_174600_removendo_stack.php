<?php

use yii\db\Migration;

class m190830_174600_removendo_stack extends Migration
{
    public function safeUp()
    {
        $sql = <<<SQL

        UPDATE bpbi_grafico_configuracao
        SET
            data_serie = REPLACE(data_serie,
                'stack: \'true\'',
                'stack: \'false\'')
        WHERE
            data_serie LIKE '%stack: \'true\'%'
            AND id > 0;
SQL;

        $this->execute($sql);

        return true;
    }

    public function safeDown()
    {
        echo "m190830_174600_removendo_stack cannot be reverted.\n";

        return true;
    }
}
