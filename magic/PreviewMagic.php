<?php

namespace app\magic;

use Yii;
use app\models\IndicadorCampo;
use app\magic\SqlMagic;
use app\magic\FiltroMagic;
use app\models\ConsultaItemConfiguracao;
use app\lists\DateFormatList;

class PreviewMagic {

    public static function getValor($id) {
        return IndicadorCampo::find()->andWhere(['id' => $id])->asArray()->one();
    }

    public static function getSerie($id) {
        return IndicadorCampo::find()->andWhere(['id' => $id])->asArray()->one();
    }

    public static function getArgumentos($argumentos) {
        $data = [];

        foreach ($argumentos as $argumento) {
            $campo = IndicadorCampo::find()->andWhere(['id' => $argumento['id']])->asArray()->one();
            $data[] = ['campo' => $campo, 'id' => $argumento['id'], 'type' => $argumento['type'], 'sort' => $argumento['sort'], 'tipo_numero' => $argumento['tipo_numero']];
        }

        return $data;
    }

    public static function getData($consulta, $post, $index_geral = 0, $type = null, $sqlMode = FALSE) {
        $serie = $select = $group_by = $order_by = $where = $magic_names = [];
        $data_campos = ['x' => null, 'y' => null, 'z' => null, 'tipo_numero' => 1];
        $elemento_atual = null;
        $sort = 0;
        $coluna = '';
        $tipo_grafico = "column";

//        Filtros de drilldown
        $data_filtro = isset($post['filtro']) ? $post['filtro'] : [];

//        Se não existe valor, retornar vazio
        if (!isset($post['valor'][0])) {
            return [];
        }

        $valor = self::getValor($post['valor'][0]);

        $tipo_valor = ($valor) ? $valor['tipo'] : 'valor';

        if ($valor) {
//            Se o valor é fórmula, precisa modificar
            if ($tipo_valor == 'formulavalor') {
                $campo_valor = $valor['campo'];
                preg_match_all('/[^{{\}}]+(?=}})/', $valor['campo'], $matches);

                foreach ($matches as $match) {
                    if ($match) {
                        foreach ($match as $mt) {
//                            Faz um replace na referência, para o SQL entender o campo em questão
                            $nome_campo_valor = (int) $mt - 1;
                            $campo_valor = str_replace("{{{$mt}}}", "valor{$nome_campo_valor}", $campo_valor);
                        }
                    }
                }

                if ($valor['variavel_formula']) {
                    Yii::$app->db->createCommand($valor['variavel_formula'])->execute();
                }

                $select[] = " {$campo_valor} as y";
            } else {
                $nome_campo_valor = 'valor' . ($valor['ordem'] - 1);
                $select[] = " SUM({$nome_campo_valor}) as y";
            }

            $magic_names['y'] = $valor['nome'];
            $data_campos['y'] = $valor;
        }

        $argumentos = (isset($post['argumento'])) ? self::getArgumentos($post['argumento']) : [];

        if (isset($post['argumento'])) {
            foreach ($argumentos as $index_argumento => $argumento) {
                $campo_argumento = $argumento['campo'];
                $nome_campo_argumento = 'valor' . ($campo_argumento['ordem'] - 1);

                if ($index_argumento == $index_geral) {
                    if (in_array($campo_argumento['tipo'], ['formulatexto', 'formulavalor'])) {
                        $nome_campo_argumento = $campo_argumento['campo'];

                        preg_match_all('/[^{{\}}]+(?=}})/', $campo_argumento['campo'], $matches);

                        foreach ($matches as $match) {
                            if ($match) {
                                foreach ($match as $mt) {
                                    $nome_campo = (int) $mt - 1;
                                    $nome_campo_argumento = str_replace("{{{$mt}}}", "valor{$nome_campo}", $nome_campo_argumento);
                                }
                            }
                        }

                        if ($campo_argumento['variavel_formula']) {
                            Yii::$app->db->createCommand($campo_argumento['variavel_formula'])->execute();
                        }
                    }

                    if ($campo_argumento['tipo'] == 'data') {
                        if (in_array($campo_argumento['formato'], [DateFormatList::DD, DateFormatList::MM, DateFormatList::YYYY])) {
                            $nome_campo_argumento = "DATE_FORMAT(STR_TO_DATE({$nome_campo_argumento}, '%d/%m/%Y'), '{$campo_argumento['formato']}') + 0";
                        } else {
                            $nome_campo_argumento = "DATE_FORMAT(STR_TO_DATE({$nome_campo_argumento}, '%d/%m/%Y'), '{$campo_argumento['formato']}')";
                        }
                    }

                    $select[] = $nome_campo_argumento . " as x ";

                    $magic_names['x'] = $campo_argumento['nome'];
                    $group_by[] = $nome_campo_argumento;

                    $sort = $argumento['sort'];
                    switch ($sort) {
                        case 0:

                            if ($campo_argumento['tipo'] == 'data') {
                                $order_by[] = "CONCAT(SUBSTR(x, 7, 4), SUBSTR(x, 4, 2), SUBSTR(x, 1, 2)) ASC";
                            } elseif (in_array($campo_argumento['tipo'], ['valor', 'formulavalor'])) {
                                $order_by[] = "x + 0 ASC";
                            } else {
                                $order_by[] = "x ASC";
                            }

                            break;

                        case 1:

                            if ($campo_argumento['tipo'] == 'data') {
                                $order_by[] = "CONCAT(SUBSTR(x, 7, 4), SUBSTR(x, 4, 2), SUBSTR(x, 1, 2)) DESC";
                            } elseif (in_array($campo_argumento['tipo'], ['valor', 'formulavalor'])) {
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
                    $tipo_grafico = $argumento['type'];
                    $coluna = $nome_campo_argumento;

                    $data_campos['x'] = $campo_argumento;
                    $data_campos['tipo_numero'] = $argumento['tipo_numero'];
                }
            }
        } else {
            $magic_names['x'] = 'Total';
        }

        $tipo_grafico = ($type && $type != 'null') ? $type : $tipo_grafico;

        if ($elemento_atual && $tipo_grafico == 'table') {
            $configuracoesCampo = ConsultaItemConfiguracao::find()
                            ->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE])
                            ->andWhere(['id_consulta' => $consulta->id, 'id_item' => $elemento_atual['id']])->orderBy('ordem ASC')->all();

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
                                $nome_campo = (int) $mt - 1;
                                $nome_campo_configuracao = str_replace("{{{$mt}}}", "valor{$nome_campo}", $nome_campo_configuracao);
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

                if ($configuracao->campo->tipo == 'data') {
                    $order_by[] = "CONCAT(SUBSTR(w{$index_configuracao}, 7, 4), SUBSTR(w{$index_configuracao}, 4, 2), SUBSTR(w{$index_configuracao}, 1, 2)) ASC";
                } elseif (in_array($configuracao->campo->tipo, ['valor', 'formulavalor'])) {
                    $order_by[] = "w{$index_configuracao} + 0 ASC";
                } else {
                    $order_by[] = "w{$index_configuracao} ASC";
                }

                $data_campos['w' . $index_configuracao] = $configuracao->campo->attributes;
            }
        }

