<?php

namespace app\magic;

use Yii;
use app\models\Consulta;
use app\models\IndicadorCampo;
use app\models\ConsultaFiltroUsuario;
use app\models\ConsultaItemConfiguracao;
use app\lists\DateFormatList;

class PainelMagic {

    public static function getValores($consulta_id) {
        return Consulta::find()->getValueFields($consulta_id);
    }

    public static function getSeries($consulta_id) {
        return Consulta::find()->getSerieFields($consulta_id);
    }

    public static function getArgumentos($consulta_id) {
        return Consulta::find()->getArgFields($consulta_id);
    }

    public static function unserializeToken($token) {
        return unserialize(base64_decode($token));
    }

    public static function serializeData($data) {
        return base64_encode(serialize($data));
    }

    public static function montaSql($consulta, $select = [], $where = '', $group_by = [], $order_by = [], $limit = 10) {
        $table_name = "bpbi_indicador{$consulta->id_indicador}";
        $select_string = " SELECT ";

        $limitGroup = ($consulta->limite) ? " LIMIT {$consulta->limite}" : "";

        foreach ($select as $ind => $sel) {
            $select_string .= $sel;
            $select_string .= ($ind < (count($select) - 1)) ? ', ' : '';
        }

        $group_string = ($group_by) ? " GROUP BY " : "";

        foreach ($group_by as $ind => $gru) {
            $group_string .= $gru;
            $group_string .= ($ind < (count($group_by) - 1)) ? ', ' : '';
        }

        $order_string = ($order_by) ? " ORDER BY " : "";

        foreach ($order_by as $ind => $ord) {
            $order_string .= $ord;
            $order_string .= ($ind < (count($order_by) - 1)) ? ', ' : '';
        }

        $sql = <<<SQL
                
            SELECT * FROM
            (
                $select_string 
                FROM {$table_name}
                {$where}
                {$group_string}
                LIMIT {$limit}
            ) as sel
            {$order_string}
            {$limitGroup}
                
SQL;

        return $sql;
    }

