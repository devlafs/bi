<?php

use yii\db\Migration;
use yii\db\Schema;
use app\models\PerfilPermissao;
use app\models\PermissaoGeral;

class m190129_115006_permissoes extends Migration {

    public function safeUp() {
        $this->addColumn('{{%bpbi_permissao_geral}}', 'column', Schema::TYPE_INTEGER);

        PerfilPermissao::deleteAll();

        PermissaoGeral::deleteAll();

        $sql = <<<SQL
                
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Visualizar','Possui permissão para visualizar as conexões existentes','7. Conexões','<controller>conexao</controller><action>index</action><action>view</action>',1,0,NULL,NULL,NULL,NULL,1);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Cadastrar','Possui permissão para cadastrar novas conexões','7. Conexões','<controller>conexao</controller><action>create</action>',1,0,NULL,NULL,NULL,NULL,2);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Alterar','Possui permissão para alterar as conexões existentes','7. Conexões','<controller>conexao</controller><action>update</action>',1,0,NULL,NULL,NULL,NULL,3);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Excluir','Possui permissão para excluir as conexões existentes','7. Conexões','<controller>conexao</controller><action>delete</action>',1,0,NULL,NULL,NULL,NULL,4);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Cadastrar','Possui permissão para cadastrar novas consultas','2. Consultas','<controller>ajax</controller><action>consulta</action>',1,0,NULL,NULL,NULL,NULL,2);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Alterar','Possui permissão para alterar as consultas existentes','2. Consultas','<controller>consulta</controller><action>alterar</action><action>preview</action><action>update-pallete</action><action>open-filter-update</action><action>and-filter-update</action><action>or-filter-update</action><action>get-type-update</action><action>get-field-update</action><action>save-filter-update</action><action>permission-consulta</action>',1,0,NULL,NULL,NULL,NULL,3);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Visualizar','Possui permissão para visualizar os emails automáticos','4. Emails','<controller>email</controller><action>index</action><action>view</action>',1,0,NULL,NULL,NULL,NULL,1);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Cadastrar','Possui permissão para cadastrar novos emails automáticos','4. Emails','<controller>email</controller><action>create</action>',1,0,NULL,NULL,NULL,NULL,2);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Alterar','Possui permissão para alterar os emails automáticos','4. Emails','<controller>email</controller><action>update</action>',1,0,NULL,NULL,NULL,NULL,3);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Excluir','Possui permissão para excluir os emails automáticos','4. Emails','<controller>email</controller><action>delete</action>',1,0,NULL,NULL,NULL,NULL,4);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Visualizar','Possui permissão para visualizar os campos dos cubos','6. Config. de Campos','<controller>indicador-campo</controller><action>index</action><action>view</action>',1,0,NULL,NULL,NULL,NULL,1);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Alterar','Possui permissão para alterar os campos dos cubos','6. Config. de Campos','<controller>indicador-campo</controller><action>update</action>',1,0,NULL,NULL,NULL,NULL,3);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Excluir','Possui permissão para excluir os campos dos cubos','6. Config. de Campos','<controller>indicador-campo</controller><action>delete</action>',1,0,NULL,NULL,NULL,NULL,4);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Visualizar','Possui permissão para visualizar os cubos existentes','5. Cubos','<controller>indicador</controller><action>index</action><action>view</action>',1,0,NULL,NULL,NULL,NULL,1);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Cadastrar','Possui permissão para cadastrar novos cubos','5. Cubos','<controller>indicador</controller><action>create</action>',1,0,NULL,NULL,NULL,NULL,2);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Alterar','Possui permissão para alterar os cubos existentes','5. Cubos','<controller>indicador</controller><action>update</action>',1,0,NULL,NULL,NULL,NULL,3);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Inativar','Possui permissão para alterar os status dos cubos existentes','5. Cubos','<controller>indicador</controller><action>status</action>',1,0,NULL,NULL,NULL,NULL,5);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Excluir','Possui permissão para excluir os cubos existentes','5. Cubos','<controller>indicador</controller><action>delete</action>',1,0,NULL,NULL,NULL,NULL,4);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Visualizar','Possui permissão para visualizar os perfis existentes','9. Perfis','<controller>perfil</controller><action>index</action><action>view</action>',1,0,NULL,NULL,NULL,NULL,1);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Cadastrar','Possui permissão para cadastrar novos perfis','9. Perfis','<controller>perfil</controller><action>create</action>',1,0,NULL,NULL,NULL,NULL,2);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Alterar','Possui permissão para alterar os perfis existentes','9. Perfis','<controller>perfil</controller><action>update</action>',1,0,NULL,NULL,NULL,NULL,3);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Excluir','Possui permissão para excluir os perfis existentes','9. Perfis','<controller>perfil</controller><action>delete</action>',1,0,NULL,NULL,NULL,NULL,4);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Visualizar','Possui permissão para visualizar os usuários existentes','8. Usuários','<controller>usuario</controller><action>index</action><action>view</action>',1,0,NULL,NULL,NULL,NULL,1);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Cadastrar','Possui permissão para cadastrar novos usuários','8. Usuários','<controller>usuario</controller><action>create</action>',1,0,NULL,NULL,NULL,NULL,2);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Alterar','Possui permissão para alterar os usuários existentes','8. Usuários','<controller>usuario</controller><action>update</action>',1,0,NULL,NULL,NULL,NULL,3);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Excluir','Possui permissão para excluir os usuário existentes','8. Usuários','<controller>usuario</controller><action>delete</action>',1,0,NULL,NULL,NULL,NULL,4);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Cadastrar/Alterar','Possui permissão para cadastrar/alterar pastas','1. Menus','<controller>ajax</controller><action>pasta</action>',1,0,NULL,NULL,NULL,NULL,2);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Mover','Possui permissão para mover as pastas/consultas existentes','1. Menus','<controller>ajax</controller><action>move-menu</action>',1,0,NULL,NULL,NULL,NULL,5);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Excluir','Possui permissão para excluir as pastas existentes','1. Menus','<controller>ajax</controller><action>delete-folder</action>',1,0,NULL,NULL,NULL,NULL,4);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Redefinir senha','Possui permissão para enviar redefinição de senha para os usuários existentes','8. Usuários','<controller>usuario</controller><action>password</action>',1,0,NULL,NULL,NULL,NULL,5);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Duplicar','Possui permissão para duplicar as consultas existentes','2. Consultas','<controller>ajax</controller><action>duplicate-consulta</action>',1,0,NULL,NULL,NULL,NULL,5);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Fórmulas','Possui permissão para cadastrar novas fórmulas nos indicadores','6. Config. de Campos','<controller>indicador-campo</controller><action>create</action>',1,0,NULL,NULL,NULL,NULL,2);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Cadastrar','Possui permissão para cadastrar novos painéis','3. Painéis','<controller>ajax</controller><action>painel</action>',1,0,NULL,NULL,NULL,NULL,2);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Alterar','Possui permissão para alterar os painéis existentes','3. Painéis','<controller>painel</controller><action>alterar</action><action>permission-painel</action>',1,0,NULL,NULL,NULL,NULL,3);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Duplicar','Possui permissão para duplicar os painéis existentes','3. Painéis','<controller>ajax</controller><action>duplicate-painel</action>',1,0,NULL,NULL,NULL,NULL,5);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Excluir','Possui permissão para excluir os painéis existentes','3. Painéis','<controller>ajax</controller><action>delete-painel</action>',1,0,NULL,NULL,NULL,NULL,4);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Visualizar','Possui permissão para visualizar as consultas existentes','2. Consultas','<controller>consulta</controller><action>visualizar</action>',1,0,NULL,NULL,NULL,NULL,1);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Visualizar','Possui permissão para visualizar os painéis existentes','3. Painéis','<controller>painel</controller><action>visualizar</action>',1,0,NULL,NULL,NULL,NULL,1);
            INSERT INTO `bpbi_permissao_geral` (`nome`,`descricao`,`gerenciador`,`constante`,`is_ativo`,`is_excluido`,`created_at`,`updated_at`,`created_by`,`updated_by`,`column`) VALUES ('Excluir','Possui permissão para excluir as consultas existentes','2. Consultas','<controller>ajax</controller><action>delete-consulta</action>',1,0,NULL,NULL,NULL,NULL,4);
                
SQL;
        $this->execute($sql);

        return true;
    }

    public function safeDown() {
        $this->dropColumn('{{%bpbi_permissao_geral}}', 'column');

        return true;
    }

}