<?php

namespace app\models;

use Yii;

class UrlShare extends \yii\db\ActiveRecord {

    CONST VIEW_CONSULTA = 1;
    CONST VIEW_PAINEL = 2;
    CONST VIEW_RELATORIO = 3;

    public $updated_at;
    public $updated_by;

    public static function tableName() {
        return 'bpbi_url_share';
    }

    public function rules() {
        return [
            [['id_usuario', 'view'], 'required'],
            [['id_consulta', 'id_painel', 'id_relatorio_data', 'id_usuario'], 'integer'],
            [['created_at', 'is_ativo', 'is_excluido', 'created_by'], 'safe'],
            [['token', 'type', 'email', 'password'], 'string', 'max' => 255],
            [['id_consulta'], 'exist', 'skipOnError' => true, 'targetClass' => Consulta::className(), 'targetAttribute' => ['id_consulta' => 'id']],
            [['id_painel'], 'exist', 'skipOnError' => true, 'targetClass' => Painel::className(), 'targetAttribute' => ['id_painel' => 'id']],
            [['id_relatorio_data'], 'exist', 'skipOnError' => true, 'targetClass' => RelatorioData::className(), 'targetAttribute' => ['id_relatorio_data' => 'id']],
        ];
    }

    public function attributeLabels() {
        return
                [
                    'id_consulta' => Yii::t('app', 'geral.consulta'),
                    'id_painel' => Yii::t('app', 'geral.painel'),
                    'id_relatorio_data' => Yii::t('app', 'geral.relatorio'),
                    'id_usuario' => Yii::t('app', 'geral.usuario'),
                    'token' => Yii::t('app', 'url_share.token'),
                    'password' => Yii::t('app', 'url_share.password'),
                    'type' => Yii::t('app', 'geral.tipo'),
                    'view' => Yii::t('app', 'url_share.view'),
                    'email' => Yii::t('app', 'geral.email'),
                    'is_ativo' => Yii::t('app', 'geral.is_ativo'),
                    'is_excluido' => Yii::t('app', 'geral.is_excluido'),
                    'created_at' => Yii::t('app', 'geral.created_at'),
                    'created_by' => Yii::t('app', 'geral.created_by'),
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

    public function getAcessos() {
        return $this->hasMany(UrlShareAccess::className(), ['id_url' => 'id']);
    }

}