        if (isset($post['serie']) && !in_array($tipo_grafico, ['pie', 'donut', 'funnel', 'kpi'])) {
            $serie = self::getSerie($post['serie'][0]);

            if ($serie) {
                $nome_campo_serie = 'valor' . ($serie['ordem'] - 1);

                if (in_array($serie['tipo'], ['formulatexto', 'formulavalor'])) {
                    $nome_campo_serie = $serie['campo'];
                    preg_match_all('/[^{{\}}]+(?=}})/', $serie['campo'], $matches);

                    foreach ($matches as $match) {
                        if ($match) {
                            foreach ($match as $mt) {
                                $nomecampo = (int) $mt - 1;
                                $nome_campo_serie = str_replace("{{{$mt}}}", "valor{$nomecampo}", $nome_campo_serie);
                            }
                        }
                    }

                    if ($serie['variavel_formula']) {
                        Yii::$app->db->createCommand($serie['variavel_formula'])->execute();
                    }
                }

                if ($serie['tipo'] == 'data') {
                    if (in_array($serie['formato'], [DateFormatList::DD, DateFormatList::MM, DateFormatList::YYYY])) {
                        $nome_campo_serie = "DATE_FORMAT(STR_TO_DATE({$nome_campo_serie}, '%d/%m/%Y'), '{$serie['formato']}') + 0";
                    } else {
                        $nome_campo_serie = "DATE_FORMAT(STR_TO_DATE({$nome_campo_serie}, '%d/%m/%Y'), '{$serie['formato']}')";
                    }
                }

                $select[] = $nome_campo_serie . " as z ";

                $magic_names['z'] = ($serie['nome']) ? $serie['nome'] : 'null';
                $group_by[] = $nome_campo_serie;

                if ($serie['tipo'] == 'data') {
                    $order_by[] = "CONCAT(SUBSTR(z, 7, 4), SUBSTR(z, 4, 2), SUBSTR(z, 1, 2)) ASC";
                } elseif (in_array($serie['tipo'], ['valor', 'formulavalor'])) {
                    $order_by[] = "z + 0 ASC";
                } else {
                    $order_by[] = "z ASC";
                }

                $data_campos['z'] = $serie;
            }
        }

        $condicao = self::getCondicao($consulta->condicao);
        $where = self::formatWhere($condicao, $data_filtro, $consulta->condicao_avancada);

        $sql = SqlMagic::montaSql($consulta, $select, $where, $group_by, $order_by, 10);
        $print_sql = '';

        if ($sqlMode && Yii::$app->user->identity->id == 1) {
            $print_sql = $sql;
        }

        $error = FALSE;

        try {
            $data_provider = Yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $ex) {
            $data_provider = [];
            $error = TRUE;
        }

        return
                [
                    'elementoAtual' => $elemento_atual,
                    'dataProvider' => $data_provider,
                    'campos' => $data_campos,
                    'ultimo' => (($index_geral + 1) == sizeof($argumentos)) || (sizeof($argumentos) == 0),
                    'nomes' => $magic_names,
                    'error' => $error,
                    'tipoGrafico' => $tipo_grafico,
                    'coluna' => $coluna,
                    'filtro' => $data_filtro,
                    'tipo_valor' => $tipo_valor,
                    'sort' => $sort,
                    'sql' => $print_sql,
                    'series' => !empty($serie)
        ];
    }

    public static function getCondicao($condicao) {
        $data = [];

        if ($condicao)
        {
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

    public static function formatWhere($condicao, $drilldown, $condicao_avancada = null) {
        if (!$condicao && !$drilldown && (!$condicao_avancada || trim($condicao_avancada) == '')) {
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
            $hasFilter = TRUE;
        }

        if ($drilldown) {
            $where .= ($hasFilter) ? " AND " : " ";

            foreach ($drilldown as $w => $valor_filtro) {
                $where .= ($w > 0) ? " AND " : " ";

                if ($valor_filtro['valor'] == 'null') {
                    $where .= "({$valor_filtro['nome']} is null)";
                } else {
                    $where .= "({$valor_filtro['nome']} = '{$valor_filtro['valor']}')";
                }
            }
        }

        return $where;
    }

}
