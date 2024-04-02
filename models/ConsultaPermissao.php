<?php

namespace app\models;

use Yii;

class ConsultaPermissao extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'bpbi_consulta_permissao';
    }

    public function rules() {
        return [
            [['id_consulta', 'id_permissao', 'id_perfil'], 'required'],
            [['id_consulta', 'id_permissao', 'id_perfil'], 'integer'],
            [['created_at', 'updated_at', 'is_ativo', 'is_excluido', 'created_by', 'updated_by'], 'safe'],
            [['id_consulta'], 'exist', 'skipOnError' => true, 'targetClass' => Consulta::className(), 'targetAttribute' => ['id_consulta' => 'id']],
            [['id_permissao'], 'exist', 'skipOnError' => true, 'targetClass' => PermissaoConsulta::className(), 'targetAttribute' => ['id_permissao' => 'id']],
        ];
    }

    public function attributeLabels() {
        return [
            'id_consulta' => Yii::t('app', 'geral.consulta'),
            'id_permissao' => Yii::t('app', 'consulta_permissao.id_permissao'),
            'id_perfil' => Yii::t('app', 'geral.perfil'),
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

    public function getPermissao() {
        return $this->hasOne(PermissaoConsulta::className(), ['id' => 'id_permissao']);
    }

    public static function find() {
        return new queries\ConsultaPermissaoQuery(get_called_class());
    }

}
