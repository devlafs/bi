<?php

use yii\db\Migration;

class m181210_103837_permissoes_gerais extends Migration {

    public function safeUp() {
        Yii::$app->db->createCommand()->batchInsert('{{%bpbi_permissao_geral}}', ['nome', 'descricao', 'gerenciador', 'constante'], [
            ['Visualizar', 'Possui permissão para visualizar as conexões existentes', 'Conexão', '<controller>conexao</controller><action>index</action><action>view</action>'],
            ['Cadastrar', 'Possui permissão para cadastrar novas conexões', 'Conexão', '<controller>conexao</controller><action>create</action>'],
            ['Alterar', 'Possui permissão para alterar as conexões existentes', 'Conexão', '<controller>conexao</controller><action>update</action>'],
            ['Excluir', 'Possui permissão para excluir as conexões existentes', 'Conexão', '<controller>conexao</controller><action>delete</action>'],
            ['Cadastrar', 'Possui permissão para cadastrar novas consultas', 'Consulta', '<controller>ajax</controller><action>consulta</action>'],
            ['Alterar', 'Possui permissão para alterar as consultas existentes', 'Consulta', '<controller>consulta</controller><action>alterar</action><action>preview</action><action>update-pallete</action><action>open-filter-update</action><action>and-filter-update</action><action>or-filter-update</action><action>get-type-update</action><action>get-field-update</action><action>save-filter-update</action><action>permission-consulta</action><action>config-field</action><action>salvar-configuracoes</action>'],
            ['Excluir', 'Possui permissão para excluir as consultas existentes', 'Consulta', '<controller>ajax</controller><action>delete-consulta</action>'],
            ['Visualizar', 'Possui permissão para visualizar os emails automáticos', 'Email automático', '<controller>email</controller><action>index</action><action>view</action>'],
            ['Cadastrar', 'Possui permissão para cadastrar novos emails automáticos', 'Email automático', '<controller>email</controller><action>create</action>'],
            ['Alterar', 'Possui permissão para alterar os emails automáticos', 'Email automático', '<controller>email</controller><action>update</action>'],
            ['Excluir', 'Possui permissão para excluir os emails automáticos', 'Email automático', '<controller>email</controller><action>delete</action>'],
            ['Visualizar', 'Possui permissão para visualizar os campos dos cubos', 'Campos de Cubos', '<controller>indicador-campo</controller><action>index</action><action>view</action>'],
            ['Alterar', 'Possui permissão para alterar os campos dos cubos', 'Campos de Cubos', '<controller>indicador-campo</controller><action>update</action>'],
            ['Excluir', 'Possui permissão para excluir os campos dos cubos', 'Campos de Cubos', '<controller>indicador-campo</controller><action>delete</action>'],
            ['Visualizar', 'Possui permissão para visualizar os cubos existentes', 'Cubos', '<controller>indicador</controller><action>index</action><action>view</action>'],
            ['Cadastrar', 'Possui permissão para cadastrar novos cubos', 'Cubos', '<controller>indicador</controller><action>create</action>'],
            ['Alterar', 'Possui permissão para alterar os cubos existentes', 'Cubos', '<controller>indicador</controller><action>update</action>'],
            ['Inativar', 'Possui permissão para alterar os status dos cubos existentes', 'Indicador', '<controller>indicador</controller><action>status</action>'],
            ['Excluir', 'Possui permissão para excluir os cubos existentes', 'Cubos', '<controller>indicador</controller><action>delete</action>'],
            ['Visualizar', 'Possui permissão para visualizar os perfis existentes', 'Perfil', '<controller>perfil</controller><action>index</action><action>view</action>'],
            ['Cadastrar', 'Possui permissão para cadastrar novos perfis', 'Perfil', '<controller>perfil</controller><action>create</action>'],
            ['Alterar', 'Possui permissão para alterar os perfis existentes', 'Perfil', '<controller>perfil</controller><action>update</action>'],
            ['Excluir', 'Possui permissão para excluir os perfis existentes', 'Perfil', '<controller>perfil</controller><action>delete</action>'],
            ['Visualizar', 'Possui permissão para visualizar os usuários existentes', 'Usuário', '<controller>usuario</controller><action>index</action><action>view</action>'],
            ['Cadastrar', 'Possui permissão para cadastrar novos usuários', 'Usuário', '<controller>usuario</controller><action>create</action>'],
            ['Alterar', 'Possui permissão para alterar os usuários existentes', 'Usuário', '<controller>usuario</controller><action>update</action>'],
            ['Senha', 'Possui permissão para enviar redefinição de senha para os usuários existentes', 'Usuário', '<controller>usuario</controller><action>password</action>'],
            ['Excluir', 'Possui permissão para excluir os usuário existentes', 'Usuário', '<controller>usuario</controller><action>delete</action>'],
            ['Cadastrar/Alterar', 'Possui permissão para cadastrar/alterar pastas', 'Menu', '<controller>ajax</controller><action>pasta</action>'],
            ['Mover', 'Possui permissão para mover as pastas/consultas existentes', 'Menu', '<controller>ajax</controller><action>move-menu</action>'],
            ['Excluir', 'Possui permissão para excluir as pastas existentes', 'Menu', '<controller>ajax</controller><action>delete-folder</action>'],
                ]
        )->execute();

        return true;
    }

    public function safeDown() {
        echo "m181210_103837_permissoes_gerais cannot be reverted.\n";

        return true;
    }

}
