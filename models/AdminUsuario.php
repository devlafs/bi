<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;
use app\models\UsuarioToken;
use app\magic\CacheMagic;

class AdminUsuario extends \yii\db\ActiveRecord implements IdentityInterface {

    public $version;
    public $module;
    public $perfil_nome;
    public $repetir_senha;
    public $nova_senha;
    public $repetir_nova_senha;

    public static function getDb() {
        return Yii::$app->get('userDb');
    }

    public function init() {
        if (!$this->module) {
            $this->module = Yii::$app->getModule("user");
        }
    }

    public static function tableName() {
        return 'admin_usuario';
    }

    public function rules() {
        return
                [
                    [['nome', 'nomeResumo', 'perfil_id', 'login', 'email'], 'required'],
                    [['senha', 'repetir_senha'], 'required', 'on' => 'create'],
                    [['departamento', 'obs'], 'string'],
                    [['language', 'acesso_bi', 'created_at', 'updated_at', 'is_ativo', 'is_excluido', 'created_by', 'updated_by', 'perfil_nome', 'status'], 'safe'],
                    [['perfil_id',], 'integer'],
                    [['nome'], 'string', 'max' => 100],
                    [['cargo', 'login'], 'string', 'max' => 50],
                    [['celular'], 'string', 'max' => 150],
                    [['email'], 'string', 'max' => 250],
                    [['nomeResumo'], 'string', 'max' => 40],
                    [['senha', 'nova_senha', 'repetir_senha', 'repetir_nova_senha'], 'string', 'max' => 200],
                    [['senha', 'nova_senha', 'repetir_senha', 'repetir_nova_senha'], 'string', 'min' => 6],
                    [['email'], 'unique'],
                    [['email'], 'email'],
                    [['login'], 'unique'],
                    ['repetir_senha', 'compare', 'compareAttribute' => 'senha', 'skipOnEmpty' => false, 'message' => "Senhas não conferem", 'on' => 'create'],
                    ['repetir_nova_senha', 'compare', 'compareAttribute' => 'nova_senha', 'skipOnEmpty' => true, 'message' => "Senhas não conferem", 'on' => 'update'],
        ];
    }

    public function attributeLabels() {
        return
                [
                    'cargo' => Yii::t('app', 'usuario.cargo'),
                    'celular' => Yii::t('app', 'usuario.celular'),
                    'departamento' => Yii::t('app', 'geral.departamento'),
                    'email' => Yii::t('app', 'geral.email'),
                    'login' => Yii::t('app', 'usuario.login'),
                    'nome' => Yii::t('app', 'usuario.nome'),
                    'nomeResumo' => Yii::t('app', 'usuario.nomeResumo'),
                    'obs' => Yii::t('app', 'usuario.obs'),
                    'perfil_id' => Yii::t('app', 'geral.perfil'),
                    'perfil_nome' => Yii::t('app', 'geral.perfil'),
                    'senha' => Yii::t('app', 'geral.senha'),
                    'nova_senha' => Yii::t('app', 'geral.senha'),
                    'repetir_senha' => Yii::t('app', 'usuario.repetir_senha'),
                    'repetir_nova_senha' => Yii::t('app', 'usuario.repetir_nova_senha'),
                    'acesso_bi' => Yii::t('app', 'usuario.acesso_bi'),
                    'is_ativo' => Yii::t('app', 'geral.is_ativo'),
                    'is_excluido' => Yii::t('app', 'geral.is_excluido'),
                    'created_at' => Yii::t('app', 'geral.created_at'),
                    'updated_at' => Yii::t('app', 'geral.updated_at'),
                    'created_by' => Yii::t('app', 'geral.created_by'),
                    'updated_by' => Yii::t('app', 'geral.updated_by'),
        ];
    }

    public function behaviors() {
        $behaviors = [
                    [
                        'class' => \app\behaviors\ChangeLogBehavior::className(),
                        'excludedAttributes' => ['updated_at'],
                    ],
                    [
                        'class' => \yii\behaviors\BlameableBehavior::className(),
                        'createdByAttribute' => 'created_by',
                        'updatedByAttribute' => 'updated_by',
                    ],
                    [
                        'class' => \yii\behaviors\TimestampBehavior::className(),
                        'createdAtAttribute' => 'created_at',
                        'updatedAtAttribute' => 'updated_at',
                        'value' => new \yii\db\Expression('NOW()'),
                    ],
        ];

        return array_merge(parent::behaviors(), $behaviors);
    }

    public function getPerfil() {
        return $this->hasOne(AdminPerfil::className(), ['id' => 'perfil_id']);
    }

    public function getUsuarioDepartamento() {
        return $this->hasMany(AdminUsuarioDepartamento::className(), ['usuario_id' => 'id']);
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
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null): IdentityInterface {
        
    }

    public function beforeSave($insert) {

        if ($this->isNewRecord) {
            $this->senha = base64_encode($this->login . $this->senha);

            $pessoal = new AdminUsuarioPessoal();
            $pessoal->save();

            $this->id = $pessoal->id;
        } else {
            if ($this->nova_senha && $this->repetir_nova_senha) {
                $this->senha = base64_encode($this->login . $this->nova_senha);
            }
        }

        return parent::beforeSave($insert);
    }

    public function sendPassword() {
        $token = Yii::$app->security->generateRandomString() . time();

        $modelToken = new UsuarioToken();
        $modelToken->id_usuario = $this->id;
        $modelToken->token = $token;
        $modelToken->save();

        \Yii::$app->mailer->htmlLayout = "@app/mail/layouts/html";

        $url = CacheMagic::getSystemData('url') . "/user/password?t={$token}";

        $message = \Yii::$app->mailer->compose(['html' => '@app/mail/views/change-password'], ['usuario' => $this, 'url' => $url]);
        $message->setFrom(CacheMagic::getSystemData('systemEmail'));
        $message->setTo($this->email);
        $message->setSubject("Redefinição de Senha");
        $sent = $message->send();

        return $sent;
    }

}
