<?php

namespace app\models;

use Yii;

class ConsultaItem extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'bpbi_consulta_item';
    }

    public function rules() {
        return [
            [['id_consulta', 'id_campo', 'ordem', 'ordenacao'], 'required'],
            [['id_consulta', 'id_campo', 'ordem', 'ordenacao', 'tipo_numero', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at', 'is_ativo', 'is_excluido'], 'safe'],
            [['parametro', 'tipo_grafico'], 'string', 'max' => 255],
            [['id_campo'], 'exist', 'skipOnError' => true, 'targetClass' => IndicadorCampo::className(), 'targetAttribute' => ['id_campo' => 'id']],
            [['id_consulta'], 'exist', 'skipOnError' => true, 'targetClass' => Consulta::className(), 'targetAttribute' => ['id_consulta' => 'id']],
        ];
    }

    public function attributeLabels() {
        return
                [
                    'id_consulta' => Yii::t('app', 'geral.consulta'),
                    'id_campo' => Yii::t('app', 'geral.campo'),
                    'parametro' => Yii::t('app', 'consulta_item.parametro'),
                    'ordem' => Yii::t('app', 'geral.ordem'),
                    'tipo_numero' => Yii::t('app', 'consulta_item.tipo_numero'),
                    'ordenacao' => Yii::t('app', 'consulta_item.ordenacao'),
                    'tipo_grafico' => Yii::t('app', 'consulta_item.tipo_grafico'),
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

}
