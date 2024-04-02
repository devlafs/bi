<?php

namespace app\models;

use Yii;

class Pallete extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'bpbi_pallete';
    }

    public function rules() {
        return [
            [['nome', 'file'], 'required'],
            [['created_at', 'color1', 'color2', 'is_ativo', 'is_excluido', 'created_by'], 'safe'],
            [['nome', 'file'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels() {
        return [
            'nome' => Yii::t('app', 'geral.nome'),
            'file' => Yii::t('app', 'pallete.file'),
            'is_ativo' => Yii::t('app', 'geral.is_ativo'),
            'is_excluido' => Yii::t('app', 'geral.is_excluido'),
            'created_at' => Yii::t('app', 'geral.created_at'),
            'updated_at' => Yii::t('app', 'geral.updated_at'),
            'created_by' => Yii::t('app', 'geral.created_by'),
            'updated_by' => Yii::t('app', 'geral.updated_by')
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
