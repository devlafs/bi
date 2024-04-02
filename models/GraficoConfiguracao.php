<?php

namespace app\models;

use Yii;

class GraficoConfiguracao extends \yii\db\ActiveRecord {

    public $label_tipo;
    public $label_view;
    public $label_serie;

    public static function tableName() {
        return 'bpbi_grafico_configuracao';
    }

    public function rules() {
        return [
            [['view', 'tipo'], 'required'],
            [['label_tipo', 'label_view', 'label_serie', 'data', 'data_serie', 'data_timeline', 'created_at', 'updated_at', 'is_serie', 'is_ativo', 'is_excluido', 'created_by', 'updated_by'], 'safe'],
            [['view', 'tipo'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels() {
        return
                [
                    'view' => Yii::t('app', 'grafico_configuracao.view'),
                    'label_view' => Yii::t('app', 'grafico_configuracao.label_view'),
                    'tipo' => Yii::t('app', 'geral.tipo'),
                    'label_tipo' => Yii::t('app', 'geral.tipo'),
                    'data' => Yii::t('app', 'grafico_configuracao.data'),
                    'data_serie' => Yii::t('app', 'grafico_configuracao.data_serie'),
                    'data_timeline' => Yii::t('app', 'grafico_configuracao.data_timeline'),
                    'is_serie' => Yii::t('app', 'geral.serie'),
                    'label_serie' => Yii::t('app', 'geral.serie'),
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

}
