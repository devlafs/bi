<?php

namespace app\models;

use Yii;
use yii\db\Exception;
use yii\bootstrap\Html;

class Indicador extends \yii\db\ActiveRecord {

    public $qtd_dados;

    public static function tableName() {
        return 'bpbi_indicador';
    }

    public function rules() {
        return
                [
                    [['id_conexao', 'tipo', 'nome', 'periodicidade', 'sql', 'hora_inicial'], 'required'],
                    [['id_conexao'], 'integer'],
                    [['descricao', 'sql'], 'string'],
                    [['sql'], 'validateInsert', 'on' => ['create']],
                    [['sincrono', 'created_at', 'updated_at', 'executed_at', 'is_ativo', 'is_excluido', 'created_by', 'updated_by', 'id_importacao', 'qtd_dados', 'hora_inicial'], 'safe'],
                    [['tipo', 'nome', 'caminho', 'periodicidade'], 'string', 'max' => 255],
                    [['id_conexao'], 'exist', 'skipOnError' => true, 'targetClass' => Conexao::className(), 'targetAttribute' => ['id_conexao' => 'id']],
        ];
    }

    public function attributeLabels() {
        return
                [
                    'id_conexao' => Yii::t('app', 'indicador.id_conexao'),
                    'tipo' => Yii::t('app', 'geral.tipo'),
                    'nome' => Yii::t('app', 'geral.nome'),
                    'descricao' => Yii::t('app', 'geral.descricao'),
                    'sql' => Yii::t('app', 'indicador.sql'),
                    'caminho' => Yii::t('app', 'indicador.caminho'),
                    'periodicidade' => Yii::t('app', 'indicador.periodicidade'),
                    'qtd_dados' => Yii::t('app', 'indicador.qtd_dados'),
                    'hora_inicial' => Yii::t('app', 'indicador.hora_inicial'),
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

    public function getConsultas() {
        return $this->hasMany(Consulta::className(), ['id_indicador' => 'id']);
    }

    public function getConexao() {
        return $this->hasOne(Conexao::className(), ['id' => 'id_conexao']);
    }

    public function getCampos() {
        return $this->hasMany(IndicadorCampo::className(), ['id_indicador' => 'id']);
    }

    public static function find() {
        return new queries\IndicadorQuery(get_called_class());
    }

    public function getStringConsultas() {
        $string = "";

        foreach ($this->consultas as $consulta) {
            if (!$consulta->is_excluido) {
                $badge = (!$consulta->is_ativo) ? "<span class='badge badge-warning'>" . Yii::t('app', 'indicador.texto_inativo') . "</span>" : "";
                $string .= Html::a("{$consulta->nome} {$badge}", ['/consulta/alterar', 'id' => $consulta->id], ['title' => $consulta->getPathName(FALSE)]) . ' <br>';
            }
        }

        return $string;
    }

    public function getLogsCarga() {
        $logs = IndicadorCargaHistorico::find()->andWhere(['id_indicador' => $this->id])
                        ->orderBy("started_at DESC")->limit(10)->all();

        $str = "";

        foreach ($logs as $log) {
            $started_at = Yii::$app->formatter->asDate($log->started_at, 'php:d/m/Y H:i');
            $finished_at = Yii::$app->formatter->asDate($log->finished_at, 'php:d/m/Y H:i');
            $class = ($log->success == 1) ? 'success' : 'danger';
            $logx = ($log->success == 1) ? Yii::t('app', 'indicador.sucesso_carga') : Yii::t('app', 'indicador.erro_carga');
            $text = "{$started_at} - {$finished_at} : {$logx}";
            $str .= '<span title="' . $log->message . '" class="badge badge-' . $class . '">' . $text . '</span> <br>';
        }

        return $str;
    }

    public function getQuantidadeDados() {
        $sql = <<<SQL
    
            SELECT count(1) FROM bpbi_indicador{$this->id};
                
SQL;

        return Yii::$app->db->createCommand($sql)->queryScalar();
    }

    public function validateInsert($attribute, $params, $validator) {
        $conn = $this->conexao->getConnection();

        try {
            $campos = $conn->createCommand($this->sql)->queryOne();
        } catch (Exception $e) {
            $this->addError('sql', $e->getMessage());
        }
    }

    public function afterSave($insert, $changedAttributes) {
        if ($insert) {
            $conn = $this->conexao->getConnection();
            $mydb = \Yii::$app->db;

            $campos = $conn->createCommand($this->sql)->queryAll();

            $mydb->createCommand("DROP TABLE IF EXISTS bpbi_indicador{$this->id};")->execute();

            $mydb->createCommand()
                    ->delete('bpbi_indicador_campo', ['id_indicador' => $this->id])
                    ->execute();

            $columns = "id bigint(20) NOT NULL AUTO_INCREMENT, id_indicador bigint(20), ";
            $keys = "PRIMARY KEY (id), ";

            foreach ($campos as $campo) {
                $ordem = 1;
                $qtd_keys = 1;

                foreach ($campo as $nome => $valor) {
                    $mydb->createCommand()->insert('bpbi_indicador_campo', [
                        'id_indicador' => $this->id,
                        'campo' => $nome,
                        'nome' => $nome,
                        'descricao' => $nome,
                        'ordem' => $ordem,
                    ])->execute();

                    if(!$this->sincrono)
                    {
                        if($this->conexao->tipo == 'sqlserver')
                        {
                            $sqlMaxLen = str_replace("?", "", "SELECT MAX(LEN({$nome})) FROM ({$this->sql}) as v;");
                        }
                        elseif($this->conexao->tipo == 'firebird')
                        {
                            $sqlMaxLen = str_replace("?", "", "SELECT MAX(CHAR_LENGTH({$nome})) FROM ({$this->sql}) as v;");
                        }
                        else
                        {
                            $sqlMaxLen = str_replace("?", "", "SELECT MAX(LENGTH(`{$nome}`)) FROM ({$this->sql}) as v;");
                        }

                        (int) $maxLen = $conn->createCommand($sqlMaxLen)->queryScalar();

                        $type = "varchar(255)";
                        $key = "KEY bpbi_indicador" . $this->id . "_" . ($ordem - 1) . " (valor" . ($ordem - 1) . "), ";

                        if ($maxLen > 255) {
                            $type = "text";
                            $key = "";
                            $qtd_keys--;
                        }

                        $columns .= "valor" . ($ordem - 1) . " {$type} DEFAULT NULL, ";

                        if ($qtd_keys < 64) {
                            $keys .= $key;
                        }
                    }

                    $ordem++;
                    $qtd_keys++;
                }

                break;
            }

            if(!$this->sincrono)
            {
                $columns .= "created_at timestamp NOT NULL DEFAULT current_timestamp, ";
                $keys = substr($keys, 0, -2);

                $mydb->createCommand("CREATE TABLE IF NOT EXISTS bpbi_indicador{$this->id} ({$columns} {$keys})  ENGINE=InnoDB  CHARACTER SET utf8;")->execute();
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }

}
