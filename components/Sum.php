<?php

namespace app\components;

class Sum {

    public static function pageTotal($dataprovider, $fieldName) {
        $total = 0;

        if (isset($dataprovider)) {
            foreach ($dataprovider as $data) {
                $total += (isset($data[$fieldName])) ? (double) $data[$fieldName] : 0;
            }
        }

        return $total;
    }

}
