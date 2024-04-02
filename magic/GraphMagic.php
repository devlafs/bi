<?php

namespace app\magic;

use Yii;

class GraphMagic {

    CONST TYPE_BAR = 'bar';
    CONST TYPE_HORIZONTALBAR = 'horizontal_bar';
    CONST TYPE_COLUMN = 'column';
    CONST TYPE_FUNNEL = 'funnel';
    CONST TYPE_GAUGE = 'gauge';
    CONST TYPE_LINE = 'line';
    CONST TYPE_MAP = 'map';
    CONST TYPE_PIE = 'pie';
    CONST TYPE_TABLE = 'table';
    CONST TYPE_KPI = 'kpi';
    CONST TYPE_DONUT = 'donut';
    CONST TYPE_BARLINE = 'barline';
    CONST TYPE_HEATMAP = 'heatmap';

    public static function getView($tipo) {
        return ($tipo) ? "/site/charts/_{$tipo}" : "/site/charts/_pie";
    }

    public static function getFormattedData($data) {
        $aspas = ["'", '"'];

        $return = ['dataProvider' => [], 'titulo' => '', 'tituloX' => '', 'tituloY' => ''];

        $return['filtros'] = $data['filtros'];
        $return['breadcrumbs'] = $data['filtrosTemporarios'];
        $return['elementos'] = $data['elementos'];
        $return['tipo'] = $data['tipo'];
        $return['titulo'] = $data['titulo'];
        $return['tituloX'] = $data['tituloX'];
        $return['tituloY'] = $data['tituloY'];
        $return['last'] = $data['ultimaDimensao'];
        $return['eixoX'] = [];
        $return['eixoY'] = [];

        foreach ($data['elementosEixoX'] as $ix => $elx) {
            $return['eixoX'][$ix] = $elx['titulo'];
        }

        foreach ($data['elementosEixoY'] as $iy => $ely) {
            $return['eixoY'][$iy] = $ely['titulo'];
        }

        foreach ($data['lista'] as $il => $lista) {
            foreach ($data['elementosEixoX'] as $x => $elx) {
                $tituloX = str_replace($aspas, '', $elx['titulo']);
                $listaX = str_replace($aspas, '', $lista["x" . $x]);

                $return['dataProvider'][$il][$tituloX] = $listaX;
            }

            foreach ($data['elementosEixoY'] as $iy => $ely) {
                $tituloY = str_replace($aspas, '', $ely['titulo']);
                $listaY = str_replace($aspas, '', $lista["y" . $iy]);

                $return['dataProvider'][$il][$tituloY] = $listaY;
            }

            $cor0 = str_replace($aspas, '', $lista["cor0"]);

            $return['dataProvider'][$il]['color'] = $cor0;
        }

        return $return;
    }

}
