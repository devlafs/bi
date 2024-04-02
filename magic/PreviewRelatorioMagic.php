<?php

namespace app\magic;

use app\models\RelatorioCampo;
use app\models\searches\RelatorioDinamicoSearch;
use Yii;
use app\magic\SqlMagic;
use app\magic\FiltroMagic;
use app\lists\DateFormatList;

class PreviewRelatorioMagic {

    public static function getValor($id) {
        return RelatorioCampo::find()->andWhere(['id' => $id])->asArray()->one();
    }

    public static function getArgumentos($argumentos) {
        $data = [];

        foreach ($argumentos as $argumento) {
            $campo = RelatorioCampo::find()->andWhere(['id' => $argumento['id']])->asArray()->one();
            $data[] = ['campo' => $campo, 'id' => $argumento['id']];
        }

        return $data;
    }

    public static function getData($relatorio, $post, $sqlMode = FALSE) {

        $data_campos = ['x' => null, 'y' => null];
        if (!isset($post['valor'][0])) {
            return [];
        }

        $valor = self::getValor($post['valor'][0]);

        if ($valor) {
            $data_campos['y'] = $valor;
        }

        $argumentos = (isset($post['argumento'])) ? self::getArgumentos($post['argumento']) : [];

        foreach ($argumentos as $argumento)
        {
            $data_campos['x'][] = $argumento['campo'];
        }

        $searchModel = new RelatorioDinamicoSearch();
        $searchModel->relatorio = $relatorio;
        $searchModel->campos = $argumentos;
        $searchModel->getAttributesDynamicFields();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $sqlMode, 10);

        return
                [
                    'dataProvider' => $dataProvider,
                    'campos' => $data_campos,
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
