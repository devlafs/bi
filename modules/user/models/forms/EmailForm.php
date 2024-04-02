<?php

namespace app\modules\user\models\forms;

use Yii;
use yii\base\Model;
use app\models\AdminUsuario;

class EmailForm extends Model
{
    public $email;

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
            [["email"], "required"],
            ["email", "validateUser"],
        ];
    }

    public function attributeLabels()
    {
        
        return
        [
            "email" => Yii::t('app', 'usuario_form.email'),
        ];
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
        
        if ($user && (!$user->perfil->is_ativo || $user->perfil->is_excluido || !$user->perfil->acesso_bi))
        {
            $this->addError("email", "Perfil do usuário sem permissão de acesso ao BPbi.");
        }
    }
    
    public function send()
    {
        if ($this->validate()) 
        {
            $user = $this->getUser();
            
            if($user)
            {
                return $user->sendPassword();
            }
        }
        
        return false;
    }
}