    public static function getData($consulta, $index = 0, $filtro = null, $token = '', $previous = FALSE, $user_id = null, $own = FALSE, $field = null, $painel_filtro = null) {
        $select = $select_totalizador = $group_by = $order_by = $where = $magic_names = [];
        $data_campos = ['x' => null, 'y' => null, 'z' => null, 'tipo_numero' => 1];
        $tipo_grafico = 'column';
        $sort = 0;
        $elemento_atual = null;

        if (!$user_id && Yii::$app->user) {
            $user_id = Yii::$app->user->id;
        }

        $serialized_data = self::unserializeToken($token);
        $data_filtro = isset($serialized_data['filtro']) ? $serialized_data['filtro'] : [];
        $data_breadcrumb = isset($serialized_data['breadcrumb']) ? $serialized_data['breadcrumb'] : [];

        $valores = self::getValores($consulta->id);

        $tipo_valor = ($valores) ? $valores[0]->campo->tipo : 'valor';

        foreach ($valores as $valor) {
            $nome_campo_valor = 'valor' . ($valor->campo->ordem - 1);

            if ($tipo_valor == 'formulavalor') {
                $campo = $valor->campo->campo;
                preg_match_all('/[^{{\}}]+(?=}})/', $valor->campo->campo, $matches);

                foreach ($matches as $match) {
                    if ($match) {
                        foreach ($match as $mt) {
                            $nomecampo = (int) $mt - 1;
                            $campo = str_replace("{{{$mt}}}", "valor{$nomecampo}", $campo);
                        }
                    }
                }

                $select[] = " {$campo} as y";
                $select_totalizador[] = " {$campo} as y";

                if ($valor->campo->variavel_formula) {
                    Yii::$app->db->createCommand($valor->campo->variavel_formula)->execute();
                }
            } else {
                $select[] = ' SUM(' . $nome_campo_valor . ') as y';
                $select_totalizador[] = ' SUM(' . $nome_campo_valor . ') as y';
            }

            $magic_names['y'] = $valor->campo->nome;
            $data_campos['y'] = $valor->campo->attributes;
        }

        $select_totalizador[] = "'totalizador' as x";
        $field = '';

        $argumentos = self::getArgumentos($consulta->id);
        $links = [];

        foreach ($argumentos as $i => $argumento) {
            $nome_campo = 'valor' . ($argumento->campo->ordem - 1);

            if (in_array($argumento->campo->tipo, ['formulatexto', 'formulavalor'])) {
                $nome_campo = $argumento->campo->campo;

                preg_match_all('/[^{{\}}]+(?=}})/', $argumento->campo->campo, $matches);

                foreach ($matches as $match) {
                    if ($match) {
                        foreach ($match as $mt) {
                            $nomecampo = (int) $mt - 1;
                            $nome_campo = str_replace("{{{$mt}}}", "valor{$nomecampo}", $nome_campo);
                        }
                    }
                }

                if ($argumento->campo->variavel_formula) {
                    Yii::$app->db->createCommand($argumento->campo->variavel_formula)->execute();
                }
            }

            if ($argumento->campo->tipo == 'data') {
                if (in_array($nome_campo, [DateFormatList::DD, DateFormatList::MM, DateFormatList::YYYY])) {
                    $nome_campo = "DATE_FORMAT(STR_TO_DATE({$nome_campo}, '%d/%m/%Y'), '{$argumento->campo->formato}') + 0";
                } else {
                    $nome_campo = "DATE_FORMAT(STR_TO_DATE({$nome_campo}, '%d/%m/%Y'), '{$argumento->campo->formato}')";
                }
            }

            if ($i == $index) {
                $select[] = $nome_campo . ' as x';
                $field = $nome_campo;
                $magic_names['x'] = $argumento->campo->nome;
                $data_campos['x'] = $argumento->campo->attributes;
                $data_campos['tipo_numero'] = $argumento->tipo_numero;
                $group_by[] = $nome_campo;

                $sort = $argumento->ordenacao;

                switch ($sort) {
                    case 0:

                        if ($argumento->campo->tipo == 'data') {
                            $order_by[] = "CONCAT(SUBSTR(x, 7, 4), SUBSTR(x, 4, 2), SUBSTR(x, 1, 2)) ASC";
                        } elseif (in_array($argumento->campo->tipo, ['valor', 'formulavalor'])) {
                            $order_by[] = "x + 0 ASC";
                        } else {
                            $order_by[] = "x ASC";
                        }

                        break;

                    case 1:

                        if ($argumento->campo->tipo == 'data') {
                            $order_by[] = "CONCAT(SUBSTR(x, 7, 4), SUBSTR(x, 4, 2), SUBSTR(x, 1, 2)) DESC";
                        } elseif (in_array($argumento->campo->tipo, ['valor', 'formulavalor'])) {
                            $order_by[] = "x + 0 DESC";
                        } else {
                            $order_by[] = "x DESC";
                        }

                        break;

                    case 2:

                        $order_by[] = "y ASC";

                        break;

                    case 3:

                        $order_by[] = "y DESC";

                        break;
                }

                $elemento_atual = $argumento;
                $tipo_grafico = ActiveGraphMagic::getActiveGraph($elemento_atual, $user_id);

                if ($elemento_atual->campo->link) {
                    $links['x'] = $elemento_atual->campo->link;
                }

            } elseif ($i == ($index - 1) && $own && !$previous) {
                if($own) {
                    $data_filtro[$nome_campo] = $filtro;
                }

                $data_breadcrumb[$index - 1] = ['nome' => $argumento->campo->nome, 'valor' => $filtro];
            }
        }

        if ($elemento_atual && $tipo_grafico == 'table') {
            $configuracoesCampo = ConsultaItemConfiguracao::find()
                            ->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE])
                            ->andWhere(['id_consulta' => $consulta->id, 'id_item' => $elemento_atual->id_campo])->orderBy('ordem ASC')->all();

