<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\db\Connection;
use app\models\Conexao;
use app\models\Indicador;
use app\models\IndicadorCampo;
use yii\helpers\Console;
use yii\db\Expression;

class IndicadorController extends Controller {

    public $table_conexao = 'admin_conexao';
    public $table_indicador = 'ind_indicador';
    public $table_campo = 'ind_indicador_coleta_campo';

    public function actionImportar($host, $username, $password, $database) {
        $connection = new Connection([
            'dsn' => "mysql:host={$host};dbname={$database};charset=utf8",
            'username' => $username,
            'password' => $password,
        ]);

        $connection->open();

        $this->importarConexoes($connection);
        $this->importarIndicadores($connection);
        $this->importarCampos($connection);
    }

    private function importarConexoes($connection) {
        $conexoes = $connection->createCommand("SELECT * FROM {$this->table_conexao}")->queryAll();

        foreach ($conexoes as $conexao) {
            $created_at = new Expression('NOW()');
            $existe_conexao = Conexao::find()->andWhere(['id_importacao' => $conexao['id']])->exists();

            if (!$existe_conexao) {
                Yii::$app->db->createCommand()->batchInsert('{{%bpbi_conexao}}', [
                    'nome',
                    'tipo',
                    'host',
                    'database',
                    'porta',
                    'login',
                    'senha',
                    'created_at',
                    'id_importacao',
                        ], [
                    [
                        $conexao['nome'],
                        'database',
                        $conexao['url'],
                        $conexao['url'],
                        $conexao['url'],
                        $conexao['login'],
                        $conexao['senha'],
                        $created_at,
                        $conexao['id']
                    ],
                ])->execute();

                parent::stdout("Conexão:: {$conexao['id']} - {$conexao['nome']} "
                        . "importada com sucesso \n\n", Console::BG_GREEN);
            } else {
                parent::stdout("Conexão:: {$conexao['id']} - {$conexao['nome']} "
                        . "já existe \n\n", Console::BG_BLUE);
            }
        }
    }

    private function importarIndicadores($connection) {
        $indicadores = $connection->createCommand("SELECT * FROM {$this->table_indicador} WHERE indr_status = 'A'")->queryAll();

        foreach ($indicadores as $indicador) {
            $existe_indicador = Indicador::find()->andWhere(['id_importacao' => $indicador['id']])->exists();

            if (!$existe_indicador) {
                $created_at = new Expression('NOW()');
                $is_ativo = $indicador['indr_status'] != 'I';
                $conexao = Conexao::findOne(['id_importacao' => $indicador['admcone_id']]);

                if ($conexao) {
                    Yii::$app->db->createCommand()->batchInsert('{{%bpbi_indicador}}', [
                        'id_conexao',
                        'tipo',
                        'nome',
                        'descricao',
                        'sql',
                        'periodicidade',
                        'is_ativo',
                        'created_at',
                        'id_importacao',
                            ], [
                        [
                            $conexao->id,
                            'database',
                            $indicador['desc_indicador'],
                            $indicador['desc_descricao'],
                            $indicador['desc_analitico'],
                            86400,
                            $is_ativo,
                            $created_at,
                            $indicador['id']
                        ],
                    ])->execute();

                    parent::stdout("Indicador:: {$indicador['id']} - {$indicador['desc_indicador']} "
                            . "importado com sucesso \n\n", Console::BG_GREEN);

                    $script = $connection->createCommand("SHOW CREATE TABLE indicador{$indicador['id']}")->queryAll();
                    $scriptTable = $script[0]['Table'];
                    $scriptSql = $script[0]['Create Table'];

                    $novo_indicador = Indicador::findOne(['id_importacao' => $indicador['id']]);

                    if ($novo_indicador) {
                        $sql_insert = $this->formatarSqlCreate($scriptSql, $scriptTable, $novo_indicador->id);

                        Yii::$app->db->createCommand($sql_insert)->execute();

                        parent::stdout("Tabela:: bpbi_indicador{$novo_indicador->id} "
                                . "criada com sucesso \n\n", Console::BG_GREEN);
                    }
                } else {
                    parent::stdout("Indicador:: {$indicador['id']} - {$indicador['desc_indicador']} "
                            . "com conexão não localizada \n\n", Console::BG_RED);
                }
            } else {
                parent::stdout("Indicador:: {$indicador['id']} - {$indicador['desc_indicador']} "
                        . "já existe \n\n", Console::BG_BLUE);
            }
        }
    }

    private function formatarSqlCreate($sql, $table, $indicador_id) {
        $sql = str_replace("CREATE TABLE `{$table}`", "CREATE TABLE IF NOT EXISTS `bpbi_indicador{$indicador_id}`", $sql);

        $sql = str_replace("`id` bigint(20) NOT NULL AUTO_INCREMENT", "`id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_indicador` bigint(20) DEFAULT NULL", $sql);

        $sql = str_replace("PRIMARY KEY (`id`),", "`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),", $sql);

        $sql = str_replace("KEY `indicador9_", "KEY `indicador{$indicador_id}_", $sql);

        return $sql;
    }

    private function importarCampos($connection) {
        $campos = $connection->createCommand("SELECT * FROM {$this->table_campo}")->queryAll();

        foreach ($campos as $campo) {
            $existe_campos = IndicadorCampo::find()->andWhere(['id_importacao' => $campo['id']])->exists();

            if (!$existe_campos) {
                $created_at = new Expression('NOW()');
                $indicador = Indicador::findOne(['id_importacao' => $campo['indindi_id']]);

                if ($indicador) {
                    Yii::$app->db->createCommand()->batchInsert('{{%bpbi_indicador_campo}}', [
                        'id_indicador',
                        'ordem',
                        'nome',
                        'tipo',
                        'descricao',
                        'campo',
                        'created_at',
                        'id_importacao',
                            ], [
                        [
                            $indicador->id,
                            $campo['numr_ordem'],
                            $campo['desc_campo'],
                            'texto',
                            $campo['desc_campo'],
                            $campo['desc_campo'],
                            $created_at,
                            $campo['id']
                        ],
                    ])->execute();

                    parent::stdout("Campo:: {$campo['id']} - {$campo['desc_campo']} "
                            . "importado com sucesso \n\n", Console::BG_GREEN);
                } else {
                    parent::stdout("Campo:: {$campo['id']} - {$campo['desc_campo']} "
                            . "com cubo não localizado \n\n", Console::BG_RED);
                }
            } else {
                parent::stdout("Campo:: {$campo['id']} - {$campo['desc_campo']} "
                        . "já existe \n\n", Console::BG_BLUE);
            }
        }
    }

}
