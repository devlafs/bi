<?php

namespace app\lists;

use Yii;

class DateFormatList {

    CONST DD_MM_YYYY = '%d/%m/%Y';
    CONST DD__MM__YYYY = '%d-%m-%Y';
    CONST MM_YYYY = '%m/%Y';
    CONST MM__YYYY = '%m-%Y';
    CONST DD = '%d';
    CONST MM = '%m';
    CONST YYYY = '%Y';

    public static $formats = [
        self::DD_MM_YYYY => 'DD/MM/AAAA',
        self::DD__MM__YYYY => 'DD-MM-AAAA',
        self::MM_YYYY => 'MM/AAAA',
        self::MM__YYYY => 'MM-AAAA',
        self::DD => 'DD',
        self::MM => 'MM',
        self::YYYY => 'AAAA'
    ];

    public static $masks = [
        self::DD_MM_YYYY => '99/99/999',
        self::DD__MM__YYYY => '99-99-9999',
        self::MM_YYYY => '99/9999',
        self::MM__YYYY => '99-9999',
        self::DD => '99',
        self::MM => '99',
        self::YYYY => '9999'
    ];

    public static function getFormat($cod) {
        return (isset(self::$formats[$cod])) ? self::$formats[$cod] : $cod;
    }

    public static function getMask($cod) {
        return (isset(self::$masks[$cod])) ? self::$masks[$cod] : $cod;
    }
}
