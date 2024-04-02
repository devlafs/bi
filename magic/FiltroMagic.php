<?php

namespace app\magic;

use app\lists\DateFormatList;
use app\lists\TagsList;
use app\models\Indicador;
use app\models\Relatorio;
use app\models\RelatorioCampo;
use app\models\RelatorioData;
use Yii;
use app\models\Consulta;
use app\models\IndicadorCampo;
use app\models\AdminUsuarioDepartamento;

class FiltroMagic {

    CONST COND_MAIOR_IGUAL = 15;
    CONST COND_CONTEM = 1;
    CONST COND_IGUAL_A = 2;
    CONST COND_DIFERENTE = 3;
    CONST COND_NAO_CONTEM = 4;
    CONST COND_MAIOR = 5;
    CONST COND_MENOR = 6;
    CONST COND_MENOR_IGUAL = 7;
    CONST COND_INTERVALO_DE = 8;
    CONST COND_DENTRO_LISTA = 9;
    CONST COND_FORA_LISTA = 10;
    CONST COND_COMECA_COM = 11;
    CONST COND_NAO_COMECA_COM = 12;
    CONST COND_TERMINA_COM = 13;
    CONST COND_NAO_TERMINA_COM = 14;

    public static function getDataNameFields()
    {
        return [
            self::COND_IGUAL_A => Yii::t('app', 'filtro.igual_a'),
            self::COND_DIFERENTE => Yii::t('app', 'filtro.diferente_de'),
            self::COND_CONTEM => Yii::t('app', 'filtro.contem'),
            self::COND_NAO_CONTEM => Yii::t('app', 'filtro.nao_contem'),
            self::COND_MAIOR => Yii::t('app', 'filtro.maior_que'),
            self::COND_MAIOR_IGUAL => Yii::t('app', 'filtro.maior_ou_igual_a'),
            self::COND_MENOR => Yii::t('app', 'filtro.menor_que'),
            self::COND_MENOR_IGUAL => Yii::t('app', 'filtro.menor_ou_igual_a'),
            self::COND_INTERVALO_DE => Yii::t('app', 'filtro.em_um_intervalor_de'),
            self::COND_DENTRO_LISTA => Yii::t('app', 'filtro.iguais_a_lista'),
            self::COND_FORA_LISTA => Yii::t('app', 'filtro.diferentes_de_lista'),
            self::COND_COMECA_COM => Yii::t('app', 'filtro.comecao_com'),
            self::COND_NAO_COMECA_COM => Yii::t('app', 'filtro.nao_comeca_com'),
            self::COND_TERMINA_COM => Yii::t('app', 'filtro.termina_com'),
            self::COND_NAO_TERMINA_COM => Yii::t('app', 'filtro.nao_termina_com'),
        ];
    }

    public static function getLabelNameFields($field)
    {
        $data = self::getDataNameFields();
        return (isset($data[$field])) ? $data[$field] : $field;
    }

    public static function getDataMoney()
    {
        return [
            self::COND_IGUAL_A => Yii::t('app', 'filtro.igual_a'),
            self::COND_MAIOR => Yii::t('app', 'filtro.maior_que'),
            self::COND_MAIOR_IGUAL => Yii::t('app', 'filtro.maior_ou_igual_a'),
            self::COND_MENOR => Yii::t('app', 'filtro.menor_que'),
            self::COND_MENOR_IGUAL => Yii::t('app', 'filtro.menor_ou_igual_a'),
            self::COND_INTERVALO_DE => Yii::t('app', 'filtro.em_um_intervalor_de')
        ];
    }

    public static function getDataString()
    {
        return [
            self::COND_IGUAL_A => Yii::t('app', 'filtro.igual_a'),
            self::COND_DIFERENTE => Yii::t('app', 'filtro.diferente_de'),
            self::COND_DENTRO_LISTA => Yii::t('app', 'filtro.iguais_a_lista'),
            self::COND_FORA_LISTA => Yii::t('app', 'filtro.diferentes_de_lista'),
            self::COND_CONTEM => Yii::t('app', 'filtro.contem'),
            self::COND_NAO_CONTEM => Yii::t('app', 'filtro.nao_contem'),
            self::COND_COMECA_COM => Yii::t('app', 'filtro.comecao_com'),
            self::COND_NAO_COMECA_COM => Yii::t('app', 'filtro.nao_comeca_com'),
            self::COND_TERMINA_COM => Yii::t('app', 'filtro.termina_com'),
            self::COND_NAO_TERMINA_COM => Yii::t('app', 'filtro.nao_termina_com'),
        ];
    }

