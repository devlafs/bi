<?php

namespace app\models;

use Yii;

class Mapa extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'bpbi_mapa';
    }

    public function rules()
    {
        return [
            [['identificador', 'nome', 'latitude', 'longitude', 'zoom'], 'required'],
            [['descricao'], 'string'],
            [['latitude', 'longitude'], 'number'],
            [['zoom'], 'integer'],
            [['file', 'created_at', 'updated_at', 'is_ativo', 'is_excluido', 'created_by', 'updated_by'], 'safe'],
            [['file'], 'file', 'extensions' => 'json'],
            [['identificador', 'nome', 'corfundo_ativo', 'corfundo_inativo', 'corborda', 'file'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return 
        [
            'identificador' => 'Grupo',
            'nome' => Yii::t('app', 'geral.nome'),
            'descricao' => Yii::t('app', 'geral.descricao'),
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'zoom' => 'Zoom',
            'corfundo_ativo' => 'Cor do Fundo (Selecionado)',
            'corfundo_inativo' => 'Cor do Fundo (Sem dados)',
            'corborda' => 'Cor da Borda',
            'file' => 'GeoJson',
            'is_ativo' => Yii::t('app', 'geral.is_ativo'),
            'is_excluido' => Yii::t('app', 'geral.is_excluido'),
            'created_at' => Yii::t('app', 'geral.created_at'),
            'updated_at' => Yii::t('app', 'geral.updated_at'),
            'created_by' => Yii::t('app', 'geral.created_by'),
            'updated_by' => Yii::t('app', 'geral.updated_by'),
        ];
    }

    public function behaviors()
    {
        $behaviors =
        [
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
    
    public function getCampos()
    {
        return $this->hasMany(MapaCampo::className(), ['id_mapa' => 'id']);
    }
}
