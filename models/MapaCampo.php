<?php

namespace app\models;

use Yii;

class MapaCampo extends \yii\db\ActiveRecord
{
    public $id_indicador;
    
    public $nome_campo;
    
    public static function tableName()
    {
        return 'bpbi_mapa_campo';
    }

    public function rules()
    {
        return [
            [['id_mapa', 'id_indicador', 'id_campo'], 'integer'],
            [['tag', 'id_indicador', 'id_campo'], 'required'],
            [['descricao'], 'string'],
            [['created_at', 'updated_at', 'nome_campo', 'is_ativo', 'is_excluido', 'created_by', 'updated_by'], 'safe'],
            [['tag'], 'string', 'max' => 255],
            [['id_campo'], 'exist', 'skipOnError' => true, 'targetClass' => IndicadorCampo::className(), 'targetAttribute' => ['id_campo' => 'id']],
            [['id_mapa'], 'exist', 'skipOnError' => true, 'targetClass' => Mapa::className(), 'targetAttribute' => ['id_mapa' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return 
        [
            'id_mapa' => 'Mapa',
            'id_indicador' => 'Cubo',
            'id_campo' => 'Campo',
            'nome_campo' => 'Nome',
            'tag' => 'Tag',
            'descricao' => Yii::t('app', 'geral.descricao'),
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
    
    public function afterFind() 
    {
        parent::afterFind();
        
        $this->id_indicador = $this->campo->id_indicador;
    }
    
    public function getCampo()
    {
        return $this->hasOne(IndicadorCampo::className(), ['id' => 'id_campo']);
    }

    public function getMapa()
    {
        return $this->hasOne(Mapa::className(), ['id' => 'id_mapa']);
    }
}
