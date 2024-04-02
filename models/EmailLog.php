<?php

namespace app\models;

use Yii;

class EmailLog extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'bpbi_email_log';
    }

    public function rules() {
        return [
            [['id_email', 'destinatario', 'log', 'status'], 'required'],
            [['id_email', 'status'], 'integer'],
            [['log'], 'string'],
            [['created_at', 'updated_at', 'is_ativo', 'is_excluido', 'created_by', 'updated_by'], 'safe'],
            [['destinatario'], 'string', 'max' => 255],
            [['id_email'], 'exist', 'skipOnError' => true, 'targetClass' => Email::className(), 'targetAttribute' => ['id_email' => 'id']],
        ];
    }

    public function attributeLabels() {
        return
                [
                    'id_email' => Yii::t('app', 'geral.email'),
                    'destinatario' => Yii::t('app', 'email_log.destinatario'),
                    'log' => Yii::t('app', 'email_log.log'),
                    'status' => Yii::t('app', 'geral.status'),
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

    public function getEmail() {
        return $this->hasOne(Email::className(), ['id' => 'id_email']);
    }

}
