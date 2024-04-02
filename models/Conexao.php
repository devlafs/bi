<?php

namespace app\models;

use Yii;
use yii\db\Exception;
use yii\db\Connection;

class Conexao extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'bpbi_conexao';
    }

    public function rules() {
        return [
            [['nome', 'tipo', 'host', 'database', 'login', 'senha'], 'required'],
            [['created_at', 'updated_at', 'is_ativo', 'is_excluido', 'created_by', 'updated_by', 'id_importacao'], 'safe'],
            [['nome', 'tipo', 'host', 'database', 'porta', 'login', 'senha'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels() {
        return
                [
                    'nome' => Yii::t('app', 'geral.nome'),
                    'tipo' => Yii::t('app', 'geral.tipo'),
                    'host' => Yii::t('app', 'conexao.host'),
                    'database' => Yii::t('app', 'conexao.database'),
                    'porta' => Yii::t('app', 'conexao.porta'),
                    'login' => Yii::t('app', 'conexao.login'),
                    'senha' => Yii::t('app', 'geral.senha'),
                    'is_ativo' => Yii::t('app', 'geral.is_ativo'),
                    'is_excluido' => Yii::t('app', 'geral.is_excluido'),
                    'created_at' => Yii::t('app', 'geral.created_at'),
                    'updated_at' => Yii::t('app', 'geral.updated_at'),
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

    public function getIndicadores() {
        return $this->hasMany(Indicador::className(), ['id_conexao' => 'id']);
    }

    public static function find() {
        return new queries\ConexaoQuery(get_called_class());
    }

    public function testConnection() {

        if($this->tipo == 'firebird')
        {
            $connection = new \edgardmessias\db\firebird\Connection([
                'dsn' => $this->getDnsByType(),
                'username' => $this->login,
                'password' => $this->senha,
            ]);
        }
        else
        {
            $connection = new Connection([
                'dsn' => $this->getDnsByType(),
                'username' => $this->login,
                'password' => $this->senha,
                'charset' => 'utf8'
            ]);
        }

        $message = 'Online';

        try {
            $connection->open();
            $error = $connection->createCommand($this->getTestByType())->queryScalar();
        } catch (Exception $ex) {
            $error = FALSE;
            $message = $ex->getMessage();
        }

        return ['error' => $error, 'message' => $message];
    }

    public function getConnection() {

        if($this->tipo == 'firebird')
        {
            $connection = new \edgardmessias\db\firebird\Connection([
                'dsn' => $this->getDnsByType(),
                'username' => $this->login,
                'password' => $this->senha,
            ]);
        }
        else
        {
            $connection = new Connection([
                'dsn' => $this->getDnsByType(),
                'username' => $this->login,
                'password' => $this->senha,
                'charset' => 'utf8'
            ]);
        }

        try {
            $connection->open();
        } catch (Exception $ex) {
            return null;
        }

        return $connection;
    }

    public function getDnsByType() {
        $dns = "";

        switch ($this->tipo) {
            case "mysql":
                $dns = "mysql:host={$this->host};dbname={$this->database}";
                break;
            case "oracle":
                $dns = "oci:dbname=//{$this->host}/{$this->database};charset=UTF8";
                break;
            case "pgsql":
                $dns = "pgsql:host={$this->host};dbname={$this->database}";
                break;
            case "sqlserver":
                $dns = "sqlsrv:Server={$this->host};Database={$this->database}";
                break;
            case "firebird":
                $dns = "firebird:dbname={$this->host}:/{$this->database}";
                break;
        }

        return $dns;
    }

    public function getTestByType() {
        $query = "";

        switch ($this->tipo) {
            case "mysql":
            case "pgsql":
            case "sqlserver":
                $query = "SELECT 1";
                break;
            case "firebird":
                $query = 'SELECT 1 as foo FROM RDB$DATABASE;';
                break;
            case "oracle":
                $query = "SELECT 1 FROM DUAL";
        }

        return $query;
    }

}
