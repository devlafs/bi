<?php

namespace app\models;

use Yii;

class IndicadorCampo extends \yii\db\ActiveRecord {

    public $reload_form;

    public static function tableName() {
        return 'bpbi_indicador_campo';
    }

    public function rules() {
        return
                [
                    [['id_indicador', 'ordem', 'nome', 'campo'], 'required'],
                    [['id_indicador', 'ordem', 'casas_decimais'], 'integer'],
                    [['link', 'descricao', 'separador_decimal', 'separador_milhar', 'variavel_formula'], 'string'],
                    [['created_at', 'updated_at', 'id_importacao', 'tipo_lista', 'is_ativo', 'is_excluido', 'created_by', 'updated_by', 'agrupar_valor', 'reload_form'], 'safe'],
                    [['nome', 'tipo', 'prefixo', 'sufixo', 'formato', 'separador_decimal', 'separador_milhar', 'variavel_formula'], 'string', 'max' => 255],
                    [['campo'], 'string', 'max' => 1024],
                    [['id_indicador'], 'exist', 'skipOnError' => true, 'targetClass' => Indicador::className(), 'targetAttribute' => ['id_indicador' => 'id']],
        ];
    }

    public function attributeLabels() {
        return
                [
                    'id_indicador' => Yii::t('app', 'geral.indicador'),
                    'ordem' => Yii::t('app', 'indicador_campo.ordem'),
                    'campo' => Yii::t('app', 'geral.campo'),
                    'nome' => Yii::t('app', 'geral.nome'),
                    'tipo' => Yii::t('app', 'indicador_campo.tipo'),
                    'link' => Yii::t('app', 'indicador_campo.link'),
                    'prefixo' => Yii::t('app', 'indicador_campo.prefixo'),
                    'sufixo' => Yii::t('app', 'indicador_campo.sufixo'),
                    'formato' => Yii::t('app', 'indicador_campo.formato'),
                    'casas_decimais' => Yii::t('app', 'indicador_campo.casas_decimais'),
                    'separador_decimal' => Yii::t('app', 'indicador_campo.separador_decimal'),
                    'separador_milhar' => Yii::t('app', 'indicador_campo.separador_milhar'),
                    'variavel_formula' => Yii::t('app', 'indicador_campo.variavel_formula'),
                    'agrupar_valor' => Yii::t('app', 'indicador_campo.agrupar_valor'),
                    'descricao' => Yii::t('app', 'geral.descricao'),
                    'tipo_lista' => Yii::t('app', 'indicador_campo.tipo_lista'),
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

    public function getIndicador() {
        return $this->hasOne(Indicador::className(), ['id' => 'id_indicador']);
    }

    public function getItems() {
        return $this->hasMany(ConsultaItem::className(), ['id_campo' => 'id']);
    }

    public function getIcon() {
        $icon = '<i class="bp-string pr-1"></i>';

        switch ($this->tipo) {
            case 'texto':
                $icon = '<i class="bp-string pr-1"></i>';
                break;
            case 'formulatexto':
            case 'formulavalor':
                $icon = '<i class="bp-formula pr-1"></i>';
                break;
            case 'data':
                $icon = '<i class="bp-time pr-1"></i>';
                break;
            case 'valor':
                $icon = '<i class="bp-number pr-1"></i>';
                break;
        }

        return $icon;
    }

}
