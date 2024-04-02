<?php

namespace app\magic;

use Yii;
use app\models\RelatorioCampo;

class RelatorioMagic {

    public static function format($campo, $valor) {

        switch($campo->tipo)
        {
            case RelatorioCampo::TIPO_TEXTO:
            case RelatorioCampo::TIPO_EMAIL:

                return $valor[$campo->campo];

            case RelatorioCampo::TIPO_INTEIRO:

                $value = $valor[$campo->campo] * 1;
                return number_format($value, 0, ',', '.');

            case RelatorioCampo::TIPO_MONETARIO:

                $value = $valor[$campo->campo] * 1;
                return 'R$ ' . number_format($value, 2, ',', '.');

            case RelatorioCampo::TIPO_DATA:

                return Yii::$app->formatter->asDatetime($valor[$campo->campo], "php:d/m/Y");

            case RelatorioCampo::TIPO_DATAHORA:

                return Yii::$app->formatter->asDatetime($valor[$campo->campo], "php:d/m/Y H:i");

            case RelatorioCampo::TIPO_TELEFONE:

                if(!$valor[$campo->campo])
                {
                    return '';
                }

                $tel = preg_replace('/\D/', '', $valor[$campo->campo]);
                return "<a href='tel:+55{$tel}'>{$valor[$campo->campo]}</a>";

            case RelatorioCampo::TIPO_LINK:

                if(!$valor[$campo->campo])
                {
                    return '';
                }

                $link = str_replace('{valor}', $valor[$campo->campo], $campo->options);
                return "<a href='{$link}' target='_blank'>{$valor[$campo->campo]}</a>";

            case RelatorioCampo::TIPO_WHATSAPP:

                if(!$valor[$campo->campo])
                {
                    return '';
                }

                $cel = preg_replace('/\D/', '', $valor[$campo->campo]);
                return "<a target='_blank' href='https://api.whatsapp.com/send?phone=55{$cel}&text=Premedical'><i class='fa fa-whatsapp'></i></a> {$valor[$campo->campo]}";

            default:

                return $valor[$campo->campo];

        }
    }

    public static function getFields($campos, $useRelation = FALSE, $index = 0) {
        $dropdown = '';

        foreach ($campos as $campo) {
            $campo = ($useRelation) ? $campo : $campo->campo;
            $class = (in_array($campo->tipo, RelatorioCampo::$tipo_texto)) ? ' acceptarg ' : '';
            $class .= (in_array($campo->tipo, RelatorioCampo::$tipo_inteiro)) ? ' acceptval ' : '';

            $campo_nome_sbs = (strlen($campo->nome) > 50) ? mb_substr($campo->nome, 0, 50) . '...' : $campo->nome;

            $dropdown .= "<li id='el-{$index}' data-id='{$campo->id}' class='attr-list-item justify-content-start {$class} align-items-center' style='display: flex;'>";
            $dropdown .= $campo->icon;
            $dropdown .= "<span class='title-el' title='" . $campo->nome . "'>" . $campo_nome_sbs . "</span>";
            $dropdown .= "</li>";

            $index++;
        }

        return $dropdown;
    }

    public static function getCurrentSort($type) {
        $class = ($type == 0 || $type == 2) ? 'down' : 'up';

        return "<i class='fa fa-sort-alpha-{$class} mr-2'></i>";
    }

    public static function getCurrentGraph($type) {
        $class = '';

        if($type == 'heatmap')
        {
            return "<i class='bp-kanban mr-2'></i>";

        }

        switch ($type) {
            case 'area':
                $class = 'area';
                break;
            case 'line':
                $class = 'line';
                break;
            case 'bar':
                $class = 'bar';
                break;
            case 'column':
                $class = 'colum';
                break;
            case 'pie':
                $class = 'pie';
                break;
            case 'donut':
                $class = 'donut';
                break;
            case 'funnel':
                $class = 'funel';
                break;
            case 'kpi':
                $class = 'kpi';
                break;
            case 'table':
                $class = 'grid';
        }

        return "<i class='bp-chart--{$class} mr-2'></i>";
    }

    public static function getLabelActive() {
        return "<span class='badge badge-default badge-pill ml-auto'>" . Yii::t('app', 'menu_consulta.texto_ativo') . "</span>";
    }
}
