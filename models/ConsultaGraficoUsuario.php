<?php

namespace app\models;

use Yii;

class ConsultaGraficoUsuario extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'bpbi_consulta_grafico_usuario';
    }

    public function rules() {
        return [
            [['id_usuario', 'id_consulta'], 'required'],
            [['id_usuario', 'id_consulta'], 'integer'],
            [['campo'], 'string', 'max' => 255],
            [['tipo_grafico', 'created_at', 'updated_at', 'is_ativo', 'is_excluido', 'created_by', 'updated_by'], 'safe'],
            [['id_consulta'], 'exist', 'skipOnError' => true, 'targetClass' => Consulta::className(), 'targetAttribute' => ['id_consulta' => 'id']],
        ];
    }

    public function attributeLabels() {
        return
                [
                    'id_usuario' => Yii::t('app', 'geral.usuario'),
                    'id_consulta' => Yii::t('app', 'geral.consulta'),
                    'campo' => Yii::t('app', 'geral.campo'),
                    'tipo_grafico' => Yii::t('app', 'consulta_grafico_usuario.tipo_grafico'),
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

}
