<?php

namespace app\magic;

use app\models\ConsultaItemCor;
use Yii;

class DataProviderMagic {

    public static function getData($dataProvider, $type, $usingSerie = FALSE, $campos = null) {
        $data = [];

        switch ($type) {
            case 'table':
                $data = self::getDataTable($dataProvider, $usingSerie);
                break;
            case 'column':
            case 'area':
            case 'line':
            case 'bar':
            case 'heatmap':
                $data = self::getDataArea($dataProvider, $usingSerie, $campos);
                break;
            case 'pie':
            case 'donut':
            case 'funnel':
            case 'kpi':
                $data = self::getDataPie($dataProvider, $campos);
                break;
        }

        return $data;
    }

    public static function getDataTable($dataProvider, $usingSerie) {
        $dataTable = ['data' => []];

        if ($dataProvider) {
            foreach ($dataProvider as $index => $data) {
                if ($usingSerie) {
                    $dataTable['data'][$data['z']]['data'][] = $data;
                } else {
                    $dataTable['data'][$index]['data'][] = $data;
                }
            }
        }

        return $dataTable;
    }

    public static function getDataArea($dataProvider, $usingSerie, $campos) {
        $dataPie = ['x' => [], 'z' => [], 'data' => []];
        $dataReturn = [];

        if ($dataProvider) {
            foreach ($dataProvider as $data) {
                if (!isset($data['x']) && !$campos['x']) {
                    $dataPie['x'][0] = 'Total';

                    if ($usingSerie) {
                        if (!in_array($data['z'], $dataPie['z'])) {
                            $dataPie['z'][] = $data['z'];
                        }

                        $dataPie['data'][$data['z']]["Total"] = (isset($dataPie['data'][$data['z']]["Total"])) ? $dataPie['data'][$data['z']]["Total"] + (double) $data['y'] : (double) $data['y'];
                    } else {
                        $dataPie['data']["Total"] = (isset($dataPie['data']["Total"])) ? $dataPie['data']["Total"] + (double) $data['y'] : (double) $data['y'];
                    }
                } elseif (!isset($data['x'])) {
                    $dataPie['x'][] = 'null';
                    $dataReturn['filter'][] = 'null';

                    if ($usingSerie) {
                        if (!in_array($data['z'], $dataPie['z'])) {
                            $dataPie['z'][] = $data['z'];
                        }

                        $dataPie['data'][$data['z']]["null"] = (isset($dataPie['data'][$data['z']]["null"])) ? $dataPie['data'][$data['z']]["null"] + (double) $data['y'] : (double) $data['y'];
                    } else {
                        $dataPie['data']["null"] = (isset($dataPie['data']["null"])) ? $dataPie['data']["null"] + (double) $data['y'] : (double) $data['y'];
                    }
                } else {
                    if (!in_array($data['x'], $dataPie['x'])) {
                        $dataPie['x'][] = $data['x'];
                        $dataReturn['filter'][] = $data['x'];
                    }

                    if ($usingSerie) {
                        if (!in_array($data['z'], $dataPie['z'])) {
                            $dataPie['z'][] = $data['z'];
                        }

                        $dataPie['data'][$data['z']][$data['x']] = (isset($dataPie['data'][$data['z']][$data['x']])) ? $dataPie['data'][$data['z']][$data['x']] + (double) $data['y'] : (double) $data['y'];
                    } else {
                        $dataPie['data'][$data['x']] = (isset($dataPie['data'][$data['x']])) ? $dataPie['data'][$data['x']] + (double) $data['y'] : (double) $data['y'];
                    }
                }
            }
        }

        foreach ($dataPie as $idx_dtPie => $dtPie) {
            if ($idx_dtPie != 'data') {
                foreach ($dtPie as $idx_vlPie => $vlPie) {
                    $dataReturn[$idx_dtPie][$idx_vlPie] = ResultMagic::format($vlPie, $campos[$idx_dtPie], 1, TRUE);
                }
            } else {
                if ($usingSerie) {
                    foreach ($dtPie as $idx_serie => $vlPie) {
                        foreach ($vlPie as $idx_x => $vl_y) {
                            $z = ResultMagic::format($idx_serie, $campos['z'], 1, TRUE);
                            $x = ResultMagic::format($idx_x, $campos['x'], 1, TRUE);

                            $dataReturn['data'][$z][$x] = $vl_y;
                        }
                    }
                } else {
                    foreach ($dtPie as $idx_x => $vl_y) {
                        $x = ResultMagic::format($idx_x, $campos['x'], 1, TRUE);

                        $dataReturn['data'][$x] = $vl_y;
                    }
                }
            }
        }

        return $dataReturn;
    }

    public static function getDataPie($dataProvider, $campos) {
        $dataPie = ['x' => [], 'data' => []];

        if ($dataProvider) {
            foreach ($dataProvider as $data) {
                if (!isset($data['x']) && !$campos['x']) {
                    $dataPie['x'][0] = 'Total';

                    $dataPie['data']["Total"] = (isset($dataPie['data']["Total"])) ? $dataPie['data']["Total"] + (double) $data['y'] : (double) $data['y'];
                } elseif (!isset($data['x'])) {
                    $dataPie['x'][] = 'null';

                    $dataPie['data']["null"] = (isset($dataPie['data']["null"])) ? $dataPie['data']["null"] + (double) $data['y'] : (double) $data['y'];
                } else {
                    if (!in_array($data['x'], $dataPie['x'])) {
                        $dataPie['x'][] = $data['x'];
                    }

                    $dataPie['data'][$data['x']] = (isset($dataPie['data'][$data['x']])) ? $dataPie['data'][$data['x']] + (double) $data['y'] : (double) $data['y'];
                }
            }
        }

        $dataReturn = [];

        foreach ($dataPie as $idx_dtPie => $dtPie) {
            if ($idx_dtPie != 'data') {
                foreach ($dtPie as $idx_vlPie => $vlPie) {
                    $dataReturn[$idx_dtPie][$idx_vlPie] = ResultMagic::format($vlPie, $campos[$idx_dtPie], 1, TRUE);
                }
            } else {
                foreach ($dtPie as $idx_x => $vl_y) {
                    $x = ResultMagic::format($idx_x, $campos['x'], 1, TRUE);

                    $dataReturn['data'][$x] = $vl_y;
                    $dataReturn['filter'][] = $idx_x;
                }
            }
        }

        return $dataReturn;
    }

    public static function getDataColor($id_consulta, $id_campo, $dataProvider) {
        $dataColor = [];

        if ($dataProvider) {

            $hasConfs = ConsultaItemCor::find()->andWhere([
                'id_consulta' => $id_consulta,
                'id_campo' => $id_campo,
                'is_ativo' => TRUE,
                'is_excluido' => FALSE
            ])->exists();

            if($hasConfs)
            {
                foreach ($dataProvider as $data) {

                    $itemColor = ConsultaItemCor::find()->andWhere([
                        'id_consulta' => $id_consulta,
                        'id_campo' => $id_campo,
                        'valor' => $data,
                        'is_ativo' => TRUE,
                        'is_excluido' => FALSE
                    ])->one();

                    $dataColor[$data] = ($itemColor) ? $itemColor->cor : null;
                }
            }
        }

        return $dataColor;
    }
}
