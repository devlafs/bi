<?php

namespace app\models;

use Yii;

class Sistema extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'bpbi_sistema';
    }

    public function rules() {
        return [
            [['campo', 'valor'], 'required'],
            [['ordem', 'visible'], 'safe'],
            [['campo', 'valor', 'nome'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels() {
        return [
            'campo' => Yii::t('app', 'geral.campo'),
            'valor' => Yii::t('app', 'sistema.valor'),
            'nome' => Yii::t('app', 'geral.nome'),
            'ordem' => Yii::t('app', 'geral.ordem'),
            'visible' => Yii::t('app', 'sistema.visible'),
        ];
    }

}
