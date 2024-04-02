<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Indicador;
use yii\db\Expression;
use yii\helpers\Console;
use app\models\IndicadorCargaHistorico;
use app\magic\CacheMagic;

class BiController extends Controller {

    public function actionCarga($id = null) {
        $start_date = date('Y-m-d H:i:s');
        parent::stdout("{$start_date} - Iniciando rotina de carga\n\n", Console::BG_BLUE);

        if($id)
        {
            $indicadores = Indicador::find()->andWhere([
                'is_ativo' => TRUE,
                'is_excluido' => FALSE,
                'tipo' => 'database',
                'id' => $id
            ])->all();
        }
        else
        {
            $indicadores = Indicador::find()->andWhere([
                'is_ativo' => TRUE,
                'is_excluido' => FALSE,
                'tipo' => 'database'
            ])->andWhere("TIME(NOW()) > TIME(CONCAT(hora_inicial,':00')) AND (executed_at IS NULL OR TIMESTAMPDIFF(SECOND, executed_at, NOW()) > periodicidade)")->all();
        }

        $db = \Yii::$app->db;

        foreach ($indicadores as $indicador) {
            $start_date = date('Y-m-d H:i:s');
            parent::stdout("{$start_date} - Iniciando carga no indicador:: {$indicador['id']} - {$indicador['nome']}\n\n", Console::BG_BLUE);

            $conn = $indicador->conexao->getConnection();
            $success = TRUE;
            $message = '';

            if ($conn) {
                $data = $conn->createCommand($indicador->sql)->queryAll();

                $campos = $valores = [];

                $db->createCommand("DELETE FROM bpbi_indicador{$indicador->id} WHERE id > 0;")->execute();

                if ($data) {
                    foreach ($data as $index_valor => $dado) {
                        $index = 0;

                        if ($index_valor == 0) {
                            $campos[$index_valor][] = "id_indicador";
                        }

                        $valores[$index_valor][] = $indicador->id;

                        foreach ($dado as $valor) {
                            if ($index_valor == 0) {
                                $campos[$index_valor][] = "valor{$index}";
                            }

                            $valores[$index_valor][] = $valor;
                            $index++;
                        }
                    }

                    try {
                        $db->createCommand()->batchInsert("{{%bpbi_indicador{$indicador->id}}}", $campos[0], $valores)->execute();
                    } catch (\yii\db\Exception $ex) {
                        $success = false;
                        $message = $ex->errorInfo[2];
                    }

                    parent::stdout("Carga realizada com sucesso. \n\n", Console::BG_GREEN);
                } else {
                    parent::stdout("Nenhum resultado foi carregado\n\n", Console::BG_YELLOW);
                }

                if ($success) {
                    $indicador->executed_at = new Expression('NOW()');
                    $indicador->save(FALSE, ['executed_at']);
                }
            } else {
                parent::stdout("Erro ao tentar se conectar\n\n", Console::BG_RED);
            }

            $end_date = date('Y-m-d H:i:s');
            self::history($indicador, $start_date, $end_date, $success, $message);

            parent::stdout("{$end_date} - Finalizando carga no indicador:: {$indicador['id']} - {$indicador['nome']}\n\n", Console::BG_GREEN);
        }

        $end_date = date('Y-m-d H:i:s');
        parent::stdout("{$end_date} - Finalizando rotina de carga\n\n", Console::BG_BLUE);
    }

    public static function history($indicador, $started_at, $finished_at, $success = true, $message = '') {
        $db = \Yii::$app->db;
        $total = $db->createCommand("SELECT count(1) FROM bpbi_indicador{$indicador->id}")->queryScalar();

        $model = new IndicadorCargaHistorico();
        $model->id_indicador = $indicador->id;
        $model->tipo_carga = "PHP";
        $model->total = ($total) ? $total : 0;
        $model->started_at = $started_at;
        $model->finished_at = $finished_at;
        $model->success = $success;
        $model->message = $message;
        $model->save();

        if (!$success) {
            self::sendErrorEmail($indicador, $started_at, $message);
        }
    }

    public static function sendErrorEmail($indicador, $started_at, $error) {
        $company_name = CacheMagic::getSystemData('name');

        $message = '';
        $message .= 'EMPRESA: <b>' . $company_name . '</b><br>';
        $message .= 'CUBO: <b>' . $indicador->id . ' - ' . $indicador->nome . '</b><br>';
        $message .= 'HORÁRIO: <b>' . $started_at . '</b><br>';
        $message .= "<br />--------------------------------------------<br />";
        $message .= "DBEXCEPTION <br />";
        $message .= 'Mensagem: <b>' . $error . '</b>';

        Yii::$app->mailer->compose()
                ->setFrom('bp1@bpone.com.br')
                ->setTo(['jhordan.magalhaes@bp1.com.br', 'pedro.alves@bpone.com.br'])
                ->setSubject('ERRO DE CARGA - BPBI')
                ->setHtmlBody($message)
                ->send();
    }

}
