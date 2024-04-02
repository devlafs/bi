<?php

namespace app\models;

use Yii;

class AdminUsuarioPessoal extends \yii\db\ActiveRecord {

    public static function getDb() {
        return Yii::$app->get('userDb');
    }

    public static function tableName() {
        return 'admin_usuario_pessoal';
    }

    public function rules() {
        return [
            [['experienciasProfissionais', 'habilidadesProfissionais', 'interessesProfissionais', 'nomesFilhos', 'estilo', 'quemSouEu', 'minhasPaixoes', 'esportes', 'atividades', 'hobbies', 'livros', 'musicas', 'programasTv', 'filmes', 'pratosPrediletos', 'chamaAtencao', 'gostoEmMim', 'atrai', 'naoSuporto', 'naoVivoSem', 'vivoSem'], 'string'],
            [['perfilAcademicoRestrito', 'perfilSocialRestrito', 'qtdFilhos'], 'integer'],
            [['rgDataEmissao'], 'safe'],
            [['cep', 'cidade', 'cnh', 'cnhCategoria', 'correspondencia', 'ctps', 'ctpsSerie', 'ctpsUF', 'estado', 'estadoCivil', 'nacionalidade', 'naturalidade', 'pais', 'pisPasep', 'referencia', 'rg', 'rgEstado', 'rgOrgao', 'tipoEndereco', 'titSecao', 'titulo', 'titZona', 'religiao', 'visaoPolitica'], 'string', 'max' => 30],
            [['cpf'], 'string', 'max' => 15],
            [['endereco', 'setor', 'apelido'], 'string', 'max' => 100],
            [['idiomas'], 'string', 'max' => 200],
            [['mae', 'pai'], 'string', 'max' => 50],
            [['email2', 'site'], 'string', 'max' => 250],
            [['im'], 'string', 'max' => 255],
            [['fumo', 'corPreferida', 'corOlhos', 'corCabelos'], 'string', 'max' => 20],
            [['timeFutebol'], 'string', 'max' => 40],
            [['altura'], 'string', 'max' => 10],
            [['dv_agencia_cliente', 'dv_conta_corrente'], 'string', 'max' => 1],
            [['numero_convenio'], 'string', 'max' => 6],
            [['numero_controle_cliente'], 'string', 'max' => 25],
            [['agencia_cliente'], 'string', 'max' => 4],
            [['conta_corrente'], 'string', 'max' => 8],
            [['banco'], 'string', 'max' => 500],
            [['cpf'], 'unique'],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'cep' => 'Cep',
            'cidade' => 'Cidade',
            'cnh' => 'Cnh',
            'cnhCategoria' => 'Cnh Categoria',
            'correspondencia' => 'Correspondencia',
            'cpf' => 'Cpf',
            'ctps' => 'Ctps',
            'ctpsSerie' => 'Ctps Serie',
            'ctpsUF' => 'Ctps Uf',
            'endereco' => 'Endereco',
            'estado' => 'Estado',
            'estadoCivil' => 'Estado Civil',
            'experienciasProfissionais' => 'Experiencias Profissionais',
            'habilidadesProfissionais' => 'Habilidades Profissionais',
            'idiomas' => 'Idiomas',
            'interessesProfissionais' => 'Interesses Profissionais',
            'mae' => 'Mae',
            'nacionalidade' => 'Nacionalidade',
            'naturalidade' => 'Naturalidade',
            'pai' => 'Pai',
            'pais' => 'Pais',
            'perfilAcademicoRestrito' => 'Perfil Academico Restrito',
            'perfilSocialRestrito' => 'Perfil Social Restrito',
            'pisPasep' => 'Pis Pasep',
            'referencia' => 'Referencia',
            'rg' => 'Rg',
            'rgDataEmissao' => 'Rg Data Emissao',
            'rgEstado' => 'Rg Estado',
            'rgOrgao' => 'Rg Orgao',
            'setor' => 'Setor',
            'tipoEndereco' => 'Tipo Endereco',
            'titSecao' => 'Tit Secao',
            'titulo' => 'Titulo',
            'titZona' => 'Tit Zona',
            'email2' => 'Email2',
            'site' => 'Site',
            'im' => 'Im',
            'qtdFilhos' => 'Qtd Filhos',
            'nomesFilhos' => 'Nomes Filhos',
            'religiao' => 'Religiao',
            'visaoPolitica' => 'Visao Politica',
            'estilo' => 'Estilo',
            'fumo' => 'Fumo',
            'quemSouEu' => 'Quem Sou Eu',
            'minhasPaixoes' => 'Minhas Paixoes',
            'esportes' => 'Esportes',
            'atividades' => 'Atividades',
            'hobbies' => 'Hobbies',
            'livros' => 'Livros',
            'musicas' => 'Musicas',
            'programasTv' => 'Programas Tv',
            'filmes' => 'Filmes',
            'timeFutebol' => 'Time Futebol',
            'corPreferida' => 'Cor Preferida',
            'pratosPrediletos' => 'Pratos Prediletos',
            'chamaAtencao' => 'Chama Atencao',
            'altura' => 'Altura',
            'corOlhos' => 'Cor Olhos',
            'corCabelos' => 'Cor Cabelos',
            'gostoEmMim' => 'Gosto Em Mim',
            'atrai' => 'Atrai',
            'naoSuporto' => 'Nao Suporto',
            'naoVivoSem' => 'Nao Vivo Sem',
            'vivoSem' => 'Vivo Sem',
            'apelido' => 'Apelido',
            'dv_agencia_cliente' => 'Dv Agencia Cliente',
            'numero_convenio' => 'Numero Convenio',
            'numero_controle_cliente' => 'Numero Controle Cliente',
            'agencia_cliente' => 'Agencia Cliente',
            'conta_corrente' => 'Conta Corrente',
            'dv_conta_corrente' => 'Dv Conta Corrente',
            'banco' => 'Banco',
        ];
    }

    public function getAdminUsuario() {
        return $this->hasOne(AdminUsuario::className(), ['id' => 'id']);
    }

}
