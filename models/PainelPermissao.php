<?php

namespace app\models;

use Yii;

class PainelPermissao extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'bpbi_painel_permissao';
    }

    public function rules() {
        return [
            [['id_painel', 'id_permissao', 'id_perfil'], 'required'],
            [['id_painel', 'id_permissao', 'id_perfil'], 'integer'],
            [['created_at', 'updated_at', 'is_ativo', 'is_excluido', 'created_by', 'updated_by'], 'safe'],
            [['id_painel'], 'exist', 'skipOnError' => true, 'targetClass' => Painel::className(), 'targetAttribute' => ['id_painel' => 'id']],
            [['id_permissao'], 'exist', 'skipOnError' => true, 'targetClass' => PermissaoPainel::className(), 'targetAttribute' => ['id_permissao' => 'id']],
        ];
    }

    public function attributeLabels() {
        return
                [
                    'id_painel' => Yii::t('app', 'geral.painel'),
                    'id_permissao' => Yii::t('app', 'painel_permissao.id_permissao'),
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

    public function getPainel() {
        return $this->hasOne(Painel::className(), ['id' => 'id_painel']);
    }

    public function getPermissao() {
        return $this->hasOne(PermissaoPainel::className(), ['id' => 'id_permissao']);
    }

    public static function find() {
        return new queries\PainelPermissaoQuery(get_called_class());
    }

}
