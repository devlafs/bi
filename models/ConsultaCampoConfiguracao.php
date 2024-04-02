<?php

namespace app\models;

use Yii;

class ConsultaCampoConfiguracao extends \yii\db\ActiveRecord {

    public $label_tipo;
    public $label_view;

    public static function tableName() {
        return 'bpbi_consulta_campo_configuracao';
    }

    public function rules() {
        return
                [
                    [['id_consulta', 'id_campo', 'tipo', 'view'], 'required'],
                    [['id_consulta', 'id_campo'], 'integer'],
                    [['label_tipo', 'label_view', 'data', 'data_serie', 'data_timeline', 'created_at', 'updated_at',
                    'is_ativo', 'is_excluido', 'created_by', 'updated_by', 'is_serie'], 'safe'],
                    [['tipo', 'view'], 'string', 'max' => 255],
                    [['id_consulta'], 'exist', 'skipOnError' => true, 'targetClass' => Consulta::className(), 'targetAttribute' => ['id_consulta' => 'id']],
                    [['id_campo'], 'exist', 'skipOnError' => true, 'targetClass' => IndicadorCampo::className(), 'targetAttribute' => ['id_campo' => 'id']],
        ];
    }

    public function attributeLabels() {
        return
                [
                    'id_consulta' => Yii::t('app', 'geral.consulta'),
                    'id_campo' => Yii::t('app', 'geral.campo'),
                    'label_tipo' => Yii::t('app', 'geral.tipo'),
                    'tipo' => Yii::t('app', 'geral.tipo'),
                    'label_view' => Yii::t('app', 'consulta_campo_configuracao.label_view'),
                    'view' => Yii::t('app', 'consulta_campo_configuracao.view'),
                    'data' => Yii::t('app', 'consulta_campo_configuracao.data'),
                    'is_serie' => Yii::t('app', 'geral.serie'),
                    'data_serie' => Yii::t('app', 'geral.serie'),
                    'data_timeline' => Yii::t('app', 'consulta_campo_configuracao.data_timeline'),
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

    public function getConsulta() {
        return $this->hasOne(Consulta::className(), ['id' => 'id_consulta']);
    }

    public function getCampo() {
        return $this->hasOne(IndicadorCampo::className(), ['id' => 'id_campo']);
    }

}
