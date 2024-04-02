<?php

namespace app\lists;

use Yii;

class TagsList {

    CONST TAG_HOJE = 0;
    CONST TAG_DIAS_ATRAS = 1;
    CONST TAG_DIAS_A_FRENTE = 2;
    CONST TAG_MESES_ATRAS = 3;
    CONST TAG_MESES_A_FRENTE = 4;
    CONST TAG_ANOS_ATRAS = 5;
    CONST TAG_ANOS_A_FRENTE = 6;

    public static function getDataList() {
        return [
            self::TAG_HOJE => Yii::t('app', 'geral.hoje'),
            self::TAG_DIAS_ATRAS => Yii::t('app', 'geral.dias_atras'),
            self::TAG_DIAS_A_FRENTE => Yii::t('app', 'geral.dias_a_frente'),
            self::TAG_MESES_ATRAS => Yii::t('app', 'geral.meses_atras'),
            self::TAG_MESES_A_FRENTE => Yii::t('app', 'geral.meses_a_frente'),
            self::TAG_ANOS_ATRAS => Yii::t('app', 'geral.anos_atras'),
            self::TAG_ANOS_A_FRENTE => Yii::t('app', 'geral.anos_a_frente')
        ];
    }

    public static function getElementName($cod) {
        $list = self::getDataList();
        return (isset($list[$cod])) ? $list[$cod] : $cod;
    }

}
