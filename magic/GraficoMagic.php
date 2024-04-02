<?php

namespace app\magic;

use Yii;
use app\models\GraficoConfiguracao;
use app\models\ConsultaCampoConfiguracao;

class GraficoMagic {

    public static $data_grafico = [
        'area' => 'multi',
        'bar' => 'multi',
        'column' => 'multi',
        'line' => 'multi',
        'pie' => 'uni',
        'donut' => 'uni',
        'funnel' => 'uni',
        'kpi' => 'kpi',
        'table' => 'table',
        'heatmap' => 'heatmap'
    ];

    public static function getData($consulta_id, $view, $tipo, $campo_id = null, $is_serie = FALSE) {
        $configuracoes = null;

        if ($campo_id) {
            $configuracoes = ConsultaCampoConfiguracao::find()->andWhere([
                        'is_ativo' => TRUE,
                        'is_excluido' => FALSE,
                        'id_consulta' => $consulta_id,
                        'id_campo' => $campo_id,
                        'view' => $view,
                        'tipo' => $tipo,
                        'is_serie' => $is_serie
                    ])->one();
        }

        if (!$configuracoes) {
            $configuracoes = GraficoConfiguracao::find()->
                            andWhere([
                                'is_ativo' => TRUE,
                                'is_excluido' => FALSE,
                                'is_serie' => $is_serie,
                                'view' => $view,
                                'tipo' => $tipo
                            ])->one();
        }

        return $configuracoes;
    }

}
