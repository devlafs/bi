<?php

namespace app\controllers;

use Yii;
use app\models\Email;
use app\models\Consulta;
use app\models\Painel;
use app\models\AdminPerfil;
use app\models\AdminUsuario;
use app\models\searches\EmailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use app\magic\EmailMagic;
use app\models\AdminConfiguracoes;

class EmailController extends BaseController {

    public function behaviors() {
        return
                [
                    'access' =>
                    [
                        'class' => AccessControl::className(),
                        'rules' =>
                        [
                            [
                                'actions' =>
                                [
                                    'index', //email-visualizar
                                    'view', //email-visualizar
                                    'create', //email-cadastrar
                                    'update', //email-alterar
                                    'delete' //email-excluir
                                ],
                                'allow' => true,
                                'roles' => ['@'],
                                'matchCallback' => function ($rule, $action) {
                                    return \Yii::$app->permissaoGeral->can($this->id, $this->action->id);
                                },
                            ],
                            [
                                'actions' =>
                                [
                                    'load-users', //@
                                    'send' //@
                                ],
                                'allow' => true,
                                'roles' => ['@']
                            ],
                        ],
                    ]
        ];
    }

    public function actionIndex($t = 'consulta') {
        $searchModel = new EmailSearch();
        $dataProvider = $searchModel->search($t, Yii::$app->request->queryParams);

        return $this->render('index_' . $t, [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate($t = 'consulta') {
        $model = new Email();

        if ($t == 'consulta') {
            $data = Consulta::find()->joinWith(['indicador'])->andWhere([
                        'bpbi_consulta.is_ativo' => TRUE,
                        'bpbi_consulta.is_excluido' => FALSE,
                        'bpbi_indicador.is_ativo' => TRUE,
                        'bpbi_indicador.is_excluido' => FALSE,
                    ])->orderBy('bpbi_consulta.nome ASC')->all();

            $model->view = $model::VIEW_CONSULTA;
        } else {
            $data = Painel::find()->andWhere([
                        'is_ativo' => TRUE,
                        'is_excluido' => FALSE
                    ])->orderBy('nome ASC')->all();

            $model->view = $model::VIEW_PAINEL;
        }

        $perfis = AdminPerfil::find()->andWhere([
                    'is_ativo' => TRUE,
                    'is_excluido' => FALSE
                ])->orderBy('nome ASC')->all();

        $beeIntegration = Yii::$app->params['beeIntegration'];

        if ($beeIntegration) {
            $sqlDepartamento = "SELECT  id, nome FROM admin_configuracoes WHERE tipo = 'Departamento' ORDER BY nome ASC";
        } else {
            $sqlDepartamento = "SELECT departamento as id, departamento as nome FROM admin_usuario WHERE status = 'Ativo' GROUP BY departamento ORDER BY departamento ASC";
        }

        $departamentos = Yii::$app->userDb->createCommand($sqlDepartamento)->queryAll();

        if ($model->load(Yii::$app->request->post()) && !$model->reload_form && $model->save()) {
            \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_cadastrado', [
                'model' => Yii::t('app', 'geral.email')
            ]));

            return $this->redirect(['index', 't' => $t]);
        }

        $model->reload_form = 0;

        return $this->render('create', compact('model', 't', 'data', 'perfis', 'departamentos'));
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $t = ($model->id_consulta) ? 'consulta' : 'painel';

        if ($t == 'consulta') {
            $data = Consulta::find()->joinWith(['indicador'])->andWhere([
                        'bpbi_consulta.is_ativo' => TRUE,
                        'bpbi_consulta.is_excluido' => FALSE,
                        'bpbi_indicador.is_ativo' => TRUE,
                        'bpbi_indicador.is_excluido' => FALSE,
                    ])->orderBy('bpbi_consulta.nome ASC')->all();

            $model->view = $model::VIEW_CONSULTA;
        } else {
            $data = Painel::find()->andWhere([
                        'is_ativo' => TRUE,
                        'is_excluido' => FALSE
                    ])->orderBy('nome ASC')->all();

            $model->view = $model::VIEW_PAINEL;
        }

        $perfis = AdminPerfil::find()->andWhere([
                    'is_ativo' => TRUE,
                    'is_excluido' => FALSE
                ])->orderBy('nome ASC')->all();

        $beeIntegration = Yii::$app->params['beeIntegration'];

        if ($beeIntegration) {
            $sqlDepartamento = "SELECT  id, nome FROM admin_configuracoes WHERE tipo = 'Departamento' ORDER BY nome ASC";
        } else {
            $sqlDepartamento = "SELECT departamento as id, departamento as nome FROM admin_usuario WHERE status = 'Ativo' GROUP BY departamento ORDER BY departamento ASC";
        }

        $departamentos = Yii::$app->userDb->createCommand($sqlDepartamento)->queryAll();

        $usuario = ($model->id_usuario) ? AdminUsuario::findOne($model->id_usuario) : null;

        if ($model->load(Yii::$app->request->post()) && !$model->reload_form && $model->save()) {
            \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_alterado', [
                'model' => Yii::t('app', 'geral.email')
            ]));

            return $this->redirect(['index', 't' => $t]);
        }

        $model->reload_form = 0;

        return $this->render('update', compact('model', 't', 'data', 'perfis', 'usuario', 'departamentos'));
    }

    public function actionDelete($id) {
        $model = $this->findModel($id);
        $model->is_excluido = TRUE;
        $model->tipo_destinatario = $model->getTipoDestinatario();

        if ($model->save()) {
            \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_removido', [
                'model' => Yii::t('app', 'geral.email')
            ]));
        }

        return true;
    }

    public function actionSend($id) {
        $model = $this->findModel($id);
        $t = ($model->id_consulta) ? 'consulta' : 'painel';
        EmailMagic::enviar($id);

        \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_enviado', [
            'model' => Yii::t('app', 'geral.email')
        ]));

        return $this->redirect(['index', 't' => $t]);
    }

    public function actionLoadUsers($q = null, $id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $out = ['results' => ['id' => '', 'text' => '']];

        if (!is_null($q)) {
            $query = new \yii\db\Query();
            $data = $query->select('id AS id, nomeResumo AS text')
                            ->from('admin_usuario')
                            ->where(['like', 'nomeResumo', $q])->all(\Yii::$app->userDb);

            $out['results'] = array_values($data);
        } elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => AdminUsuario::find($id)->nomeResumo];
        }

        return $out;
    }

    protected function findModel($id) {
        if (($model = Email::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'controller.mensagem_erro_404'));
    }

}
