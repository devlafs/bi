<?php

namespace app\magic;

use Yii;

class ConsultaMagic {

    public static function getFields($campos, $useRelation = FALSE, $index = 0, $is_serie = FALSE) {
        $dropdown = '';

        foreach ($campos as $campo) {
            $tipo_grafico = ($useRelation) ? 'column' : $campo->tipo_grafico;
            $ordenacao = ($useRelation) ? '0' : $campo->ordenacao;
            $tipo_numero = ($useRelation) ? '1' : $campo->tipo_numero;
            $campo = ($useRelation) ? $campo : $campo->campo;

            $class = ($campo->tipo == 'data' || $campo->tipo == 'texto' || $campo->tipo == 'valor' || $campo->tipo == 'formulatexto') ? ' acceptser acceptarg ' : '';
            $class .= ($campo->tipo == 'valor' || $campo->tipo == 'formulavalor') ? ' acceptval ' : '';

            $campo_nome_sbs = (strlen($campo->nome) > 50) ? mb_substr($campo->nome, 0, 50) . '...' : $campo->nome;

            $dropdown .= "<li id='el-{$index}' data-id='{$campo->id}' data-type='{$tipo_grafico}' data-sort='{$ordenacao}' data-tipo_numero='{$tipo_numero}' class='attr-list-item justify-content-start {$class} align-items-center' style='display: flex;'>";
            $dropdown .= $campo->icon;
            $dropdown .= "<span class='title-el' title='" . $campo->nome . "'>" . $campo_nome_sbs . "</span>";
            $dropdown .= "<div class='d-flex ml-auto'>";
            $dropdown .= "<div class='dropdown attr-list__toolbar'>";
            $dropdown .= "<a class='btn btn--noborder' rule='button' id='dropdownMenuButton{$index}' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>";
            $dropdown .= "<i class='bp-menu-dots'></i>";
            $dropdown .= "</a>";
            $dropdown .= "<ul class='dropdown-menu' aria-labelledby='dropdownMenuButton{$index}'>";
            $dropdown .= "<li class='dropdown-submenu'>";
            $dropdown .= "<a class='dropdown-item data-sort el-item-" . $index . "' data-sort='" . $ordenacao . "' href='javascript:;'>";
            $dropdown .= self::getCurrentSort($ordenacao);
            $dropdown .= Yii::t('app', 'menu_consulta.ordenacao') . " </a>";
            $dropdown .= "<ul class='dropdown-menu'>";
            $dropdown .= "<h6 class='dropdown-header'>" . Yii::t('app', 'menu_consulta.por_argumento') . ":</h6>";
            $dropdown .= "<a class='dropdown-item choose-sort' data-index='" . $index . "' data-val='0' tabindex='-1' href='javascript:;'><i class='fa fa-sort-alpha-down mr-2'></i> " . Yii::t('app', 'menu_consulta.crescente');
            $dropdown .= ($ordenacao == 0) ? self::getLabelActive() : '';
            $dropdown .= "</a>";
            $dropdown .= "<a class='dropdown-item choose-sort' data-index='" . $index . "' data-val='1' href='javascript:;'><i class='fa fa-sort-alpha-up mr-2'></i> " . Yii::t('app', 'menu_consulta.decrescente');
            $dropdown .= ($ordenacao == 1) ? self::getLabelActive() : '';
            $dropdown .= "</a>";
            $dropdown .= "<h6 class='dropdown-header'>" . Yii::t('app', 'menu_consulta.por_valor') . ":</h6>";
            $dropdown .= "<a class='dropdown-item choose-sort' data-index='" . $index . "' data-val='2' href='javascript:;'><i class='fa fa-sort-alpha-down mr-2'></i> " . Yii::t('app', 'menu_consulta.crescente');
            $dropdown .= ($ordenacao == 2) ? self::getLabelActive() : '';
            $dropdown .= "</a>";
            $dropdown .= "<a class='dropdown-item choose-sort' data-index='" . $index . "' data-val='3' href='javascript:;'><i class='fa fa-sort-alpha-up mr-2'></i> " . Yii::t('app', 'menu_consulta.decrescente');
            $dropdown .= ($ordenacao == 3) ? self::getLabelActive() : '';
            $dropdown .= "</a>";
            $dropdown .= "</ul>";
            $dropdown .= "</li>";
            $dropdown .= "<li class='dropdown-submenu'>";
            $dropdown .= "<a class='dropdown-item data-type el-item-" . $index . "' data-type='" . $tipo_grafico . "' href='javascript:;'>";
            $dropdown .= self::getCurrentGraph($tipo_grafico);
            $dropdown .= Yii::t('app', 'menu_consulta.grafico') . " </a>";
            $dropdown .= "<ul class='dropdown-menu'>";
            $dropdown .= "<a class='dropdown-item choose-type' data-index='" . $index . "' data-val='area' tabindex='-1' href='javascript:;'><i class='bp-chart--area mr-2'></i> " . Yii::t('app', 'geral.area');
            $dropdown .= ($tipo_grafico == 'area') ? self::getLabelActive() : '';
            $dropdown .= "</a>";
            $dropdown .= "<a class='dropdown-item choose-type' data-index='" . $index . "' data-val='line' tabindex='-1' href='javascript:;'><i class='bp-chart--line mr-2'></i> " . Yii::t('app', 'geral.linha');
            $dropdown .= ($tipo_grafico == 'line') ? self::getLabelActive() : '';
            $dropdown .= "</a>";
            $dropdown .= "<a class='dropdown-item choose-type' data-index='" . $index . "' data-val='bar' href='javascript:;'><i class='bp-chart--bar mr-2'></i> " . Yii::t('app', 'geral.barra');
            $dropdown .= ($tipo_grafico == 'bar') ? self::getLabelActive() : '';
            $dropdown .= "</a>";
            $dropdown .= "<a class='dropdown-item choose-type' data-index='" . $index . "' data-val='column' href='javascript:;'><i class='bp-chart--colum mr-2'></i> " . Yii::t('app', 'geral.coluna');
            $dropdown .= ($tipo_grafico == 'column') ? self::getLabelActive() : '';
            $dropdown .= "</a>";
            $dropdown .= "<a class='dropdown-item choose-type' data-index='" . $index . "' data-val='pie' href='javascript:;'><i class='bp-chart--pie mr-2'></i> " . Yii::t('app', 'geral.pizza');
            $dropdown .= ($tipo_grafico == 'pie') ? self::getLabelActive() : '';
            $dropdown .= "</a>";
            $dropdown .= "<a class='dropdown-item choose-type' data-index='" . $index . "' data-val='donut' href='javascript:;'><i class='bp-chart--donut mr-2'></i> " . Yii::t('app', 'geral.donut');
            $dropdown .= ($tipo_grafico == 'donut') ? self::getLabelActive() : '';
            $dropdown .= "</a>";
            $dropdown .= "<a class='dropdown-item choose-type' data-index='" . $index . "' data-val='funnel' href='javascript:;'><i class='bp-chart--funel mr-2'></i> " . Yii::t('app', 'geral.funil');
            $dropdown .= ($tipo_grafico == 'funnel') ? self::getLabelActive() : '';
            $dropdown .= "</a>";
            $dropdown .= "<a class='dropdown-item choose-type' data-index='" . $index . "' data-val='kpi' href='javascript:;'><i class='bp-chart--kpi mr-2'></i> " . Yii::t('app', 'geral.kpi');
            $dropdown .= ($tipo_grafico == 'kpi') ? self::getLabelActive() : '';
            $dropdown .= "</a>";
            $dropdown .= "<a class='dropdown-item choose-type' data-index='" . $index . "' data-val='table' href='javascript:;'><i class='bp-chart--grid mr-2'></i> " . Yii::t('app', 'geral.tabela');
            $dropdown .= ($tipo_grafico == 'table') ? self::getLabelActive() : '';
            $dropdown .= "</a>";
            $dropdown .= "<a class='dropdown-item choose-type' data-index='" . $index . "' data-val='heatmap' href='javascript:;'><i class='bp-kanban mr-2'></i> " . Yii::t('app', 'geral.heatmap');
            $dropdown .= ($tipo_grafico == 'heatmap') ? self::getLabelActive() : '';
            $dropdown .= "</a>";
            $dropdown .= "</ul>";
            $dropdown .= "</li>";
            $dropdown .= "<li class='dropdown-submenu'>";
            $dropdown .= "<a class='dropdown-item data-tipo_numero el-item-" . $index . "' data-tipo_numero='" . $tipo_grafico . "' href='javascript:;'>";
            $dropdown .= '<i class="bp-number mr-2" style="font-size: 18px;"></i>';
            $dropdown .= Yii::t('app', 'menu_consulta.valor') . " </a>";
            $dropdown .= "<ul class='dropdown-menu'>";
            $dropdown .= "<a class='dropdown-item choose-tipo_numero' data-index='" . $index . "' data-val='1' tabindex='-1' href='javascript:;'> " . Yii::t('app', 'menu_consulta.sem_formatacao');
            $dropdown .= ($tipo_numero == 1) ? self::getLabelActive() : '';
            $dropdown .= "</a>";
            $dropdown .= "<div class='dropdown-divider'></div>";
            $dropdown .= "<a class='dropdown-item choose-tipo_numero' data-index='" . $index . "' data-val='2' tabindex='-1' href='javascript:;'> " . Yii::t('app', 'menu_consulta.milhares');
            $dropdown .= ($tipo_numero == 2) ? self::getLabelActive() : '';
            $dropdown .= "</a>";
            $dropdown .= "<a class='dropdown-item choose-tipo_numero' data-index='" . $index . "' data-val='3' tabindex='-1' href='javascript:;'> " . Yii::t('app', 'menu_consulta.milhoes');
            $dropdown .= ($tipo_numero == 3) ? self::getLabelActive() : '';
            $dropdown .= "</a>";
            $dropdown .= "<a class='dropdown-item choose-tipo_numero' data-index='" . $index . "' data-val='4' tabindex='-1' href='javascript:;'> " . Yii::t('app', 'menu_consulta.bilhoes');
            $dropdown .= ($tipo_numero == 4) ? self::getLabelActive() : '';
            $dropdown .= "</a>";
            $dropdown .= "<a class='dropdown-item choose-tipo_numero' data-index='" . $index . "' data-val='5' tabindex='-1' href='javascript:;'> " . Yii::t('app', 'menu_consulta.trilhoes');
            $dropdown .= ($tipo_numero == 5) ? self::getLabelActive() : '';
            $dropdown .= "</a>";
            $dropdown .= "</ul>";
            $dropdown .= "</li>";
            $dropdown .= "<li class='dropdown-divider'></li>";
            $dropdown .= "<li class='dropdown-item open-color' data-title='" . $campo_nome_sbs . "' data-id='" . $campo->id . "' href='javascript:;'>" . Yii::t('app', 'menu_consulta.cores_personalizadas') . "</li>";
            $dropdown .= "<li class='dropdown-item open-config' data-title='" . $campo_nome_sbs . "' data-id='" . $campo->id . "' href='javascript:;'>" . Yii::t('app', 'menu_consulta.campos_adicionais') . "</li>";

            if (Yii::$app->user->identity->id == 1) {
                $dropdown .= "<li class='dropdown-submenu'>";
                $dropdown .= "<a class='dropdown-item config-avancadas config-avancadas-" . $campo->id . "' data-title='" . $campo_nome_sbs . "' data-id='" . $campo->id .
                        "' data-graph='" . $tipo_grafico . "' data-isserie='" . $is_serie . "'>" . Yii::t('app', 'menu_consulta.conf_avancadas') . "</a>";
                $dropdown .= "<ul class='dropdown-menu'>";
                $dropdown .= "<a class='dropdown-item config-avancadas-item' data-id='" . $campo->id . "' data-view='view' tabindex='-1' href='javascript:;'>" . Yii::t('app', 'geral.consulta') . "</a>";
                $dropdown .= "<a class='dropdown-item config-avancadas-item' data-id='" . $campo->id . "' data-view='content' tabindex='-1' href='javascript:;'>" . Yii::t('app', 'geral.painel') . "</a>";
                $dropdown .= "<a class='dropdown-item config-avancadas-item' data-id='" . $campo->id . "' data-view='mobile' tabindex='-1' href='javascript:;'>" . Yii::t('app', 'geral.mobile') . "</a>";
                $dropdown .= "<a class='dropdown-item config-avancadas-item' data-id='" . $campo->id . "' data-view='share' tabindex='-1' href='javascript:;'>" . Yii::t('app', 'geral.compartilhamento') . "</a>";
                $dropdown .= "</ul>";
                $dropdown .= "</li>";
            }

            $dropdown .= "</ul>";
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
