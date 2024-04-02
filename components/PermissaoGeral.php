<?php

namespace app\components;

use Yii;
use app\models\PerfilPermissao;

class PermissaoGeral {

    public static function can($url, $action) {
        if (!isset(Yii::$app->user->identity)) {
            return false;
        }

        if (Yii::$app->user->identity->perfil->is_admin) {
            return true;
        }

        $data = self::getProfilePermissions();

        return isset($data[$url][$action]) ? $data[$url][$action] : false;
    }

    public static function getProfilePermissions() {
        $perfil = (isset(Yii::$app->user->identity) && Yii::$app->user->identity->perfil_id) ? Yii::$app->user->identity->perfil : null;

        $permissions = false;

        if ($perfil) {
            $cache = Yii::$app->cache;
            $key = "permission_profile_" . $perfil->id;

            $permissions = $cache->get($key);

            if ($permissions === false)
                ; {
                $permissions = self::setProfilePermissions();
            }
        }

        return $permissions;
    }

    public static function setProfilePermissions() {
        $cache = Yii::$app->cache;
        $perfil = (isset(Yii::$app->user->identity) && Yii::$app->user->identity->perfil_id) ? Yii::$app->user->identity->perfil : null;
        $permissions = [];

        if ($perfil) {
            $cache = Yii::$app->cache;
            $key = "permission_profile_" . $perfil->id;

            $models = PerfilPermissao::find()->andWhere([
                        'is_ativo' => TRUE,
                        'is_excluido' => FALSE,
                        'id_perfil' => $perfil->id
                    ])->all();

            foreach ($models as $model) {
                $controller = self::getTextBetweenTags($model->permissao->constante, 'controller');
                $actions = self::getTextBetweenTags($model->permissao->constante, 'action');

                foreach ($actions as $action) {
                    foreach ($action as $result) {
                        $permissions[$controller[1][0]][$result] = 1;
                    }
                }
            }

            $cache->set($key, $permissions);
        }

        return $permissions;
    }

    public static function getTextBetweenTags($string, $tagname) {
        $pattern = "/<{$tagname}>([\w\W]*?)<\/{$tagname}>/";
        preg_match_all($pattern, $string, $matches);

        return $matches;
    }

}
