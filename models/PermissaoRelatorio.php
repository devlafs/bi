<?php

namespace app\models;

use Yii;

class PermissaoRelatorio extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'bpbi_permissao_relatorio';
    }

    public function rules()
    {
        return [
            [['nome', 'gerenciador', 'constante'], 'required'],
            [['descricao', 'constante'], 'string'],
            [['created_by', 'updated_by'], 'integer'],
            [['is_ativo', 'is_excluido', 'created_at', 'updated_at'], 'safe'],
            [['nome', 'gerenciador'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels() {
        return [
            'nome' => Yii::t('app', 'geral.nome'),
            'descricao' => Yii::t('app', 'geral.descricao'),
            'gerenciador' => Yii::t('app', 'permissao_painel.gerenciador'),
            'constante' => Yii::t('app', 'permissao_painel.constante'),
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

    public function getPermissoes() {
        return $this->hasMany(RelatorioPermissao::className(), ['id_permissao' => 'id']);
    }
}
