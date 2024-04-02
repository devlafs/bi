<?php

namespace app\models;

use Yii;

class UltimaTelaAcesso extends \yii\db\ActiveRecord {

    CONST VIEW_CONSULTA = 1;
    CONST VIEW_PAINEL = 2;
    CONST VIEW_RELATORIO = 3;

    public static function tableName() {
        return 'bpbi_ultima_tela_acesso';
    }

    public function rules() {
        return [
            [['id_usuario', 'view', 'index'], 'required'],
            [['id_usuario', 'id_consulta', 'id_painel', 'id_relatorio_data', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at', 'is_ativo', 'is_excluido'], 'safe'],
            [['id_consulta'], 'exist', 'skipOnError' => true, 'targetClass' => Consulta::className(), 'targetAttribute' => ['id_consulta' => 'id']],
            [['id_painel'], 'exist', 'skipOnError' => true, 'targetClass' => Painel::className(), 'targetAttribute' => ['id_painel' => 'id']],
            [['id_relatorio_data'], 'exist', 'skipOnError' => true, 'targetClass' => RelatorioData::className(), 'targetAttribute' => ['id_relatorio_data' => 'id']],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => AdminUsuario::className(), 'targetAttribute' => ['id_usuario' => 'id']],
        ];
    }

    public function attributeLabels() {
        return
                [
                    'id_usuario' => Yii::t('app', 'geral.usuario'),
                    'id_consulta' => Yii::t('app', 'geral.consulta'),
                    'id_painel' => Yii::t('app', 'geral.painel'),
                    'id_relatorio_data' => Yii::t('app', 'geral.relatorio'),
                    'view' => Yii::t('app', 'ultima_tela_acesso.view'),
                    'index' => Yii::t('app', 'ultima_tela_acesso.index'),
                    'token' => Yii::t('app', 'ultima_tela_acesso.token'),
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

    public function getPainel() {
        return $this->hasOne(Painel::className(), ['id' => 'id_painel']);
    }

    public function getRelatorio() {
        return $this->hasOne(RelatorioData::className(), ['id' => 'id_relatorio_data']);
    }

    public function getUsuario() {
        return $this->hasOne(AdminUsuario::className(), ['id' => 'id_usuario']);
    }

}
