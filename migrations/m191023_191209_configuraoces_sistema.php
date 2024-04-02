<?php

use yii\db\Migration;
use yii\db\Schema;

class m191023_191209_configuraoces_sistema extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%bpbi_sistema}}','nome',Schema::TYPE_STRING);
        $this->addColumn('{{%bpbi_sistema}}','ordem',Schema::TYPE_INTEGER);
        $this->addColumn('{{%bpbi_sistema}}','visible',Schema::TYPE_BOOLEAN . ' not null default TRUE');

        $sql = <<<SQL

            UPDATE `bpbi_sistema` SET `nome`='Nome' WHERE `id`='1';
            UPDATE `bpbi_sistema` SET `nome`='Descrição' WHERE `id`='2';
            UPDATE `bpbi_sistema` SET `nome`='Logo' WHERE `id`='3';
            UPDATE `bpbi_sistema` SET `nome`='URL' WHERE `id`='4';
            UPDATE `bpbi_sistema` SET `nome`='Manutenção', `visible`='0' WHERE `id`='5';
            UPDATE `bpbi_sistema` SET `nome`='Email Suporte', `visible`='0' WHERE `id`='7';
            UPDATE `bpbi_sistema` SET `nome`='Versão', `visible`='0' WHERE `id`='8';
            UPDATE `bpbi_sistema` SET `nome`='Email Remetente' WHERE `id`='6';
            UPDATE `bpbi_sistema` SET `nome`='Palheta' WHERE `id`='9';
            UPDATE `bpbi_sistema` SET `nome`='Tempo de Expiração (URL)' WHERE `id`='10';
            UPDATE `bpbi_sistema` SET `nome`='Tempo de Expiração (Email)' WHERE `id`='11';
            INSERT INTO `bpbi_sistema` (`campo`, `valor`, `nome`, `visible`) VALUES ('advancedFilter', 'FALSE', 'Filtros Avançados', '1');
            INSERT INTO `bpbi_sistema` (`campo`, `valor`, `nome`, `visible`, `ordem`) VALUES ('homepage', '', 'Página Inicial', '1', '13');
            
            UPDATE `bpbi_sistema` SET `ordem`='1' WHERE `id`='1';
            UPDATE `bpbi_sistema` SET `ordem`='2' WHERE `id`='2';
            UPDATE `bpbi_sistema` SET `ordem`='3' WHERE `id`='3';
            UPDATE `bpbi_sistema` SET `ordem`='4' WHERE `id`='4';
            UPDATE `bpbi_sistema` SET `ordem`='5' WHERE `id`='6';
            UPDATE `bpbi_sistema` SET `ordem`='8' WHERE `id`='9';
            UPDATE `bpbi_sistema` SET `ordem`='6' WHERE `id`='10';
            UPDATE `bpbi_sistema` SET `ordem`='7' WHERE `id`='11';
            UPDATE `bpbi_sistema` SET `ordem`='9' WHERE `id`='12';
            
            ALTER TABLE `bpbi_sistema` MODIFY valor LONGTEXT NOT NULL;

SQL;

        $this->execute($sql);

        return true;
    }

    public function safeDown()
    {
        $this->dropColumn('{{%bpbi_sistema}}','nome');
        $this->dropColumn('{{%bpbi_sistema}}','ordem');
        $this->dropColumn('{{%bpbi_sistema}}','visible');

        return true;
    }
}
