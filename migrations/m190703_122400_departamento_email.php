<?php

use yii\db\Migration;
use yii\db\Schema;

class m190703_122400_departamento_email extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%bpbi_email}}', 'id_departamento', Schema::TYPE_INTEGER);
        
        $this->addColumn('{{%bpbi_email}}', 'departamento', Schema::TYPE_STRING);

        return true;
    }

    public function safeDown()
    {
        $this->dropColumn('{{%bpbi_email}}', 'id_departamento');
        
        $this->dropColumn('{{%bpbi_email}}', 'departamento');

        return true;
    }
}
