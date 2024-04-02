<?php

namespace app\magic;

use app\lists\TagHtmlList;
use Yii;
use app\models\Sistema;

class CacheMagic {

    public static function getSystemData($info) {
        $data = Sistema::find()->andWhere([
            'campo' => $info
        ])->one();

        return ($data) ? $data->valor : null;
    }

    public static function getHomepageHtml() {
        $html = self::getSystemData('homepage');

        $tags = [
            '#' . TagHtmlList::TAG_LOGO_EMPRESA => CacheMagic::getSystemData('logo'),
            '#' . TagHtmlList::TAG_NOME_EMPRESA => CacheMagic::getSystemData('name'),
            '#' . TagHtmlList::TAG_LINK_ACESSO => CacheMagic::getSystemData('url'),
            '#' . TagHtmlList::TAG_DATA_HOJE => date('d/m/Y'),
        ];

        if($user = Yii::$app->user->identity)
        {
            $tagsUser =
            [
                '#' . TagHtmlList::TAG_PERFIL_USUARIO => $user->perfil->nome,
                '#' . TagHtmlList::TAG_NOME_USUARIO => $user->nomeResumo,
                '#' . TagHtmlList::TAG_EMAIL_USUARIO => $user->email,
                '#' . TagHtmlList::TAG_DEPARTAMENTO_USUARIO => $user->departamento,
                '#' . TagHtmlList::TAG_CARGO_USUARIO => $user->cargo
            ];

            $tags = array_merge($tags, $tagsUser);
        }

        $find = array_keys($tags);
        $replace = array_values($tags);
        return str_ireplace($find, $replace, $html);
    }

}
