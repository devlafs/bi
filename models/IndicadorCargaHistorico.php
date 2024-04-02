<?php

namespace app\models;

use Yii;

class IndicadorCargaHistorico extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'bpbi_indicador_carga_historico';
    }

    public function rules()
    {
        return [
            [['id_indicador', 'tipo_carga'], 'required'],
            [['id_indicador', 'total'], 'integer'],
            [['started_at', 'finished_at', 'success', 'message'], 'safe'],
            [['tipo_carga'], 'string', 'max' => 255],
            [['id_indicador'], 'exist', 'skipOnError' => true, 'targetClass' => Indicador::className(), 'targetAttribute' => ['id_indicador' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_indicador' => Yii::t('app', 'geral.indicador'),
            'tipo_carga' => Yii::t('app', 'indicador_carga_historico.tipo_carga'),
            'total' => Yii::t('app', 'indicador_carga_historico.total'),
            'success' => Yii::t('app', 'indicador_carga_historico.success'),
            'message' => Yii::t('app', 'indicador_carga_historico.message'),
            'started_at' => Yii::t('app', 'indicador_carga_historico.started_at'),
            'finished_at' => Yii::t('app', 'indicador_carga_historico.finished_at'),
        ];
    }
    
    public function getIndicador()
    {
        return $this->hasOne(Indicador::className(), ['id' => 'id_indicador']);
    }
}
