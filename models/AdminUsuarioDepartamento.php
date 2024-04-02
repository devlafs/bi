<?php

namespace app\models;

use Yii;

class AdminUsuarioDepartamento extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'admin_usuario_departamento';
    }

    public static function getDb()
    {
        return Yii::$app->get('userDb');
    }

    public function rules()
    {
        return [
            [['departamento_id', 'usuario_id'], 'integer'],
            [['departamento_id'], 'exist', 'skipOnError' => true, 'targetClass' => AdminConfiguracoes::className(), 'targetAttribute' => ['departamento_id' => 'id']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => AdminUsuario::className(), 'targetAttribute' => ['usuario_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return 
        [
            'departamento_id' => Yii::t('app', 'geral.departamento'),
            'usuario_id' => Yii::t('app', 'geral.usuario')
        ];
    }

    public function getDepartamento()
    {
        return $this->hasOne(AdminConfiguracoes::className(), ['id' => 'departamento_id']);
    }

    public function getUsuario()
    {
        return $this->hasOne(AdminUsuario::className(), ['id' => 'usuario_id']);
    }
}
