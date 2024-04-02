<?php

namespace app\modules\user\models;

use Yii;
use yii\base\Model;
use yii\web\IdentityInterface;

class User extends Model implements IdentityInterface
{
    public $id;
    
    public $desc_uid;
    
    public $perfil_id;
    
    public $nome;
    
    public $version;
    
    public $module;
    
    public function init()
    {
        if (!$this->module)
        {
            $this->module = Yii::$app->getModule("user");
        }
    }

    public function rules()
    {
        $rules = 
        [
            [['id', 'desc_uid', 'perfil_id', 'nome', 'version'], 'safe'],
        ];

        return $rules;
    }

    public function attributeLabels()
    {
        return 
        [
            'id' => 'ID',
            'desc_uid' => 'UID',
            'perfil_id' => 'Perfil',
            'nome' => 'Nome',
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

    public static function findIdentity($id): IdentityInterface 
    {
        return new User();
    }

    public static function findIdentityByAccessToken($token, $type = null): IdentityInterface {
        
    }

}