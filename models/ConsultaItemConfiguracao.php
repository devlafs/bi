<?php

namespace app\models;

use Yii;

class ConsultaItemConfiguracao extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'bpbi_consulta_item_configuracao';
    }

    public function rules() {
        return [
            [['ordem', 'id_consulta', 'id_item', 'id_campo'], 'required'],
            [['ordem', 'id_consulta', 'id_item', 'id_campo'], 'integer'],
            [['created_at', 'updated_at', 'is_ativo', 'is_excluido', 'created_by', 'updated_by'], 'safe'],
            [['id_campo'], 'exist', 'skipOnError' => true, 'targetClass' => IndicadorCampo::className(), 'targetAttribute' => ['id_campo' => 'id']],
            [['id_consulta'], 'exist', 'skipOnError' => true, 'targetClass' => Consulta::className(), 'targetAttribute' => ['id_consulta' => 'id']],
            [['id_item'], 'exist', 'skipOnError' => true, 'targetClass' => IndicadorCampo::className(), 'targetAttribute' => ['id_item' => 'id']],
        ];
    }

    public function attributeLabels() {
        return
                [
                    'ordem' => Yii::t('app', 'geral.ordem'),
                    'id_consulta' => Yii::t('app', 'geral.consulta'),
                    'id_item' => Yii::t('app', 'consulta_item_configuracao.id_item'),
                    'id_campo' => Yii::t('app', 'geral.campo'),
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

    public function getCampo() {
        return $this->hasOne(IndicadorCampo::className(), ['id' => 'id_campo']);
    }

    public function getConsulta() {
        return $this->hasOne(Consulta::className(), ['id' => 'id_consulta']);
    }

    public function getItem() {
        return $this->hasOne(IndicadorCampo::className(), ['id' => 'id_item']);
    }

}