    public static function getDataDate()
    {
        return [
            self::COND_IGUAL_A => Yii::t('app', 'filtro.igual_a'),
            self::COND_DIFERENTE => Yii::t('app', 'filtro.diferente_de'),
            self::COND_MAIOR => Yii::t('app', 'filtro.maior_que'),
            self::COND_MENOR => Yii::t('app', 'filtro.menor_que'),
            self::COND_INTERVALO_DE => Yii::t('app', 'filtro.em_um_intervalor_de'),
        ];
    }

    public static function getValores($consulta_id) {
        return Consulta::find()->getValueFields($consulta_id);
    }

    public static function getSeries($consulta_id) {
        return Consulta::find()->getSerieFields($consulta_id);
    }

    public static function getAllArgumentos($consulta_id) {
        $consulta = Consulta::findOne($consulta_id);

        return IndicadorCampo::find()
                        ->andWhere(['id_indicador' => $consulta->id_indicador])
                        ->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE])
                        ->andWhere("tipo not in ('formulavalor', 'formulatexto')")
                        ->orderBy('nome ASC')->all();
    }

    public static function getCubos() {
        return Indicador::find()->andWhere([
            'is_ativo' => TRUE,
            'is_excluido' => FALSE
        ])->orderBy('nome ASC')->all();
    }

    public static function getAllArgumentosRelatorio($relatorio_id) {
        $relatorio = RelatorioData::findOne($relatorio_id);

        return RelatorioCampo::find()
            ->andWhere(['id_relatorio' => $relatorio->id_relatorio])
            ->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE])
            ->orderBy('nome ASC')->all();
    }

    public static function getArgumentos($consulta_id) {
        return Consulta::find()->getArgFields($consulta_id);
    }

    public static function getListByType($type) {
        $list = [];

        switch ($type) {
            case 'texto':
                $list = self::getDataString();
                break;
            case 'valor':
                $list = self::getDataMoney();
                break;
            case 'data':
                $list = self::getDataDate();
        }

        return $list;
    }

    public static function getRelatorioListByType($type) {
        $list = [];

        if(in_array($type, RelatorioCampo::$tipo_texto))
        {
            $list = self::getDataString();
        }
        elseif(in_array($type, RelatorioCampo::$tipo_inteiro))
        {
            $list = self::getDataMoney();
        }
        else
        {
            $list = self::getDataDate();
        }

        return $list;
    }

    public static function getPainelListByType($type) {
        $list = [];

        switch ($type) {

            case 'texto':

                $list = [
                    self::COND_IGUAL_A => Yii::t('app', 'filtro.igual_a'),
                    self::COND_DIFERENTE => Yii::t('app', 'filtro.diferente_de'),
                    self::COND_CONTEM => Yii::t('app', 'filtro.contem'),
                    self::COND_NAO_CONTEM => Yii::t('app', 'filtro.nao_contem'),
                    self::COND_COMECA_COM => Yii::t('app', 'filtro.comecao_com'),
                    self::COND_NAO_COMECA_COM => Yii::t('app', 'filtro.nao_comeca_com'),
                    self::COND_TERMINA_COM => Yii::t('app', 'filtro.termina_com'),
                    self::COND_NAO_TERMINA_COM => Yii::t('app', 'filtro.nao_termina_com'),
                ];

                break;

            case 'valor':

                $list = [
                    self::COND_MAIOR => Yii::t('app', 'filtro.maior_que'),
                    self::COND_MAIOR_IGUAL => Yii::t('app', 'filtro.maior_ou_igual_a'),
                    self::COND_MENOR => Yii::t('app', 'filtro.menor_que'),
                    self::COND_MENOR_IGUAL => Yii::t('app', 'filtro.menor_ou_igual_a'),
                ];

                break;

            case 'data':

                $list = [
                    self::COND_IGUAL_A => Yii::t('app', 'filtro.igual_a'),
                    self::COND_DIFERENTE => Yii::t('app', 'filtro.diferente_de'),
                    self::COND_MAIOR => Yii::t('app', 'filtro.maior_que'),
                    self::COND_MENOR => Yii::t('app', 'filtro.menor_que'),
                ];
        }

        return $list;
    }

    public static function getResults($model, $value) {
        $list = [];

        switch ($value) {
            case self::COND_IGUAL_A:
            case self::COND_DIFERENTE:
            case self::COND_DENTRO_LISTA:
            case self::COND_FORA_LISTA:

                $column = 'valor' . ($model->ordem - 1);
                $table = 'bpbi_indicador' . $model->id_indicador;

                $sql = <<<SQL

                    SELECT {$column} AS valor
                        FROM {$table}
                        WHERE {$column} IS NOT NULL
                        GROUP BY {$column}
                        ORDER BY {$column}

SQL;
                $list = Yii::$app->db->createCommand($sql)->queryAll();

                break;

            default :
                $list = [];
        }

        return $list;
    }

    public static function getCondicaoWhere($campo, $coluna, $tipoCampo, $tipoCondicao, $valor) {
        $andWhere = '';

        switch ($tipoCampo) {
            case 'texto':
                $andWhere = self::getCondicaoWhereTexto($coluna, $tipoCondicao, $valor);
                break;

            case 'data':
                $andWhere = self::getCondicaoWhereData($campo->formato, $coluna, $tipoCondicao, $valor);
                break;

            case 'valor':
                $andWhere = self::getCondicaoWhereValor($coluna, $tipoCondicao, $valor);
        }

        return $andWhere;
    }

    public static function getCondicaoWhereTexto($coluna, $tipoCondicao, $valor) {
        $andWhere = '';

        switch ($tipoCondicao) {
            case self::COND_IGUAL_A:

                if ($valor == '{empty}') {
                    $andWhere = "trim({$coluna}) = ''";
                } elseif ($valor == '{null}') {
                    $andWhere = "{$coluna} is null";
                } else {
                    $novo_valor = $valor;

                    switch ($valor) {
                        case '{usuario_logado.id}':
                            $novo_valor = isset(Yii::$app->user->identity) ? Yii::$app->user->identity->id : null;
                            break;
                        case '{usuario_logado.identificador}':
                            $novo_valor = isset(Yii::$app->user->identity) ? Yii::$app->user->identity->identificador : null;
                            break;
                        case '{usuario_logado.nome}':
                            $novo_valor = isset(Yii::$app->user->identity) ? Yii::$app->user->identity->nome : null;
                            break;
                        case '{usuario_logado.login}':
                            $novo_valor = isset(Yii::$app->user->identity) ? Yii::$app->user->identity->login : null;
                            break;
                        case '{usuario_logado.departamento_id}':

                            $beeIntegration = Yii::$app->params['beeIntegration'];

                            $novo_valor = null;

                            if ($beeIntegration) {
                                if (isset(Yii::$app->user->identity)) {
                                    $md = AdminUsuarioDepartamento::find()->andWhere(['usuario_id' => Yii::$app->user->identity->id])->one();

                                    $novo_valor = ($md) ? $md->departamento->nome : null;
                                }
                            } else {
                                $novo_valor = isset(Yii::$app->user->identity) ? Yii::$app->user->identity->departamento : null;
                            }

                            break;
                        case '{usuario_logado.perfil_id}':
                            $novo_valor = isset(Yii::$app->user->identity) ? Yii::$app->user->identity->perfil->nome : null;
                            break;
                        default:
                    }

                    $andWhere = "{$coluna} = '{$novo_valor}'";
                }

                break;

            case self::COND_DIFERENTE:

                if ($valor == '{empty}') {
                    $andWhere = "trim({$coluna}) != ''";
                } elseif ($valor == '{null}') {
                    $andWhere = "{$coluna} is not null";
                } else {
                    $novo_valor = $valor;

                    switch ($valor) {
                        case '{usuario_logado.id}':
                            $novo_valor = isset(Yii::$app->user->identity) ? Yii::$app->user->identity->id : null;
                            break;
                        case '{usuario_logado.identificador}':
                            $novo_valor = isset(Yii::$app->user->identity) ? Yii::$app->user->identity->identificador : null;
                            break;
                        case '{usuario_logado.nome}':
                            $novo_valor = isset(Yii::$app->user->identity) ? Yii::$app->user->identity->nome : null;
                            break;
                        case '{usuario_logado.login}':
                            $novo_valor = isset(Yii::$app->user->identity) ? Yii::$app->user->identity->login : null;
                            break;
                        case '{usuario_logado.departamento_id}':

                            $beeIntegration = Yii::$app->params['beeIntegration'];

                            $novo_valor = null;

                            if ($beeIntegration) {
                                if (isset(Yii::$app->user->identity)) {
                                    $md = AdminUsuarioDepartamento::find()->andWhere(['usuario_id' => Yii::$app->user->identity->id])->one();

                                    $novo_valor = ($md) ? $md->departamento->nome : null;
                                }
                            } else {
                                $novo_valor = isset(Yii::$app->user->identity) ? Yii::$app->user->identity->departamento : null;
                            }

                            break;
                        case '{usuario_logado.perfil_id}':
                            $novo_valor = isset(Yii::$app->user->identity) ? Yii::$app->user->identity->perfil->nome : null;
                            break;
                        default:
                    }

                    $andWhere = "{$coluna} != '{$novo_valor}'";
                }


                break;

            case self::COND_DENTRO_LISTA:
                $in = implode("','", $valor);
                $andWhere = "{$coluna} in ('{$in}')";
                break;

            case self::COND_FORA_LISTA:
                $in = implode("','", $valor);
                $andWhere = "{$coluna} not in ('{$in}')";
                break;

            case self::COND_CONTEM:
                $andWhere = "{$coluna} like '%{$valor}%'";
                break;

            case self::COND_NAO_CONTEM:
                $andWhere = "{$coluna} not like '%{$valor}%'";
                break;

            case self::COND_COMECA_COM:
                $andWhere = "{$coluna} like '{$valor}%'";
                break;

            case self::COND_NAO_COMECA_COM:
                $andWhere = "{$coluna} not like '{$valor}%'";
                break;

            case self::COND_TERMINA_COM:
                $andWhere = "{$coluna} like '%{$valor}'";
                break;

            case self::COND_NAO_TERMINA_COM:
                $andWhere = "{$coluna} not like '%{$valor}'";
        }

        return $andWhere;
    }

    public static function getCondicaoWhereTag($coluna, $tipoCondicao, $tag, $valor) {
        $andWhere = '';
        $sinal = '';

        switch ($tipoCondicao) {
            case self::COND_IGUAL_A:
                $sinal = "=";
                break;

            case self::COND_DIFERENTE:
                $sinal = "!=";
                break;

            case self::COND_MAIOR:
                $sinal = ">";
                break;

            case self::COND_MENOR:
                $sinal = "<";
        }

        switch ($tag) {
            case TagsList::TAG_HOJE:
                $andWhere = "DATE_FORMAT(STR_TO_DATE({$coluna}, '%d/%m/%Y'), '%Y-%m-%d') {$sinal} CURDATE()";
                break;

            case TagsList::TAG_DIAS_A_FRENTE:
                $andWhere = "DATE_FORMAT(STR_TO_DATE({$coluna}, '%d/%m/%Y'), '%Y-%m-%d') {$sinal} CURDATE() + INTERVAL {$valor} DAY";
                break;

            case TagsList::TAG_DIAS_ATRAS:
                $andWhere = "DATE_FORMAT(STR_TO_DATE({$coluna}, '%d/%m/%Y'), '%Y-%m-%d') {$sinal} CURDATE() - INTERVAL {$valor} DAY";
                break;

            case TagsList::TAG_MESES_A_FRENTE:
                $andWhere = "DATE_FORMAT(STR_TO_DATE({$coluna}, '%d/%m/%Y'), '%Y-%m-%d') {$sinal} CURDATE() + INTERVAL {$valor} MONTH";
                break;

            case TagsList::TAG_MESES_ATRAS:
                $andWhere = "DATE_FORMAT(STR_TO_DATE({$coluna}, '%d/%m/%Y'), '%Y-%m-%d') {$sinal} CURDATE() - INTERVAL {$valor} MONTH";
                break;

            case TagsList::TAG_ANOS_A_FRENTE:
                $andWhere = "DATE_FORMAT(STR_TO_DATE({$coluna}, '%d/%m/%Y'), '%Y-%m-%d') {$sinal} CURDATE() + INTERVAL {$valor} YEAR";
                break;

            case TagsList::TAG_ANOS_ATRAS:
                $andWhere = "DATE_FORMAT(STR_TO_DATE({$coluna}, '%d/%m/%Y'), '%Y-%m-%d') {$sinal} CURDATE() - INTERVAL {$valor} YEAR";
        }

        return $andWhere;
    }

    public static function getCondicaoWhereData($formato, $coluna, $tipoCondicao, $valor) {
        $andWhere = '';

        if (is_array($valor) && isset($valor['tag'])) {
            $valor_tag = isset($valor['tag']['0']) ? $valor['tag']['0'] : null;
            $tag = isset($valor['tag']['1']) ? $valor['tag']['1'] : null;

            $andWhere = self::getCondicaoWhereTag($coluna, $tipoCondicao, $tag, $valor_tag);
        } else {
            switch ($tipoCondicao) {
                case self::COND_IGUAL_A:
                case self::COND_DIFERENTE:

                    $operador = ($tipoCondicao == self::COND_IGUAL_A) ? '=' : '!=';

                    switch ($formato)
                    {
                        case DateFormatList::YYYY:
                            $andWhere = "YEAR(DATE_FORMAT(STR_TO_DATE({$coluna}, '%d/%m/%Y'), '%Y-%m-%d')) {$operador} {$valor}";
                            break;
                        case DateFormatList::MM:
                            $andWhere = "MONTH(DATE_FORMAT(STR_TO_DATE({$coluna}, '%d/%m/%Y'), '%Y-%m-%d')) {$operador} {$valor}";
                            break;
                        case DateFormatList::DD:
                            $andWhere = "DAY(DATE_FORMAT(STR_TO_DATE({$coluna}, '%d/%m/%Y'), '%Y-%m-%d')) {$operador} {$valor}";
                            break;
                        case DateFormatList::MM_YYYY:
                        case DateFormatList::MM__YYYY:
                            $andWhere = "DATE_FORMAT(STR_TO_DATE({$coluna}, '%d/%m/%Y'), '{$formato}') {$operador} '{$valor}'";
                            break;
                        case DateFormatList::DD_MM_YYYY:
                        case DateFormatList::DD__MM__YYYY:
                            $andWhere = "DATE_FORMAT(STR_TO_DATE({$coluna}, '%d/%m/%Y'), '{$formato}') {$operador} DATE_FORMAT(STR_TO_DATE('{$valor}', '%d/%m/%Y'), '{$formato}')";
                    }
                    break;
                case self::COND_MAIOR:
                case self::COND_MENOR:

                    $operador = ($tipoCondicao == self::COND_MAIOR) ? '>' : '<';

                    switch ($formato)
                    {
                        case DateFormatList::YYYY:
                            $andWhere = "YEAR(DATE_FORMAT(STR_TO_DATE({$coluna}, '%d/%m/%Y'), '%Y-%m-%d')) {$operador} {$valor}";
                            break;
                        case DateFormatList::MM:
                            $andWhere = "MONTH(DATE_FORMAT(STR_TO_DATE({$coluna}, '%d/%m/%Y'), '%Y-%m-%d')) {$operador} {$valor}";
                            break;
                        case DateFormatList::DD:
                            $andWhere = "DAY(DATE_FORMAT(STR_TO_DATE({$coluna}, '%d/%m/%Y'), '%Y-%m-%d')) {$operador} {$valor}";
                            break;
                        case DateFormatList::MM_YYYY:
                            $andWhere = "DATE_FORMAT(STR_TO_DATE({$coluna}, '%d/%m/%Y'), '%Y-%m-%d') {$operador} DATE_FORMAT(STR_TO_DATE('01/{$valor}', '%d/%m/%Y'), '%Y-%m-%d')";
                            break;
                        case DateFormatList::MM__YYYY:
                            $andWhere = "DATE_FORMAT(STR_TO_DATE({$coluna}, '%d/%m/%Y'), '%Y-%m-%d') {$operador} DATE_FORMAT(STR_TO_DATE('01-{$valor}', '%d-%m-%Y'), '%Y-%m-%d')";
                            break;
                        case DateFormatList::DD_MM_YYYY:
                        case DateFormatList::DD__MM__YYYY:
                            $andWhere = "DATE_FORMAT(STR_TO_DATE({$coluna}, '%d/%m/%Y'), '%Y-%m-%d') {$operador} DATE_FORMAT(STR_TO_DATE('{$valor}', '{$formato}'), '%Y-%m-%d')";
                    }

                    break;

                case self::COND_INTERVALO_DE:
                    switch ($formato)
                    {
                        case DateFormatList::YYYY:
                            $andWhere = "YEAR(DATE_FORMAT(STR_TO_DATE({$coluna}, '%d/%m/%Y'), '%Y-%m-%d')) BETWEEN {$valor[0]} AND {$valor[1]}";
                            break;
                        case DateFormatList::MM:
                            $andWhere = "MONTH(DATE_FORMAT(STR_TO_DATE({$coluna}, '%d/%m/%Y'), '%Y-%m-%d')) BETWEEN {$valor[0]} AND {$valor[1]}";
                            break;
                        case DateFormatList::DD:
                            $andWhere = "DAY(DATE_FORMAT(STR_TO_DATE({$coluna}, '%d/%m/%Y'), '%Y-%m-%d')) BETWEEN {$valor[0]} AND {$valor[1]}";
                            break;
                        case DateFormatList::MM_YYYY:
                            $andWhere = "DATE_FORMAT(STR_TO_DATE({$coluna}, '%d/%m/%Y'), '%Y-%m-%d') BETWEEN DATE_FORMAT(STR_TO_DATE('01/{$valor[0]}', '%d/%m/%Y'), '%Y-%m-%d') AND DATE_FORMAT(STR_TO_DATE('01/{$valor[1]}', '%d/%m/%Y'), '%Y-%m-%d')";
                            break;
                        case DateFormatList::MM__YYYY:
                            $andWhere = "DATE_FORMAT(STR_TO_DATE({$coluna}, '%d/%m/%Y'), '%Y-%m-%d') BETWEEN DATE_FORMAT(STR_TO_DATE('01-{$valor[0]}', '%d-%m-%Y'), '%Y-%m-%d') AND DATE_FORMAT(STR_TO_DATE('01-{$valor[1]}', '%d-%m-%Y'), '%Y-%m-%d')";
                            break;
                        case DateFormatList::DD_MM_YYYY:
                        case DateFormatList::DD__MM__YYYY:
                            $andWhere = "DATE_FORMAT(STR_TO_DATE({$coluna}, '%d/%m/%Y'), '%Y-%m-%d') BETWEEN DATE_FORMAT(STR_TO_DATE('{$valor[0]}', '{$formato}'), '%Y-%m-%d') AND DATE_FORMAT(STR_TO_DATE('{$valor[1]}', '{$formato}'), '%Y-%m-%d')";
                    }
            }
        }

        return $andWhere;
    }

    public static function getCondicaoWhereValor($coluna, $tipoCondicao, $valor) {
        $andWhere = '';

        switch ($tipoCondicao) {
            case self::COND_IGUAL_A:
                $andWhere = "{$coluna} = {$valor}";
                break;

            case self::COND_MAIOR:
                $andWhere = "{$coluna} > {$valor}";
                break;

            case self::COND_MAIOR_IGUAL:
                $andWhere = "{$coluna} >= {$valor}";
                break;

            case self::COND_MENOR:
                $andWhere = "{$coluna} < {$valor}";
                break;

            case self::COND_MENOR_IGUAL:
                $andWhere = "{$coluna} <= {$valor}";
                break;

            case self::COND_INTERVALO_DE:
                $andWhere = "{$coluna} BETWEEN {$valor[0]} AND {$valor[1]}";
        }

        return $andWhere;
    }

    public static function getSinalizador($tipoCondicao) {
        $sinalizador = '';

        switch ($tipoCondicao) {
            case self::COND_IGUAL_A:
                $sinalizador = "=";
                break;

            case self::COND_MAIOR:
                $sinalizador = ">";
                break;

            case self::COND_MAIOR_IGUAL:
                $sinalizador = ">=";
                break;

            case self::COND_MENOR:
                $sinalizador = "<";
                break;

            case self::COND_MENOR_IGUAL:
                $sinalizador = "<=";
                break;

            case self::COND_INTERVALO_DE:
                $sinalizador = "between";
                break;

            case self::COND_DENTRO_LISTA:
                $sinalizador = "in";
                break;

            case self::COND_FORA_LISTA:
                $sinalizador = "not in";
                break;

            case self::COND_CONTEM:
            case self::COND_COMECA_COM:
            case self::COND_TERMINA_COM:
                $sinalizador = "like";
                break;

            case self::COND_NAO_CONTEM:
            case self::COND_NAO_COMECA_COM:
            case self::COND_NAO_TERMINA_COM:
                $sinalizador = "not like";

        }

        return $sinalizador;
    }

    public static function getValorSinalizador($tipoCondicao, $valor) {
        $result = '';

        switch ($tipoCondicao) {
            case self::COND_IGUAL_A:
            case self::COND_MAIOR:
            case self::COND_MAIOR_IGUAL:
            case self::COND_MENOR:
            case self::COND_MENOR_IGUAL:
                $result = $valor;
                break;

            case self::COND_INTERVALO_DE:
                $i = implode(',', $valor);
                $result = "{$i[0]} AND {$i[1]} ";
                break;

            case self::COND_DENTRO_LISTA:
            case self::COND_FORA_LISTA:
                $result = "({$valor})";
                break;

            case self::COND_CONTEM:
            case self::COND_NAO_CONTEM:
                $valor = str_replace(['"', "'"], '', $valor);
                $result = "'%{$valor}%'";
                break;

            case self::COND_COMECA_COM:
            case self::COND_NAO_COMECA_COM:
                $valor = str_replace(['"', "'"], '', $valor);
                $result = "'{$valor}%'";
                break;

            case self::COND_TERMINA_COM:
            case self::COND_NAO_TERMINA_COM:
                $valor = str_replace(['"', "'"], '', $valor);
                $result = "'%{$valor}'";

        }

        return $result;
    }

    public static function getValorCampo($campo, $q = null)
    {
        $field = "valor" . ($campo->ordem - 1);

        if(Yii::$app->params['userTags'])
        {
            $union = <<<SQL

            UNION
            (
                SELECT '{empty}' as id, '{VAZIO}' as text
            )
            UNION
            (
                SELECT '{null}' as id, '{NULO}' as text
            )
            UNION
            (
                SELECT '{usuario_logado.id}' as id, '{USUARIO_LOGADO.ID}' as text
            )
            UNION
            (
                SELECT '{usuario_logado.identificador}' as id, '{USUARIO_LOGADO.IDENTIFICADOR}' as text
            )
            UNION
            (
                SELECT '{usuario_logado.nome}' as id, '{USUARIO_LOGADO.NOME}' as text
            )
            UNION
            (
                SELECT '{usuario_logado.login}' as id, '{USUARIO_LOGADO.LOGIN}' as text
            )
            UNION
            (
                SELECT '{usuario_logado.departamento_id}' as id, '{USUARIO_LOGADO.DEPARTAMENTO_NOME}' as text
            )
            UNION
            (
                SELECT '{usuario_logado.perfil_id}' as id, '{USUARIO_LOGADO.PERFIL_NOME}' as text
            )
SQL;
        }
        else
        {
            $union = '';
        }

        $sql = <<<SQL

        SELECT DISTINCT
            id, text
        FROM
        (
            SELECT
                {$field} AS id, UPPER({$field}) AS text
            FROM
                bpbi_indicador{$campo->id_indicador}
            {$union}
        ) as tot

SQL;
        if($q)
        {
            $sql .= " WHERE text LIKE '%{$q}%' ORDER BY text ASC LIMIT 20;";
        }
        else
        {
            $sql .= " ORDER BY text;";
        }

        return \Yii::$app->db->createCommand($sql)->queryAll();
    }
}
