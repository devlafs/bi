<?php

namespace app\lists;

use Yii;

class FrequenciaList {

    CONST DOMINGO = 0;
    CONST SEGUNDA_FEIRA = 1;
    CONST TERCA_FEIRA = 2;
    CONST QUARTA_FEIRA = 3;
    CONST QUINTA_FEIRA = 4;
    CONST SEXTA_FEIRA = 5;
    CONST SABADO = 6;

    public static function getDataSemanal() {
        return [
            self::DOMINGO => Yii::t('app', 'geral.domingo'),
            self::SEGUNDA_FEIRA => Yii::t('app', 'geral.segunda'),
            self::TERCA_FEIRA => Yii::t('app', 'geral.terca'),
            self::QUARTA_FEIRA => Yii::t('app', 'geral.quarta'),
            self::QUINTA_FEIRA => Yii::t('app', 'geral.quinta'),
            self::SEXTA_FEIRA => Yii::t('app', 'geral.sexta'),
            self::SABADO => Yii::t('app', 'geral.sabado'),
        ];
    }

    public static function getDataWeek() {
        return [
            self::DOMINGO => 'sunday',
            self::SEGUNDA_FEIRA => 'monday',
            self::TERCA_FEIRA => 'tuesday',
            self::QUARTA_FEIRA => 'wednesday',
            self::QUINTA_FEIRA => 'thursday',
            self::SEXTA_FEIRA => 'friday',
            self::SABADO => 'saturday'
        ];
    }

    public static function getNomeSemana($cod) {
        $semanal = self::getDataSemanal();
        return (isset($semanal[$cod])) ? $semanal[$cod] : $cod;
    }

    public static function getWeekName($cod) {
        $week = self::getDataWeek();
        return (isset($week[$cod])) ? $week[$cod] : $cod;
    }

    public static function getDiasMes() {
        $dias = [];

        for ($dia = 1; $dia <= 31; $dia++) {
            $dias[$dia] = $dia;
        }

        return $dias;
    }

    public static function getHoras() {
        $horas = [];

        for ($hora = 0; $hora <= 23; $hora++) {
            $str_hora = ($hora < 10) ? "0{$hora}:00" : "{$hora}:00";

            $horas[$hora] = $str_hora;
        }

        return $horas;
    }

    public static function getNomeHora($cod) {
        $list = self::getHoras();

        return (isset($list[$cod])) ? $list[$cod] : $cod;
    }

}
