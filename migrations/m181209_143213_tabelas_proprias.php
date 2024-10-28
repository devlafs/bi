<?php

use yii\db\Migration;
use yii\db\Schema;

class m181209_143213_tabelas_proprias extends Migration {

    public function safeUp() {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%admin_perfil}}', [
            'id' => Schema::TYPE_PK,
            'nome' => Schema::TYPE_STRING . ' not null',
            'descricao' => Schema::TYPE_TEXT . ' null',
            'is_admin' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'acesso_bi' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
        ], $tableOptions);

        $this->createTable('{{%admin_usuario}}', [
            'id' => Schema::TYPE_PK,
            'nome' => Schema::TYPE_STRING . ' not null',
            'nomeResumo' => Schema::TYPE_STRING . ' not null',
            'login' => Schema::TYPE_STRING . ' not null',
            'email' => Schema::TYPE_STRING . ' not null',
            'senha' => Schema::TYPE_STRING . ' not null',
            'perfil_id' => Schema::TYPE_INTEGER . ' not null',
            'cargo' => Schema::TYPE_STRING . ' null',
            'departamento' => Schema::TYPE_STRING . ' null',
            'celular' => Schema::TYPE_STRING . ' null',
            'status' => Schema::TYPE_STRING . ' null',
            'obs' => Schema::TYPE_TEXT . ' null',
            'acesso_bi' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_ativo' => Schema::TYPE_BOOLEAN . ' not null default TRUE',
            'is_excluido' => Schema::TYPE_BOOLEAN . ' not null default FALSE',
            'created_at' => Schema::TYPE_TIMESTAMP . ' null',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' null',
            'created_by' => Schema::TYPE_INTEGER . ' null',
            'updated_by' => Schema::TYPE_INTEGER . ' null',
        ], $tableOptions);

        $this->addForeignKey('adus_adpe_fk', '{{%admin_usuario}}', 'perfil_id', '{{%admin_perfil}}', 'id');

        $this->execute("CREATE TABLE `rba_acesso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admusua_id` int(11) NOT NULL,
  `dthr_login` datetime NOT NULL,
  `desc_ip` varchar(15) NOT NULL,
  `desc_useragent` varchar(200) NOT NULL,
  `desc_data` varchar(20) DEFAULT NULL,
  `bpbi` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `admusua_id` (`admusua_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8891 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;");

        return true;
    }

    public function safeDown() {
        $this->dropTable('{{%admin_usuario}}');
        $this->dropTable('{{%admin_perfil}}');

        return true;
    }

}
