<?php

namespace app\models;

use Yii;

class RelatorioPermissao extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'bpbi_relatorio_permissao';
    }

    public function rules()
    {
        return [
            [['id_relatorio_data', 'id_permissao', 'id_perfil'], 'required'],
            [['id_relatorio_data', 'id_permissao', 'id_perfil', 'is_ativo', 'is_excluido', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['id_permissao'], 'exist', 'skipOnError' => true, 'targetClass' => PermissaoRelatorio::className(), 'targetAttribute' => ['id_permissao' => 'id']],
            [['id_relatorio_data'], 'exist', 'skipOnError' => true, 'targetClass' => RelatorioData::className(), 'targetAttribute' => ['id_relatorio_data' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_relatorio' => Yii::t('app', 'geral.relatorio'),
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

    public function getPermissao()
    {
        return $this->hasOne(PermissaoRelatorio::className(), ['id' => 'id_permissao']);
    }

    public function getRelatorioData()
    {
        return $this->hasOne(RelatorioData::className(), ['id' => 'id_relatorio_data']);
    }
}