            foreach ($configuracoesCampo as $index_configuracao => $configuracao) {
                $ordem_campo_configuracao = ($configuracao->campo->ordem - 1);
                $nome_campo_configuracao = 'valor' . $ordem_campo_configuracao;

                if ($configuracao->campo->tipo == 'data') {
                    if (in_array($configuracao->campo->formato, [DateFormatList::DD, DateFormatList::MM, DateFormatList::YYYY])) {
                        $nome_campo_configuracao = "DATE_FORMAT(STR_TO_DATE({$nome_campo_configuracao}, '%d/%m/%Y'), '{$configuracao->campo->formato}') + 0";
                    } else {
                        $nome_campo_configuracao = "DATE_FORMAT(STR_TO_DATE({$nome_campo_configuracao}, '%d/%m/%Y'), '{$configuracao->campo->formato}')";
                    }
                } elseif (in_array($configuracao->campo->tipo, ['formulatexto', 'formulavalor'])) {
                    $nome_campo_configuracao = $configuracao->campo->campo;
                    preg_match_all('/[^{{\}}]+(?=}})/', $configuracao->campo->campo, $matches);

                    foreach ($matches as $match) {
                        if ($match) {
                            foreach ($match as $mt) {
                                $nome_campo_c = (int) $mt - 1;
                                $nome_campo_configuracao = str_replace("{{{$mt}}}", "valor{$nome_campo_c}", $nome_campo_configuracao);
                            }
                        }
                    }

                    if ($configuracao->campo->variavel_formula) {
                        Yii::$app->db->createCommand($configuracao->campo->variavel_formula)->execute();
                    }
                }

                if ($configuracao->campo->tipo == 'valor') {
                    $nome_campo_configuracao = ($configuracao->campo->agrupar_valor) ? "SUM({$nome_campo_configuracao})" : "{$nome_campo_configuracao}";
                } elseif ($configuracao->campo->tipo != 'formulavalor') {
                    $group_by[] = $nome_campo_configuracao;
                }

                $select[] = $nome_campo_configuracao . ' as w' . $index_configuracao;
                $magic_names['w'][$index_configuracao] = $configuracao->campo->nome;
                $data_campos['w' . $index_configuracao] = $configuracao->campo->attributes;

                if ($configuracao->campo->tipo == 'data') {
                    $order_by[] = "CONCAT(SUBSTR(w{$index_configuracao}, 7, 4), SUBSTR(w{$index_configuracao}, 4, 2), SUBSTR(w{$index_configuracao}, 1, 2)) ASC";
                } elseif (in_array($configuracao->campo->tipo, ['valor', 'formulavalor'])) {
                    $order_by[] = "w{$index_configuracao} + 0 ASC";
                } else {
                    $order_by[] = "w{$index_configuracao} ASC";
                }

                if ($configuracao->campo->link) {
                    $links[$configuracao->campo->nome] = $configuracao->campo->link;
                }
            }
        }

        $series = self::getSeries($consulta->id);

        if ($series && !in_array($tipo_grafico, ['pie', 'donut', 'funnel', 'kpi'])) {
            foreach ($series as $serie) {
                $nome_campo_serie = 'valor' . ($serie->campo->ordem - 1);

                if (in_array($serie->campo->tipo, ['formulatexto', 'formulavalor'])) {
                    $nome_campo_serie = $serie->campo->campo;
                    preg_match_all('/[^{{\}}]+(?=}})/', $serie->campo->campo, $matches);

                    foreach ($matches as $match) {
                        if ($match) {
                            foreach ($match as $mt) {
                                $nomecampo = (int) $mt - 1;
                                $nome_campo_serie = str_replace("{{{$mt}}}", "valor{$nomecampo}", $nome_campo_serie);
                            }
                        }
                    }

                    if ($serie->campo->variavel_formula) {
                        Yii::$app->db->createCommand($serie->campo->variavel_formula)->execute();
                    }
                }

                if ($serie->campo->tipo == 'data') {
                    if (in_array($serie->campo->formato, [DateFormatList::DD, DateFormatList::MM, DateFormatList::YYYY])) {
                        $nome_campo_serie = "DATE_FORMAT(STR_TO_DATE({$nome_campo_serie}, '%d/%m/%Y'), '{$serie->campo->formato}') + 0";
                    } else {
                        $nome_campo_serie = "DATE_FORMAT(STR_TO_DATE({$nome_campo_serie}, '%d/%m/%Y'), '{$serie->campo->formato}')";
                    }
                }

                $select[] = " {$nome_campo_serie} as z";
                $magic_names['z'] = ($serie->campo->nome) ? $serie->campo->nome : 'null';
                $data_campos['z'] = $serie->campo->attributes;
                $group_by[] = $nome_campo_serie;

                if ($serie->campo->tipo == 'data') {
                    $order_by[] = "CONCAT(SUBSTR(z, 7, 4), SUBSTR(z, 4, 2), SUBSTR(z, 1, 2)) ASC";
                } elseif (in_array($serie->campo->tipo, ['valor', 'formulavalor'])) {
                    $order_by[] = "z + 0 ASC";
                } else {
                    $order_by[] = "z ASC";
                }
            }
        }

        if ($previous) {
            $data_filtro = array_slice($data_filtro, 0, $index);
            $data_breadcrumb = array_slice($data_breadcrumb, 0, $index);
        }

        $condicao = self::getCondicao($consulta->condicao);

        $configuracao_filtro = ConsultaFiltroUsuario::find()->andWhere([
                    'id_usuario' => $user_id,
                    'id_consulta' => $consulta->id,
                    'is_ativo' => TRUE,
                    'is_excluido' => FALSE
                ])->one();

        $condicao_usuario = ($configuracao_filtro) ? self::getCondicao($configuracao_filtro->condicao) : null;

        if($painel_filtro)
        {
            $painel_filtro = self::getCondicaoPainel($painel_filtro);
        }

        $where = self::formatWhere($condicao, $data_filtro, $condicao_usuario, $consulta->condicao_avancada, $painel_filtro);

        $sql = self::montaSql($consulta, $select, $where, $group_by, $order_by, 10000000000);
        $sql_totalizador = self::montaSql($consulta, $select_totalizador, $where, [], [], 1);

        $error = FALSE;
        $totalizador = 0;

        try {
            $data_provider = Yii::$app->db->createCommand($sql)->queryAll();
            $totalizador = Yii::$app->db->createCommand($sql_totalizador)->queryScalar();
        } catch (\yii\db\Exception $ex) {
            $data_provider = [];
            $error = TRUE;
        }

        $sd = ['filtro' => $data_filtro, 'breadcrumb' => $data_breadcrumb];

        return
                [
                    'elementoAtual' => $elemento_atual,
                    'dataProvider' => $data_provider,
                    'ultimo' => (($index + 1) == sizeof($argumentos)) || (sizeof($argumentos) == 0),
                    'nomes' => $magic_names,
                    'token' => self::serializeData($sd),
                    'tipo_valor' => $tipo_valor,
                    'error' => $error,
                    'series' => !empty($series),
                    'links' => $links,
                    'tipoGrafico' => $tipo_grafico,
                    'sort' => $sort,
                    'campos' => $data_campos,
                    'totalizador' => $totalizador,
                    'field' => $field
        ];
    }

    public static function getCondicaoPainel($condicao) {
        $data = [];

        if ($condicao) {
            foreach ($condicao as $val) {
                $campo = IndicadorCampo::findOne($val['field']);
                $column = 'valor' . ($campo->ordem - 1);
                $tipo = $campo->tipo;

                $data[] = FiltroMagic::getCondicaoWhere($campo, $column, $tipo, $val['type'], $val['value']);
            }
        }

        return $data;
    }

    public static function getCondicao($condicao) {
        $data = [];

        if ($condicao) {
            foreach ($condicao as $x => $ands) {
                foreach ($ands as $j => $ors) {
                    $campo = IndicadorCampo::findOne($ors['field']);
                    $column = 'valor' . ($campo->ordem - 1);
                    $tipo = $campo->tipo;

                    $data[$x][$j] = FiltroMagic::getCondicaoWhere($campo, $column, $tipo, $ors['type'], $ors['value']);
                }
            }
        }

        return $data;
    }

    public static function formatWhere($condicao, $drilldown, $condicao_usuario = null, $condicao_avancada = null, $painel_filtro = null) {
        if (!$condicao && !$drilldown && !$condicao_usuario && (!$condicao_avancada || trim($condicao_avancada) == '') && !$painel_filtro) {
            return '';
        }

        $where = " WHERE ";

        $hasAdvancedFilter = CacheMagic::getSystemData('advancedFilter');
        $hasFilter = FALSE;

        if($hasAdvancedFilter && $condicao_avancada && trim($condicao_avancada) != '')
        {
            $where .= "(" . AdvancedFilterMagic::getCondicaoAvancada($condicao_avancada) . ")";
            $hasFilter = true;
        }
        else if ($condicao) {
            foreach ($condicao as $x => $ands) {
                $where .= ($x > 1) ? " AND ( " : " ( ";

                foreach ($ands as $j => $ors) {
                    $where .= ($j > 1) ? " OR {$ors} " : " {$ors}";
                }

                $where .= " ) ";
            }
            $hasFilter = true;
        }

        if ($drilldown) {
            $where .= ($hasFilter) ? " AND " : "";

            $w = 0;
            foreach ($drilldown as $nome_campo => $valor_filtro) {
                $where .= ($w > 0) ? " AND " : " ";

                if ($valor_filtro == 'null') {
                    $where .= "({$nome_campo} is null)";
                } elseif (strpos($valor_filtro, '{{OR}}') !== false) {
                    $ins = explode(" {{OR}} ", $valor_filtro);

                    $where .= "(";

                    foreach ($ins as $index_in => $in_value) {
                        if ($index_in > 0) {
                            $where .= " OR ";
                        }

                        $where .= "{$nome_campo} = '{$in_value}'";
                    }

                    $where .= ")";
                } else {
                    $where .= "({$nome_campo} = '{$valor_filtro}')";
                }

                $w++;
            }
            $hasFilter = true;
        }

        if ($condicao_usuario) {
            $where .= ($hasFilter) ? " AND " : "";

            $w = 0;

            foreach ($condicao_usuario as $x => $ands) {
                $where .= ($x > 1) ? " AND ( " : " ( ";

                foreach ($ands as $j => $ors) {
                    $where .= ($j > 1) ? " OR {$ors} " : " {$ors}";
                }

                $where .= " ) ";
            }

            $hasFilter = true;
        }

        if ($painel_filtro) {
            $where .= ($hasFilter) ? " AND " : "";

            $w = 0;

            foreach ($painel_filtro as $x => $ands) {
                $where .= ($x > 0) ? " AND {$ands} " : " {$ands} ";
            }
        }

        return $where;
    }
}
