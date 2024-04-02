<?php

namespace app\lists;

use Yii;

class ConfiguracaoGraficoList {

    CONST CONTENT = 'content';
    CONST VIEW = 'view';
    CONST PREVIEW = 'preview';
    CONST MOBILE = 'mobile';
    CONST SHARE = 'share';

    CONST AREA = 'area';
    CONST LINE = 'line';
    CONST COLUMN = 'column';
    CONST BAR = 'bar';
    CONST PIE = 'pie';
    CONST DONUT = 'donut';
    CONST FUNNEL = 'funnel';
    CONST TABLE = 'table';
    CONST HEATMAP = 'heatmap';

    public static function getDataView()
    {
        return [
            self::CONTENT => Yii::t('app', 'geral.paineis'),
            self::VIEW => Yii::t('app', 'geral.consultas'),
            self::PREVIEW => Yii::t('app', 'geral.previsualizacao'),
            self::MOBILE => Yii::t('app', 'geral.mobile'),
            self::SHARE => Yii::t('app', 'geral.compartilhamento')
        ];
    }

    public static function getDataTypes()
    {
        return [
            self::AREA => Yii::t('app', 'geral.area'),
            self::LINE => Yii::t('app', 'geral.linha'),
            self::BAR => Yii::t('app', 'geral.barra'),
            self::COLUMN => Yii::t('app', 'geral.coluna'),
            self::PIE => Yii::t('app', 'geral.pizza'),
            self::DONUT => Yii::t('app', 'geral.donut'),
            self::HEATMAP => Yii::t('app', 'geral.heatmap'),
            self::FUNNEL => Yii::t('app', 'geral.funil'),
            self::TABLE => Yii::t('app', 'geral.tabela')
        ];
    }

    public static function getView($cod) {
        $views = self::getDataView();
        return (isset($views[$cod])) ? $views[$cod] : $cod;
    }

    public static function getType($cod) {
        $types = self::getDataTypes();
        return (isset($types[$cod])) ? $types[$cod] : $cod;
    }

}
