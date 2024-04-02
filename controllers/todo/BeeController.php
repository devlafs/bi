<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class BeeController extends BaseController {

    public function behaviors() {
        return
                [
                    'access' =>
                    [
                        'class' => AccessControl::className(),
                        'rules' => [
                            [
                                'actions' => ['index', 'progress'], //@
                                'allow' => true,
                            ],
                        ],
                    ]
        ];
    }

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }

    public function actionProgress($id) {
        $this->layout = '//url-publica/main';

        $sql = <<<SQL
                
            SELECT
                DISTINCT
                solicitacao.codigo AS `codigoSolicitacao`,
                solicitacao.id AS `idSolicitacao`,
                CONCAT('[', solicitacao.codigo, ' - ', solicitacao.nomeProcesso, ']') AS `nomeProcesso`,
                solicitacao.status AS `statusSolicitacao`,
                fluati.nome AS `nomeAtividade`,
                fluati.ordem AS `ordemAtividade`,
                fluati.marco AS `marcoAtividade`,
                COALESCE(DATE_FORMAT(prazoSolicitacao.dataCadastro, '%d/%m/%Y %H:%i'), '') AS `aberturaSolicitacao`,
                COALESCE(DATE_FORMAT(prazoSolicitacao.dataConclusao, '%d/%m/%Y %H:%i'), '') AS `conclusaoSolicitacao`,
                COALESCE(DATE_FORMAT(prazoAtividade.dataCadastro, '%d/%m/%Y %H:%i'), '') AS `aberturaAtividade`,
                COALESCE(DATE_FORMAT(prazoAtividade.dataConclusao, '%d/%m/%Y %H:%i'), '') AS `conclusaoAtividade`,
                CASE WHEN atividade.id IS NOT NULL THEN '1' ELSE '0' END as executado,
                COALESCE(atividade.status, '') AS `statusAtividade`
                FROM pr_solicitacao solicitacao
                INNER JOIN pr_fluxo flu ON flu.id = solicitacao.processo_id
                INNER JOIN pr_fluxo_versao fluver ON fluver.fluxo_id = flu.id
                INNER JOIN pr_fluxo_grupodeatividade flugru ON flugru.fluxoVersao_id = fluver.id
                INNER JOIN pr_fluxo_atividade fluati ON fluati.fluxoAtividade_id = flugru.id
                LEFT JOIN pr_solicitacao_atividade atividade ON atividade.processo_id = solicitacao.id AND atividade.prAtividadeId = fluati.id
                LEFT JOIN pr_solicitacao_prazo prazoSolicitacao on prazoSolicitacao.id = solicitacao.prazo_id
                LEFT JOIN pr_solicitacao_prazo prazoAtividade on prazoAtividade.id = atividade.prazo_id
                WHERE solicitacao.codigo = {$id}
                AND flu.id IN (8, 14)
                order by solicitacao.codigo, fluati.ordem;
                
SQL;

        $data = Yii::$app->dbBee->createCommand($sql)->queryAll();

        return $this->render('progress', compact('data', 'id'));
    }

}
