<?php

namespace app\models;

use Yii;

class RelatorioDataItem extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'bpbi_relatorio_data_item';
    }

    public function rules()
    {
        return [
            [['id_relatorio_data', 'id_campo', 'ordem'], 'required'],
            [['id_relatorio_data', 'id_campo', 'ordem', 'created_by', 'updated_by'], 'integer'],
            [['is_ativo', 'is_excluido', 'created_at', 'updated_at'], 'safe'],
            [['parametro'], 'string', 'max' => 255],
            [['id_campo'], 'exist', 'skipOnError' => true, 'targetClass' => RelatorioCampo::className(), 'targetAttribute' => ['id_campo' => 'id']],
            [['id_relatorio_data'], 'exist', 'skipOnError' => true, 'targetClass' => RelatorioData::className(), 'targetAttribute' => ['id_relatorio_data' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_relatorio_data' => 'Relatório',
            'id_campo' => 'Campo',
            'ordem' => 'Ordem',
            'parametro' => 'Parâmetro',
            'is_ativo' => Yii::t('app', 'geral.is_ativo'),
            'is_excluido' => Yii::t('app', 'geral.is_excluido'),
            'created_at' => Yii::t('app', 'geral.created_at'),
            'updated_at' => Yii::t('app', 'geral.updated_at'),
            'executed_at' => Yii::t('app', 'indicador.executed_at'),
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

    public function getCampo()
    {
        return $this->hasOne(RelatorioCampo::className(), ['id' => 'id_campo']);
    }

    public function getRelatorioData()
    {
        return $this->hasOne(RelatorioData::className(), ['id' => 'id_relatorio_data']);
    }
}
