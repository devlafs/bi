<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\magic\SqlMagic;
use app\models\Consulta;
use app\models\UltimaTelaAcesso;
use app\models\UrlShare;
use app\magic\CacheMagic;

class GraficoController extends BaseController {

    public function behaviors() {
        return
                [
                    'access' =>
                    [
                        'class' => AccessControl::className(),
                        'rules' =>
                        [
                            [
                                'actions' => ['share'], //all
                                'allow' => true,
                            ],
                            [
                                'actions' => ['view'], //@
                                'allow' => true,
                                'roles' => ['@'],
                            ],
                        ],
                    ]
        ];
    }

    public function actionShare($c, $t, $previous = FALSE) {
        $this->layout = FALSE;
        $url = $this->findUrlModel($c, $t);

        if (!$url) {
            return null;
        }

        $model = $url->consulta;

        if ($post = Yii::$app->request->post()) {
            $index = $post['index'];
            $data = SqlMagic::getData($model, $index, isset($post['filtro']) ? $post['filtro'] : null, $post['token'], $previous, 100000000, TRUE, $url->id_usuario);

            if ($data['error']) {
                return $this->renderAjax("/_graficos/error", compact('index', 'data', 'model'));
            } else {
                return $this->renderAjax("/_graficos/_general/share", compact('index', 'data', 'model'));
            }
        }
    }

    public function actionView($id, $previous = FALSE) {
        $this->layout = FALSE;
        $model = $this->findModel($id);

        if ($post = Yii::$app->request->post()) {
            $index = $post['index'];
            $data = SqlMagic::getData($model, $index, isset($post['filtro']) ? $post['filtro'] : null, $post['token'], $previous, 100000000, TRUE);

            if ($data['error']) {
                return $this->renderAjax("/_graficos/error", compact('index', 'data', 'model'));
            } else {
                $this->salvarHistorico($id, $post['index'], $data['token']);
                return $this->renderAjax("/_graficos/_general/view", compact('index', 'data', 'model'));
            }
        }
    }

    protected function findModel($id) {
        if (($model = Consulta::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'controller.mensagem_erro_404'));
    }

    protected function findUrlModel($c, $t) {
        $model = UrlShare::find()->andWhere([
                    'id' => $c,
                    'token' => $t,
                    'is_ativo' => TRUE,
                    'is_excluido' => FALSE
                ])->one();

        if ($model) {
            $days = ($model->type == 'url') ? CacheMagic::getSystemData('urlShareDaysExpiration') :
                    CacheMagic::getSystemData('emailShareDaysExpiration');

            $expirate_date = strtotime(date('Y-m-d', strtotime("-{$days} day")));
            $created_at = strtotime($model->created_at);

            $aux_model = ($model->view == UrlShare::VIEW_CONSULTA) ? $model->consulta : $model->painel;

            if ($created_at < $expirate_date || !$aux_model->is_ativo || $aux_model->is_excluido) {
                $model = null;
            }
        }

        return $model;
    }

    protected function salvarHistorico($id_consulta, $index, $token = null) {
        $user_id = Yii::$app->user->identity->id;

        $model = new UltimaTelaAcesso();
        $model->id_usuario = $user_id;
        $model->view = UltimaTelaAcesso::VIEW_CONSULTA;
        $model->id_consulta = $id_consulta;
        $model->id_painel = null;
        $model->index = $index;
        $model->token = $token;
        $model->save();
    }

}
