<?php

namespace app\models;

use Yii;

class AjudaCategoria extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'bpbi_ajuda_categoria';
    }

    public function rules() {
        return [
            [['nome', 'ordem'], 'required'],
            [['ordem'], 'integer'],
        ];
    }

    public function attributeLabels() {
        return
        [
            'nome', Yii::t('app','geral.nome'),
            'ordem', Yii::t('app','geral.ordem')
        ];
    }

    public function getAjudas() {
        return $this->hasMany(Ajuda::className(), ['id_categoria' => 'id'])->
            orderBy(['ordem' => SORT_ASC]);
    }
}
