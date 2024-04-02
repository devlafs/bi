<?php

namespace app\models;

use \moonland\phpexcel\Excel;
use Yii;

class Metadado extends \yii\db\ActiveRecord
{
    public $file;

    public $novo_indicador = false;

    public static function tableName()
    {
        return 'bpbi_metadado';
    }

    public function rules()
    {
        return [
            [['nome'], 'required'],
            [['descricao'], 'string'],
            [['created_by', 'updated_by'], 'integer'],
            [['novo_indicador', 'file', 'is_incremental', 'is_ativo', 'is_excluido', 'created_at', 'updated_at', 'executed_at'], 'safe'],
            [['nome', 'caminho'], 'string', 'max' => 255],
            [['file'], 'file'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'nome' => Yii::t('app', 'geral.nome'),
            'novo_indicador' => Yii::t('app', 'metadado.novo_indicador'),
            'descricao' => Yii::t('app', 'geral.descricao'),
            'file' => Yii::t('app', 'metadado.file'),
            'caminho' => Yii::t('app', 'metadado.caminho'),
            'is_incremental' => Yii::t('app', 'metadado.is_incremental'),
            'is_ativo' => Yii::t('app', 'geral.is_ativo'),
            'is_excluido' => Yii::t('app', 'geral.is_excluido'),
            'created_at' => Yii::t('app', 'geral.created_at'),
            'updated_at' => Yii::t('app', 'geral.updated_at'),
            'executed_at' => Yii::t('app', 'metadado.executed_at'),
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

    public function notIncrementalMetadado($insert)
    {
        $mydb = \Yii::$app->db;

        $mydb->createCommand("DROP TABLE IF EXISTS bpbi_metadado{$this->id};")->execute();
        $columns = "id bigint(20) NOT NULL AUTO_INCREMENT, ";

        $file = dirname(__FILE__) . '/../web/uploads/' . $this->caminho;
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);

        $maxCell = $spreadsheet->getActiveSheet()->getHighestRowAndColumn();
        $data = $spreadsheet->getActiveSheet()->rangeToArray('A1:' . $maxCell['column'] . $maxCell['row']);

        $fields = [];
        $select = "SELECT";

        foreach ($data[0] as $ordem => $campo) {
            $field = "valor{$ordem}";
            $fields[] = $field;
            $columns .= "{$field} text, ";
            $select .= " {$field} as '$campo',";
        }

        $columns .= "created_at timestamp NOT NULL DEFAULT current_timestamp, PRIMARY KEY (id)";

        $mydb->createCommand("CREATE TABLE IF NOT EXISTS bpbi_metadado{$this->id} ({$columns})  ENGINE=InnoDB  CHARACTER SET utf8;")->execute();

        unset($data[0]);

        $mydb->createCommand()
            ->batchInsert("bpbi_metadado{$this->id}", $fields, $data)
            ->execute();

        if($insert)
        {
            $select .= " 1 as Quantidade FROM bpbi_metadado{$this->id}";

            $conexao = Conexao::find()->andWhere([
                'is_ativo' => TRUE,
                'is_excluido' => FALSE,
                'database' => 'bpbi',
                'tipo' => 'mysql'
            ])->one();

            if(!$conexao)
            {
                $conexao = new Conexao();
                $conexao->nome = 'BPbi';
                $conexao->tipo = 'mysql';
                $conexao->host = 'localhost';
                $conexao->database = 'bpbi';
                $conexao->login = 'root';
                $conexao->senha = 'r44t';
                $conexao->save();
            }

            if($conexao)
            {
                $indicador = new Indicador();
                $indicador->setScenario('create');
                $indicador->nome = $this->nome;
                $indicador->tipo = 'database';
                $indicador->id_conexao = $conexao->id;
                $indicador->periodicidade = 86400;
                $indicador->hora_inicial = '01:00';
                $indicador->sql = $select;
                $indicador->save(FALSE);
            }
        }
    }

    public function incrementalMetadado()
    {
        $mydb = \Yii::$app->db;

        $file = dirname(__FILE__) . '/../web/uploads/' . $this->caminho;
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);

        $maxCell = $spreadsheet->getActiveSheet()->getHighestRowAndColumn();
        $data = $spreadsheet->getActiveSheet()->rangeToArray('A1:' . $maxCell['column'] . $maxCell['row']);

        $fields = [];

        foreach ($data[0] as $ordem => $campo) {
            $fields[] = "valor{$ordem}";
        }

        unset($data[0]);

        $mydb->createCommand()
            ->batchInsert("bpbi_metadado{$this->id}", $fields, $data)
            ->execute();
    }

    public function afterSave($insert, $changedAttributes) {
        if ($this->file) {
            ini_set('memory_limit', '-1');

            if($this->is_incremental && !$insert)
            {
                $this->incrementalMetadado();
            }
            else
            {
                $this->notIncrementalMetadado($insert && $this->novo_indicador);
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }

    public function getQuantidadeDados() {
        $sql = <<<SQL
    
            SELECT count(1) FROM bpbi_metadado{$this->id};
                
SQL;

        return Yii::$app->db->createCommand($sql)->queryScalar();
    }
}
