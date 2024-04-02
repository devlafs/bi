<?php

namespace app\models;

use Yii;
use app\lists\FrequenciaList;
use yii\validators\EmailValidator;

class Email extends \yii\db\ActiveRecord {

    public $reload_form;
    public $log;

    CONST VIEW_CONSULTA = 1;
    CONST VIEW_PAINEL = 2;

    public static $views = [
        self::VIEW_CONSULTA => 'Consulta',
        self::VIEW_PAINEL => 'Painel'
    ];

    CONST TIPO_PERFIL = 1;
    CONST TIPO_USUARIO = 2;
    CONST TIPO_EMAIL = 3;
    CONST TIPO_DEPARTAMENTO = 4;

    public static $tipos = [
        self::TIPO_PERFIL => 'Perfil',
        self::TIPO_DEPARTAMENTO => 'Departamento',
        self::TIPO_USUARIO => 'Usuário',
        self::TIPO_EMAIL => 'Email'
    ];

    CONST FREQUENCIA_DIARIA = 1;
    CONST FREQUENCIA_SEMANAL = 2;
    CONST FREQUENCIA_MENSAL = 3;

    public static $frequencias = [
        self::FREQUENCIA_DIARIA => 'Diária',
        self::FREQUENCIA_SEMANAL => 'Semanal',
        self::FREQUENCIA_MENSAL => 'Mensal'
    ];
    public $tipo_destinatario;

    public static function tableName() {
        return 'bpbi_email';
    }

    public function rules() {
        return [
            [['assunto', 'view', 'frequencia', 'tipo_destinatario', 'hora'], 'required'],
            [['tipo_destinatario'], 'validateTipo'],
            [['frequencia'], 'validateFrequencia'],
            [['view'], 'validateView'],
            [['email'], 'validateEmails'],
            [['id_usuario', 'id_perfil', 'id_departamento', 'id_consulta', 'id_painel', 'frequencia', 'hora', 'dia_semana', 'dia_mes'], 'integer'],
            [['log', 'tipo_destinatario', 'created_at', 'updated_at', 'sent_at',
            'send_pdf', 'send_weekends', 'is_ativo', 'is_excluido', 'created_by', 'updated_by', 'reload_form'], 'safe'],
            [['assunto', 'email', 'departamento'], 'string', 'max' => 255],
            [['id_consulta'], 'exist', 'skipOnError' => true, 'targetClass' => Consulta::className(), 'targetAttribute' => ['id_consulta' => 'id']],
            [['id_painel'], 'exist', 'skipOnError' => true, 'targetClass' => Painel::className(), 'targetAttribute' => ['id_painel' => 'id']],
            [['id_template'], 'exist', 'skipOnError' => true, 'targetClass' => TemplateEmail::className(), 'targetAttribute' => ['id_template' => 'id']],
        ];
    }

