<?php

namespace app\magic;

use Yii;

class PeriodicidadeMagic {

    CONST HORA = 3600;
    CONST DUAS_HORAS = 7200;
    CONST QUATRO_HORAS = 14400;
    CONST SEIS_HORAS = 21600;
    CONST OITO_HORAS = 28800;
    CONST DOZE_HORAS = 43200;
    CONST DIA = 86400;
    CONST SEMANA = 604800;
    CONST MES = 2592000;

    public static function getDataPeriodo(){
        return [
            self::HORA => Yii::t('app', 'periodicidade.uma_em_uma_hora'),
            self::DUAS_HORAS => Yii::t('app', 'periodicidade.duas_em_duas_horas'),
            self::QUATRO_HORAS => Yii::t('app', 'periodicidade.quatro_em_quatro_horas'),
            self::SEIS_HORAS => Yii::t('app', 'periodicidade.seis_em_seis_horas'),
            self::OITO_HORAS => Yii::t('app', 'periodicidade.oito_em_oito_horas'),
            self::DOZE_HORAS => Yii::t('app', 'periodicidade.doze_em_doze_horas'),
            self::DIA => Yii::t('app', 'periodicidade.diariamente'),
            self::SEMANA => Yii::t('app', 'periodicidade.semanalmente'),
            self::MES => Yii::t('app', 'periodicidade.mensalmente'),
        ];
    }

    public static function getPeriodo($cod) {
        $periodos = self::getDataPeriodo();
        return isset($periodos[$cod]) ? $periodos[$cod] : $cod;
    }

}
