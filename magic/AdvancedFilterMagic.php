<?php

namespace app\magic;

use app\magic\FiltroMagic;
use Yii;
use app\models\Consulta;
use app\models\IndicadorCampo;
use app\models\ConsultaFiltroUsuario;
use app\magic\ActiveGraphMagic;
use app\models\ConsultaItemConfiguracao;
use app\lists\DateFormatList;

class AdvancedFilterMagic {

    public static function getFields($id_consulta) {
        $fields = FiltroMagic::getAllArgumentos($id_consulta);

        $arrayFields = [];

        foreach($fields as $field)
        {
            $arrayFields[] =
            [
                'id' => 'valor' . ($field->ordem - 1),
                'name' => $field->nome,
                'avatar' => '',
                'type' => 'field'
            ];
        }

//        $conditions = FiltroMagic::painelFields();
//
//        foreach($conditions as $id => $condition)
//        {
//            $arrayFields[] =
//            [
//                'id' => $id,
//                'name' => $condition,
//                'avatar' => '',
//                'type' => 'condition'
//            ];
//        }

        return $arrayFields;
    }

    public static function getCondicaoAvancada($condicao_avancada)
    {
        try {

            $regex_remove = '/(@\[.*?\])/';
            $condition = preg_replace($regex_remove, '', $condicao_avancada);
            $regex_field = '/(field|condition)\:(\w+)/';
            preg_match_all($regex_field, $condition, $fields);
            $condition = str_replace("@", "", $condition);
            $regex_value = '/(\{{(.*?)\}})/';
            preg_match_all($regex_value, $condition, $values);

            $add_condition = 0;
            foreach($fields[0] as $f2 => $val)
            {
                $data = explode(':', $val);

                if($data[0] == 'field')
                {
                    $condition = str_replace("(field:{$data[1]})", $data[1], $condition);
                }
                else
                {
                    $value = $values[0][$add_condition];
                    $value = str_replace("{{", '', $value);
                    $value = str_replace("}}", '', $value);

                    $condition = str_replace("(condition:{$data[1]})", FiltroMagic::getSinalizador($data[1]), $condition);
                    $condition = str_replace("{{{$value}}}", FiltroMagic::getValorSinalizador($data[1], $value), $condition);
                    $add_condition++;
                }
            }

        } catch (\Exception $e) {
            var_dump($e->getMessage());die;
            $condition = null;
        }

        return $condition;
    }
}
