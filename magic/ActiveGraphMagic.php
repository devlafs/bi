<?php

namespace app\magic;

use Yii;
use app\models\ConsultaGraficoUsuario;

class ActiveGraphMagic {

    public static function getIconName($field) {
        $data = [
                    'area' => Yii::t('app', 'geral.area'),
                    'line' => Yii::t('app', 'geral.linha'),
                    'bar' => Yii::t('app', 'geral.barra'),
                    'column' => Yii::t('app', 'geral.coluna'),
                    'pie' => Yii::t('app', 'geral.pizza'),
                    'donut' => Yii::t('app', 'geral.donut'),
                    'heatmap' => Yii::t('app', 'geral.heatmap'),
                    'funnel' => Yii::t('app', 'geral.funil'),
                    'kpi' => Yii::t('app', 'geral.kpi'),
                    'table' => Yii::t('app', 'geral.tabela')
        ];

        return (isset($data[$field])) ? $data[$field] : $field;
    }

    public static function getIconData() {
        return
                [
                    'area' => 'chart--area',
                    'line' => 'chart--line',
                    'bar' => 'chart--bar',
                    'column' => 'chart--colum',
                    'pie' => 'chart--pie',
                    'donut' => 'chart--donut',
                    'calor' => 'kanban',
                    'heatmap' => 'kanban',
                    'funnel' => 'chart--funel',
                    'kpi' => 'chart--kpi',
                    'table' => 'chart--grid'
        ];
    }

    public static function getActiveGraph($argumento, $user_id = null) {
        if (!$user_id) {
            $user_id = Yii::$app->user->id;
        }

        $configuracao = ConsultaGraficoUsuario::find()
                        ->andWhere([
                            'is_ativo' => TRUE,
                            'is_excluido' => FALSE,
                            'id_usuario' => $user_id,
                            'id_consulta' => $argumento->id_consulta,
                            'campo' => $argumento->campo->campo,
                        ])->one();

        return ($configuracao) ? $configuracao->tipo_grafico : $argumento->tipo_grafico;
    }

}
