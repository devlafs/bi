<?php

namespace app\models;

use Yii;

class UsuarioToken extends \yii\db\ActiveRecord {

    public $updated_at;

    public static function tableName() {
        return 'bpbi_usuario_token';
    }

    public function rules() {
        return [
            [['id_usuario', 'token'], 'required'],
            [['id_usuario'], 'integer'],
            [['created_at', 'is_used', 'is_ativo', 'is_excluido'], 'safe'],
            [['token'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels() {
        return
                [
                    'id_usuario' => Yii::t('app', 'geral.usuario'),
                    'token' => Yii::t('app', 'usuario_token.token'),
                    'is_used' => Yii::t('app', 'usuario_token.is_used'),
                    'is_ativo' => Yii::t('app', 'geral.is_ativo'),
                    'is_excluido' => Yii::t('app', 'geral.is_excluido'),
                    'created_at' => Yii::t('app', 'geral.created_at')
        ];
    }

    public function behaviors() {
        $behaviors = [
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
