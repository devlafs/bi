<?php

namespace app\models;

use Yii;

class AdminConfiguracoes extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'admin_configuracoes';
    }

    public static function getDb()
    {
        return Yii::$app->get('userDb');
    }

    public function rules()
    {
        return [
            [['nome', 'tipo'], 'required'],
            [['conexaoId'], 'integer'],
            [['query'], 'string'],
            [['nome'], 'string', 'max' => 200],
            [['tipo', 'codigo'], 'string', 'max' => 100],
        ];
    }

    public function attributeLabels()
    {
        return 
        [
            'nome' => Yii::t('app', 'geral.nome'),
            'tipo' => Yii::t('app', 'geral.tipo'),
            'codigo' => Yii::t('app', 'geral.codigo')
        ];
    }

    public function getUsuarioDepartamento()
    {
        return $this->hasMany(AdminUsuarioDepartamento::className(), ['departamento_id' => 'id']);
    }
}
