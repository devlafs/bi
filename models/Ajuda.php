<?php

namespace app\models;

use Yii;

class Ajuda extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'bpbi_ajuda';
    }

    public function rules() {
        return [
            [['id_categoria', 'titulo', 'ordem', 'texto'], 'required'],
            [['id_categoria', 'ordem'], 'integer'],
            [['id_categoria'], 'exist', 'skipOnError' => true, 'targetClass' => AjudaCategoria::className(), 'targetAttribute' => ['id_categoria' => 'id']],
        ];
    }

    public function attributeLabels() {
        return
        [
            'id_categoria' => Yii::app('app', 'ajuda.categoria'),
            'titulo' => Yii::app('app', 'ajuda.titulo'),
            'ordem' => Yii::app('app', 'geral.ordem'),
            'texto' => Yii::app('app', 'ajuda.texto')
        ];
    }

    public function getCategoria() {
        return $this->hasOne(AjudaCategoria::className(), ['id' => 'id_categoria']);
    }
}
