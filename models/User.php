<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\IdentityInterface;

class User extends Model implements IdentityInterface {

    public $id;
    public $desc_uid;
    public $perfil_id;
    public $nome;
    public $version;

    public function rules() {
        $rules = [
                    [['id', 'desc_uid', 'perfil_id', 'nome', 'version'], 'safe'],
        ];

        return $rules;
    }

    public function attributeLabels() {
        return
                [
                    'id' => 'ID',
                    'desc_uid' => 'UID',
                    'perfil_id' => Yii::t('app', 'geral.perfil'),
                    'nome' => Yii::t('app', 'geral.nome'),
                    'version' => 'Versão'
        ];
    }

    public function getAuthKey(): string {
        return '';
    }

    public function getId() {
        return $this->id;
    }

    public function validateAuthKey($authKey): bool {
        return true;
    }

    public static function findIdentity($id): IdentityInterface {
        return new User();
    }

    public static function findIdentityByAccessToken($token, $type = null): IdentityInterface {
        
    }

}
