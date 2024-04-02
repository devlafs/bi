<?php

namespace app\modules\user\models\forms;

use Yii;
use yii\base\Model;
use app\models\AdminUsuario;

class PasswordForm extends Model
{
    public $senha;

    public $repetir_senha;

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
            [['senha', 'repetir_senha'], "required"],
            [['senha', 'repetir_senha'], 'string', 'max' => 200],
            [['senha', 'repetir_senha'], 'string', 'min' => 6],            
            ['repetir_senha', 'compare', 'compareAttribute' => 'senha', 'message' => "Senhas não conferem"],
           
        ];
    }

    public function attributeLabels()
    {
        
        return
        [
            "senha" => Yii::t('app', 'geral.senha'),
            "repetir_senha" => Yii::t('app', 'usuario_form.repetir_senha'),
        ];
    }
    
    public function save($user)
    {
        if ($this->validate()) 
        {
            if($user)
            {
                $user->senha = base64_encode($user->login . $this->senha);
                $user->save(FALSE, ['senha']);
                
                return true;
            }
        }
        
        return false;
    }
}