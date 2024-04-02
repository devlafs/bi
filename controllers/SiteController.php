<?php

namespace app\controllers;

use app\models\AdminUsuario;
use app\models\PerfilComplemento;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\UltimaTelaAcesso;
use app\magic\CacheMagic;
use app\models\Sistema;
use yii\web\Cookie;

class SiteController extends BaseController {

    public function behaviors() {
        return
                [
                    'access' =>
                    [
                        'class' => AccessControl::className(),
                        'rules' => [
                            [
                                'actions' => ['error'], //all
                                'allow' => true,
                            ],
                            [
                                'actions' => ['logout', 'index', 'change-language'], //@
                                'allow' => true,
                                'roles' => ['@'],
                            ],
                        ],
                    ]
        ];
    }

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }

    public function actionIndex() {
        $user_id = Yii::$app->user->identity->id;
        $perfil_id = Yii::$app->user->identity->perfil_id;

        $complemento = PerfilComplemento::find()->andWhere([
            'id_perfil' => $perfil_id
        ])->one();

        if($complemento)
        {
            switch ($complemento->pagina_inicial)
            {
                case PerfilComplemento::CONSULTA_ESPECIFICO:
                    return $this->redirect(['consulta/visualizar',
                        'id' => $complemento->id_consulta
                    ]);
                    break;
                case PerfilComplemento::PAINEL_ESPECIFICO:
                    return $this->redirect(['painel/visualizar',
                        'id' => $complemento->id_painel
                    ]);
                    break;
                case PerfilComplemento::RELATORIO_ESPECIFICO:
                    return $this->redirect(['relatorio-data/visualizar',
                        'id' => $complemento->id_relatorio_data
                    ]);
                    break;
            }
        }

        $model = UltimaTelaAcesso::find()->andWhere([
            'id_usuario' => $user_id,
            'is_ativo' => true,
            'is_excluido' => false
        ])->orderBy('created_at DESC')->one();

        if (!$model || ($complemento && $complemento->pagina_inicial == PerfilComplemento::PAGINA_INICIAL)) {
            $html = CacheMagic::getHomepageHtml();
            return $this->render('index', compact('html'));
        } else {
            if ($model->view == UltimaTelaAcesso::VIEW_CONSULTA) {
                if (!$model->consulta->is_ativo || $model->consulta->is_excluido || !$model->consulta->indicador->is_ativo || $model->consulta->indicador->is_excluido) {
                    return $this->render('index');
                } else {
                    return $this->redirect(['consulta/visualizar',
                                'id' => $model->id_consulta,
                                'index' => $model->index,
                                'token' => $model->token,
                    ]);
                }
            } elseif ($model->view == UltimaTelaAcesso::VIEW_PAINEL) {
                if (!$model->painel->is_ativo || $model->painel->is_excluido) {
                    return $this->render('index');
                } else {
                    return $this->redirect(['painel/visualizar',
                                'id' => $model->id_painel
                    ]);
                }
            } else {
                if (!$model->relatorio->is_ativo || $model->relatorio->is_excluido) {
                    return $this->render('index');
                } else {
                    return $this->redirect(['relatorio-data/visualizar',
                        'id' => $model->id_relatorio_data
                    ]);
                }
            }
        }
    }

    public function actionChangeLanguage($lang = 'pt-BR')
    {
        $allowed_languages = ['es', 'en-US'];
        $selected_language = in_array($lang, $allowed_languages) ? $lang : 'pt-BR';
        Yii::$app->language = $selected_language;
        Yii::$app->session->set('language', $selected_language);

        $user = AdminUsuario::findOne(Yii::$app->user->id);
        $user->language = $selected_language;
        $user->save(FALSE, ['language']);

        return isset($_SERVER['HTTP_REFERER']) ? $this->redirect($_SERVER['HTTP_REFERER']) : $this->redirect(Yii::$app->homeUrl);
    }

    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionError() {
        $exception = Yii::$app->errorHandler->exception;
        $controller = Yii::$app->controller;
        $user = Yii::$app->user;

        $this->layout = ($user->isGuest) ? 'login' : 'main';

        if ($controller->id == 'share' && $controller->action->id == 'v') {
            $this->layout = 'main';
        }

        if ($exception !== null) {
            if (!$user->getIsGuest()) {
                $nome_empresa = (CacheMagic::getSystemData('name')) ? strtoupper(CacheMagic::getSystemData('name')) : '';

                $message = '';
                $message .= 'ID: <b>' . $user->identity->id . '</b><br>';
                $message .= 'NOME: <b>' . $user->identity->nome . '</b><br>';
                $message .= 'LOGIN: <b>' . $user->identity->login . '</b><br>';
                $message .= 'EMAIL: <b>' . $user->identity->email . '</b><br>';
                $message .= 'PERFIL: <b>' . $user->identity->perfil->nome . '</b><br>';
                $message .= 'CARGO: <b>' . $user->identity->cargo . '</b><br>';
                $message .= 'CELULAR: <b>' . $user->identity->celular . '</b><br>';
                $message .= 'HTTP_USER_AGENT: <b>' . $_SERVER['HTTP_USER_AGENT'] . '</b><br>';
                $message .= 'REDIRECT_STATUS: <b>' . $_SERVER['REDIRECT_STATUS'] . '</b><br>';
                $message .= 'SERVER_NAME: <b>' . $_SERVER['SERVER_NAME'] . '</b><br>';
                $message .= 'SERVER_PORT: <b>' . $_SERVER['SERVER_PORT'] . '</b><br>';
                $message .= 'SERVER_ADDR: <b>' . $_SERVER['SERVER_ADDR'] . '</b><br>';
                $message .= 'REQUEST_METHOD: <b>' . $_SERVER['REQUEST_METHOD'] . '</b><br>';
                $message .= 'REQUEST_URI: <b>' . $_SERVER['REQUEST_URI'] . '</b><br>';
                $message .= "<br />--------------------------------------------<br />";
                $message .= "EXCEPTION <br />";
                $message .= "<pre>" . $exception . "</pre>";
                $message .= 'IP: <b>' . Yii::$app->getRequest()->getUserIP() . '</b><br>';
                $message .= 'URL: <b>' . Yii::$app->request->getUrl() . '</b><br>';
                $message .= 'Mensagem: <b>' . $exception->getMessage() . '</b>';

                if (!preg_match('#\b(assets|apple)\b#', Yii::$app->request->getUrl(), $matches)) {
                    Yii::$app->mailer->compose()
                            ->setFrom('bp1@bpone.com.br')
                            ->setTo(['jhordan.magalhaes@bp1.com.br', 'pedro.alves@bpone.com.br'])
                            ->setSubject('ERRO DE UTILIZAÇÃO - BPBI - ' . $nome_empresa)
                            ->setHtmlBody($message)
                            ->send();
                }
            }

            return $this->render('error', [
                        'code' => $exception->statusCode,
                        'message' => $exception->getMessage(),
            ]);
        }
    }
}
