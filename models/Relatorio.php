<?php

namespace app\models;

use Yii;
use yii\db\Exception;

class Relatorio extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'bpbi_relatorio';
    }

    public function rules()
    {
        return [
            [['id_conexao', 'nome', 'sql'], 'required'],
            [['id_conexao', 'created_by', 'updated_by'], 'integer'],
            [['sql', 'descricao'], 'string'],
            [['is_ativo', 'is_excluido', 'created_at', 'updated_at'], 'safe'],
            [['nome'], 'string', 'max' => 255],
            [['id_conexao'], 'exist', 'skipOnError' => true, 'targetClass' => Conexao::className(), 'targetAttribute' => ['id_conexao' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_conexao' => 'Conexão',
            'nome' => 'Nome',
            'sql' => 'Sql',
            'descricao' => 'Descrição',
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

    public function getConexao()
    {
        return $this->hasOne(Conexao::className(), ['id' => 'id_conexao']);
    }

    public function getCampos()
    {
        return $this->hasMany(RelatorioCampo::className(), ['id_relatorio' => 'id']);
    }

    public function generateFields()
    {
        $conn = $this->conexao->getConnection();

        try {
            $fields = $conn->createCommand($this->sql)->queryOne();

            RelatorioCampo::updateAll(['is_ativo' => false, 'is_excluido' => true], ['id_relatorio' => $this->id]);

            $ordem = 1;

            foreach($fields as $field => $value)
            {
                $campo = new RelatorioCampo();
                $campo->id_relatorio = $this->id;
                $campo->ordem = $ordem;
                $campo->nome = $field;
                $campo->campo = $field;
                $campo->tipo = 1;
                $campo->save();

                $ordem++;
            }

        } catch (Exception $e) {
            return ['status' => '0', 'msg' => $e->getMessage()];
        } finally {
            return ['status' => '1', 'msg' => 'Campos gerados com sucesso.'];
        }
    }
}
