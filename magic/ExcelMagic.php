<?php

namespace app\magic;

use Yii;
use app\magic\ResultMagic;

class ExcelMagic {

    public static function getData($data) {
        $models = [];
        $nomes = $data['nomes'];
        $attrs = $attributes = [];

        foreach ($data['dataProvider'] as $index => $dado) {
            if (isset($nomes['z'])) {
                $models[$index][trim($nomes['z'])] = ResultMagic::format($dado['z'], $data['campos']['z'], 1, TRUE);
                $attributes[trim($nomes['z'])] = 1;
            }

            if (isset($nomes['x'])) {
                $models[$index][trim($nomes['x'])] = ResultMagic::format($dado['x'], $data['campos']['x'], 1, TRUE);
                $attributes[trim($nomes['x'])] = 1;
            } else {
                $models[$index][] = '-';
                $attributes[trim($nomes['Total'])] = 1;
            }

            if (isset($nomes['w'])) {
                foreach ($nomes['w'] as $column => $value) {
                    $models[$index][trim($value)] = ResultMagic::format($dado['w' . $column], $data['campos']['w' . $column], 1, TRUE);
                    $attributes[trim($value)] = 1;
                }
            }

            $models[$index][trim($nomes['y'])] = ResultMagic::format($dado['y'], $data['campos']['y'], $data['campos']['tipo_numero'], TRUE);
            $attributes[trim($nomes['y'])] = 1;
        }

        foreach($attributes as $column => $attr)
        {
            $attrs[] = ['attribute' => $column];
        }

        return ['models' => $models, 'attributes' => $attrs];
    }
}
