<?php

namespace app\controllers;

use app\magic\PainelMagic;
use app\models\Painel;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\Consulta;
use yii\web\NotFoundHttpException;

class ContentController extends BaseController {

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
                                    'view', //all
                                    'data' //all
                                ],
                                'allow' => true,
                            ],
                        ],
                    ]
        ];
    }

    public function actionData($id_painel, $id_consulta, $square, $own = FALSE, $previous = FALSE) {
        $this->layout = FALSE;

        if (!$id_consulta) {
            return $this->renderAjax("/_graficos/empty");
        }

        $painel = Painel::findOne($id_painel);
        $model = $this->findModel($id_consulta);

        if (!$model->is_ativo || $model->is_excluido || !$model->indicador->is_ativo || $model->indicador->is_excluido) {
            return $this->renderAjax("/_graficos/empty");
        }

        $data_conected = [];

        if($painel->data)
        {
            foreach($painel->data as $data)
            {
                if(isset($data['conected']) && $data['conected'] == "1")
                {
                    $consulta = Consulta::findOne($data['consulta']);
                    $data_conected[$consulta->id_indicador][] = $consulta->id;
                }
            }
        }

        if ($post = Yii::$app->request->post()) {
            $index = $post['index'];

            $field = null;
            if(isset($post['field']))
            {
                $token = PainelMagic::unserializeToken($post['token']);
                $field = $post['field'];
                $token['filtro'][$field] = isset($post['filtro']) ? $post['filtro'] : null;
                $token = PainelMagic::serializeData($token);
            }
            else
            {
                $token = $post['token'];
            }

            $painel_filtro = (isset($post['data'])) ? $post['data'] : null;

            $data = PainelMagic::getData($model, $index, (isset($post['filtro']) && $own) ? $post['filtro'] : null, $token, $previous,  null, $own, $field, $painel_filtro);

            if ($data['error']) {
                return $this->render("/_graficos/error");
            } else {
                return $this->renderAjax("/_graficos/_general/content", compact('index', 'data', 'model', 'square', 'data_conected', 'painel'));
            }
        }
    }

    public function actionView($id_painel, $id_consulta, $square) {
        $this->layout = false;

        if (!$id_consulta) {
            return $this->renderAjax("/_graficos/empty");
        }

        $painel = Painel::findOne($id_painel);
        $model = $this->findModel($id_consulta);

        if (!$model->is_ativo || $model->is_excluido || !$model->indicador->is_ativo || $model->indicador->is_excluido) {
            return $this->renderAjax("/_graficos/empty");
        }

        $data_conected = [];

        if($painel->data)
        {
            foreach($painel->data as $data)
            {
                if(isset($data['conected']) && $data['conected'] == "1")
                {
                    $consulta = Consulta::findOne($data['consulta']);
                    $data_conected[$consulta->id_indicador][] = $consulta->id;
                }
            }
        }

        $index = 0;
        $data = PainelMagic::getData($model, 0, null, "", FALSE,  null);

        if ($data['error']) {
            return $this->render("/_graficos/error");
        } else {
            return $this->renderAjax("/content/view", compact('index', 'data', 'model', 'square', 'data_conected', 'painel'));
        }
    }

    protected function findModel($id) {
        if (($model = Consulta::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'controller.mensagem_erro_404'));
    }

}
