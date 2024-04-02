<?php

namespace app\magic;

use Yii;

class ConfigMagic {

    public static function getFields($campos, $useRelation = FALSE) {
        $dropdown = '';

        foreach ($campos as $index => $campo) {
            $campo = (!$useRelation) ? $campo : $campo->campo;

            $campo_nome_sbs = (strlen($campo->nome) > 30) ? mb_substr($campo->nome, 0, 30) . ' ...' : $campo->nome;

            $dropdown .= "<li id='el-{$index}' data-id='{$campo->id}' class='attr-list-item justify-content-start align-items-center' style='display: flex;'>";
            $dropdown .= "<span class='title-el' title='" . $campo->nome . "'>" . $campo_nome_sbs . "</span>";
            $dropdown .= "</li>";
        }

        return $dropdown;
    }

}
