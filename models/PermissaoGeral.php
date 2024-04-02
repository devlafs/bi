<?php

namespace app\models;

use Yii;

class PermissaoGeral extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'bpbi_permissao_geral';
    }

    public function rules() {
        return [
            [['nome', 'constante', 'gerenciador'], 'required'],
            [['descricao', 'constante'], 'string'],
            [['column', 'created_at', 'updated_at', 'is_ativo', 'is_excluido', 'created_by', 'updated_by'], 'safe'],
            [['nome', 'gerenciador'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels() {
        return
                [
                    'nome' => Yii::t('app', 'geral.nome'),
                    'descricao' => Yii::t('app', 'geral.descricao'),
                    'gerenciador' => Yii::t('app', 'permissao_geral.gerenciador'),
                    'constante' => Yii::t('app', 'permissao_geral.constante'),
                    'column' => Yii::t('app', 'permissao_geral.column'),
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

    public function getPerfilPermissaos() {
        return $this->hasMany(PerfilPermissao::className(), ['id_permissao' => 'id']);
    }

    public static function find() {
        return new queries\PermissaoGeralQuery(get_called_class());
    }

}
