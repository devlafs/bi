<?php

namespace app\models;

use Yii;

class RelatorioCampo extends \yii\db\ActiveRecord
{
    CONST TIPO_TEXTO = 1;
    CONST TIPO_INTEIRO = 2;
    CONST TIPO_MONETARIO = 3;
    CONST TIPO_DATA = 4;
    CONST TIPO_DATAHORA = 5;
    CONST TIPO_TELEFONE = 6;
    CONST TIPO_EMAIL = 7;
    CONST TIPO_WHATSAPP = 8;
    CONST TIPO_LINK = 9;

    public static $tipos = [
        self::TIPO_TEXTO => 'Texto',
        self::TIPO_INTEIRO => 'Inteiro',
        self::TIPO_MONETARIO => 'Monetário',
        self::TIPO_DATA => 'Data',
        self::TIPO_DATAHORA => 'Data e Hora',
        self::TIPO_TELEFONE => 'Telefone',
        self::TIPO_EMAIL => 'E-mail',
        self::TIPO_WHATSAPP => 'Whatsapp',
        self::TIPO_LINK => 'Link',
    ];

    public static $tipo_texto = [
        self::TIPO_TEXTO,
        self::TIPO_DATA,
        self::TIPO_DATAHORA,
        self::TIPO_TELEFONE,
        self::TIPO_EMAIL,
        self::TIPO_WHATSAPP,
        self::TIPO_LINK,
    ];

    public static $tipo_inteiro = [
        self::TIPO_INTEIRO,
        self::TIPO_MONETARIO,
    ];

    public static function tableName()
    {
        return 'bpbi_relatorio_campo';
    }

    public function rules()
    {
        return [
            [['id_relatorio', 'ordem', 'nome', 'campo', 'tipo'], 'required'],
            [['id_relatorio', 'ordem', 'tipo', 'created_by', 'updated_by'], 'integer'],
            [['descricao'], 'string'],
            [['is_ativo', 'is_excluido', 'options', 'created_at', 'updated_at'], 'safe'],
            [['nome', 'campo', 'function'], 'string', 'max' => 255],
            [['id_relatorio'], 'exist', 'skipOnError' => true, 'targetClass' => Relatorio::className(), 'targetAttribute' => ['id_relatorio' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_relatorio' => 'Relatório',
            'ordem' => 'Ordem',
            'nome' => 'Nome',
            'campo' => 'Campo',
            'tipo' => 'Tipo',
            'descricao' => 'Descrição',
            'options' => 'Opções',
            'function' => 'Função',
            'is_ativo' => Yii::t('app', 'geral.is_ativo'),
            'is_excluido' => Yii::t('app', 'geral.is_excluido'),
            'created_at' => Yii::t('app', 'geral.created_at'),
            'updated_at' => Yii::t('app', 'geral.updated_at'),
            'executed_at' => Yii::t('app', 'indicador.executed_at'),
            'created_by' => Yii::t('app', 'geral.created_by'),
            'updated_by' => Yii::t('app', 'geral.updated_by')
        ];
    }

    public function behaviors() {
        $behaviors = [
            'json' =>
                [
                    'class' => 'app\components\JsonBehavior',
                    'attributes' =>
                        [
                            'options'
                        ],
                ],
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

    public function getRelatorio()
    {
        return $this->hasOne(Relatorio::className(), ['id' => 'id_relatorio']);
    }

    public function getTipoString()
    {
        return (isset(self::$tipos[$this->tipo])) ? self::$tipos[$this->tipo] : $this->tipo;
    }

    public function getIcon() {
        $icon = '<i class="bp-string pr-1"></i>';

        switch ($this->tipo) {
            case self::TIPO_TEXTO:
            case self::TIPO_TELEFONE:
            case self::TIPO_EMAIL:
            case self::TIPO_WHATSAPP:
            case self::TIPO_LINK:
                $icon = '<i class="bp-string pr-1"></i>';
                break;
            case self::TIPO_DATA:
            case self::TIPO_DATAHORA:
                $icon = '<i class="bp-time pr-1"></i>';
                break;
            case self::TIPO_INTEIRO:
            case self::TIPO_MONETARIO:
                $icon = '<i class="bp-number pr-1"></i>';
                break;
        }

        return $icon;
    }
}
