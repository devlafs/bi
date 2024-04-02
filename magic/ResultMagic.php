<?php

namespace app\magic;

use Yii;

class ResultMagic {

    public static function abbreviation($number, $tipo_numero, $decimals, $separador_decimal = ',', $separador_milhar = '.') {
        $abbrevs = [12 => "T", 9 => "B", 6 => "M", 3 => "K", 0 => ""];
        $choice = ["" => 0, "K" => 2, "M" => 3, "B" => 4, "T" => 5];

        foreach ($abbrevs as $exponent => $abbrev) {
            if ($tipo_numero == $choice[$abbrev]) {
                $display_num = $number / pow(10, $exponent);

                return number_format($display_num, $decimals, $separador_decimal, $separador_milhar) . $abbrev;
            }
        }
    }

    public static function formatText($value, $prefixo = '', $sufixo = '') {
        return trim($prefixo . $value . $sufixo);
    }

    public static function formatValue($value, $tipo_numero = 1, $casas_decimais = 2, $prefixo = '', $sufixo = '', $separador_decimal = ',', $separador_milhar = '.') {
        $value = (double) $value;

        return ($tipo_numero == 1) ? trim($prefixo . number_format($value, $casas_decimais, $separador_decimal, $separador_milhar) . $sufixo) : trim($prefixo . self::abbreviation($value, $tipo_numero, $casas_decimais, $separador_decimal, $separador_milhar) . $sufixo);
    }

    public static function validateDate($value) {
        $formats = ['d/m/Y', 'd-m-Y', 'Y-m-d', 'Y/m/d'];

        foreach ($formats as $f) {
            $d = \DateTime::createFromFormat($f, $value);
            $is_date = $d && $d->format($f) === $value;

            if (true == $is_date)
                break;
        }

        return $is_date;
    }

    public static function formatDate($value, $formato = 'd/m/Y') {
        if (!self::validateDate($value)) {
            return Yii::t('app', 'erro.formato_invalido');
        }

        $date = date_format(date_create_from_format('d/m/Y', $value), 'Y-m-d');
        $formato_php = str_replace("%", "", $formato);

        return trim(date($formato_php, strtotime($date)));
    }

    public static function format($value, $model, $tipo_numero = 1, $dateForce = FALSE) {
        if (!$model) {
            return $value;
        }

        $tipo = $model['tipo'];
        $prefixo = $model['prefixo'];
        $sufixo = $model['sufixo'];

        switch ($tipo) {
            case 'formulatexto':
            case 'texto':

                return self::formatText($value, $prefixo, $sufixo);

            case 'formulavalor':
            case 'valor':

                $casas_decimais = $model['casas_decimais'];
                $separador_decimal = $model['separador_decimal'];
                $separador_milhar = $model['separador_milhar'];

                return self::formatValue($value, $tipo_numero, $casas_decimais, $prefixo, $sufixo, $separador_decimal, $separador_milhar);

            case 'data':

                return (!$dateForce) ? self::formatDate($value, $model['formato']) : $value;
        }
    }

}