    public function attributeLabels() {
        return
                [
                    'view' => Yii::t('app', 'geral.tipo'),
                    'assunto' => Yii::t('app', 'email.assunto'),
                    'id_usuario' => Yii::t('app', 'geral.usuario'),
                    'id_perfil' => Yii::t('app', 'geral.perfil'),
                    'id_departamento' => Yii::t('app', 'geral.departamento'),
                    'departamento' => Yii::t('app', 'geral.departamento'),
                    'id_consulta' => Yii::t('app', 'geral.consulta'),
                    'id_painel' => Yii::t('app', 'geral.painel'),
                    'id_template' => Yii::t('app', 'email.id_template'),
                    'tipo_destinatario' => Yii::t('app', 'email.tipo_destinatario'),
                    'email' => Yii::t('app', 'geral.email'),
                    'frequencia' => Yii::t('app', 'email.frequencia'),
                    'hora' => Yii::t('app', 'email.hora'),
                    'dia_semana' => Yii::t('app', 'email.dia_semana'),
                    'dia_mes' => Yii::t('app', 'email.dia_mes'),
                    'log' => Yii::t('app', 'email.log'),
                    'send_pdf' => Yii::t('app', 'email.send_pdf'),
                    'send_weekends' => Yii::t('app', 'email.send_weekends'),
                    'is_ativo' => Yii::t('app', 'geral.is_ativo'),
                    'is_excluido' => Yii::t('app', 'geral.is_excluido'),
                    'created_at' => Yii::t('app', 'geral.created_at'),
                    'updated_at' => Yii::t('app', 'geral.updated_at'),
                    'sent_at' => Yii::t('app', 'email.sent_at'),
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

    public function validateEmails($attribute, $params, $validator) {
        $validator = new EmailValidator();

        $emails = is_array($this->email) ?: explode(';', $this->email);

        foreach ($emails as $email) {
            if (!empty(trim($email))) {
                $validator->validate($email) ?: $this->addError($attribute, \Yii::t('app', 'erro.tag_nao_encontrada', [
                    'email' => $email,
                ]));
            }
        }
    }

    public function validateView($attribute, $params, $validator) {
        switch ($this->view) {
            case self::VIEW_CONSULTA:

                if (!$this->id_consulta) {
                    $this->addError('id_consulta', Yii::t('core',Yii::t('app', 'geral.consulta').' cannot be blank'));
                }

                break;
            case self::VIEW_PAINEL:

                if (!$this->id_painel) {
                    $this->addError('id_painel', Yii::t('core',Yii::t('app', 'geral.painel').' cannot be blank'));
                }
        }
    }

    public function validateTipo($attribute, $params, $validator) {
        switch ($this->tipo_destinatario) {
            case self::TIPO_PERFIL:

                if (!$this->id_perfil) {
                    $this->addError('id_perfil', Yii::t('core',Yii::t('app', 'geral.perfil').' cannot be blank'));
                }

                break;

            case self::TIPO_USUARIO:

                if (!$this->id_usuario) {
                    $this->addError('id_usuario', Yii::t('core',Yii::t('app', 'geral.usuario').' cannot be blank'));
                }

                break;

            case self::TIPO_DEPARTAMENTO:

                $beeIntegration = Yii::$app->params['beeIntegration'];
                $field = ($beeIntegration) ? 'id_departamento' : 'departamento';

                if (!$this->{$field}) {
                    $this->addError($field, Yii::t('core',Yii::t('app', 'geral.departamento').' cannot be blank'));
                }

                break;

            case self::TIPO_EMAIL:

                if (!$this->email) {
                    $this->addError('email', Yii::t('core',Yii::t('app', 'geral.email').' cannot be blank'));
                }
        }
    }

    public function validateFrequencia($attribute, $params, $validator) {
        switch ($this->frequencia) {
            case self::FREQUENCIA_MENSAL:

                if (!$this->dia_mes) {
                    $this->addError('dia_mes', Yii::t('core',Yii::t('app', 'email.dia_mes').' cannot be blank'));
                }

                break;
            case self::FREQUENCIA_SEMANAL:

                if (!$this->dia_semana) {
                    $this->addError('dia_semana', Yii::t('core',Yii::t('app', 'email.dia_semana').' cannot be blank'));
                }
        }
    }

    public function getConsulta() {
        return $this->hasOne(Consulta::className(), ['id' => 'id_consulta']);
    }

    public function getPainel() {
        return $this->hasOne(Painel::className(), ['id' => 'id_painel']);
    }

    public function getTemplate() {
        return $this->hasOne(TemplateEmail::className(), ['id' => 'id_template']);
    }

    public function getLogs() {
        return $this->hasMany(EmailLog::className(), ['id_email' => 'id'])->orderBy('created_at DESC');
    }

    public function getLogsEnvio() {
        $str = "";

        foreach ($this->logs as $index => $log) {
            if ($index >= 20) {
                break;
            }

            $sent_at = Yii::$app->formatter->asDate($log->created_at, 'php:d/m/Y H:i');
            $class = ($log->status == 1) ? 'success' : 'danger';
            $text = "{$sent_at} - {$log->log}";
            $str .= "<span class='badge badge-{$class}'>{$text}</span> <br>";
        }

        return $str;
    }

    public function getDestinatario() {
        $destinatario = '';

        if ($this->id_perfil) {
            $destinatario = AdminPerfil::findOne($this->id_perfil)->nome;
        } elseif ($this->id_usuario) {
            $destinatario = AdminUsuario::findOne($this->id_usuario)->nomeResumo;
        } elseif ($this->departamento || $this->id_departamento) {
            $beeIntegration = Yii::$app->params['beeIntegration'];
            $destinatario = ($beeIntegration) ? AdminConfiguracoes::findOne($this->id_departamento)->nome : $this->departamento;
        } else {
            $emails = is_array($this->email) ?: explode(';', $this->email);

            foreach ($emails as $email) {
                if (!empty(trim($email))) {
                    $destinatario .= "{$email} <br>";
                }
            }
        }

        return $destinatario;
    }

    public function getTipoDestinatario() {
        $tipo_destinatario = self::TIPO_EMAIL;

        if ($this->id_perfil) {
            $tipo_destinatario = self::TIPO_PERFIL;
        } elseif ($this->id_usuario) {
            $tipo_destinatario = self::TIPO_USUARIO;
        } elseif ($this->departamento || $this->id_departamento) {
            $tipo_destinatario = self::TIPO_DEPARTAMENTO;
        }

        return $tipo_destinatario;
    }

    public function getDiaEnvio() {
        $dia = 'Todo dia';

        if ($this->frequencia == self::FREQUENCIA_MENSAL) {
            $dia = $this->dia_mes;
        } elseif ($this->frequencia == self::FREQUENCIA_SEMANAL) {
            $dia = FrequenciaList::getNomeSemana($this->dia_semana);
        }

        return $dia;
    }

    public function beforeSave($insert) {
        if ($this->tipo_destinatario) {
            $tipo = (int) $this->tipo_destinatario;

            switch ($tipo) {
                case self::TIPO_PERFIL:
                    $this->id_usuario = null;
                    $this->email = null;
                    $this->departamento = null;
                    $this->id_departamento = null;
                    break;
                case self::TIPO_USUARIO:
                    $this->id_perfil = null;
                    $this->email = null;
                    $this->departamento = null;
                    $this->id_departamento = null;
                    break;
                case self::TIPO_DEPARTAMENTO:
                    $this->id_perfil = null;
                    $this->id_usuario = null;
                    $this->email = null;
                    break;
                case self::TIPO_EMAIL:
                    $this->id_perfil = null;
                    $this->id_usuario = null;
                    $this->departamento = null;
                    $this->id_departamento = null;
            }
        }

        if ($this->frequencia) {
            $frequencia = (int) $this->frequencia;

            switch ($frequencia) {
                case self::FREQUENCIA_DIARIA:
                    $this->dia_semana = null;
                    $this->dia_mes = null;
                    break;
                case self::FREQUENCIA_SEMANAL:
                    $this->dia_mes = null;
                    break;
                case self::FREQUENCIA_MENSAL:
                    $this->dia_semana = null;
            }
        }

        return parent::beforeSave($insert);
    }

}
