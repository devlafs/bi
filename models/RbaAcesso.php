<?php

namespace app\models;

use Yii;

class RbaAcesso extends \yii\db\ActiveRecord {

    public static function getDb() {
        return Yii::$app->get('userDb');
    }

    public static function tableName() {
        return 'rba_acesso';
    }

    public function rules() {
        return [
            [['admusua_id', 'dthr_login', 'desc_ip', 'desc_useragent'], 'required'],
            [['admusua_id'], 'integer'],
            [['dthr_login', 'bpbi'], 'safe'],
            [['desc_ip'], 'string', 'max' => 15],
            [['desc_data'], 'string', 'max' => 20],
            [['desc_useragent'], 'string', 'max' => 150],
            [['admusua_id'], 'exist', 'skipOnError' => true, 'targetClass' => AdminUsuario::className(), 'targetAttribute' => ['admusua_id' => 'id']],
        ];
    }

    public function attributeLabels() {
        return
                [
                    'admusua_id' => Yii::t('app', 'geral.usuario'),
                    'dthr_login' => Yii::t('app', 'rba_acesso.dthr_login'),
                    'desc_ip' => Yii::t('app', 'rba_acesso.desc_ip'),
                    'desc_useragent' => Yii::t('app', 'rba_acesso.desc_useragent'),
        ];
    }

    public function getUsuario() {
        return $this->hasOne(AdminUsuario::className(), ['id' => 'admusua_id']);
    }

}
