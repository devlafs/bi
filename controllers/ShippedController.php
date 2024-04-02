<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\magic\SqlMagic;
use app\models\Consulta;
use yii\web\NotFoundHttpException;

class ShippedController extends BaseController {

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

    public function actionData($id, $color = null, $previous = FALSE) {
        $this->layout = FALSE;

        if (!$id) {
            return $this->renderAjax("/_graficos/empty");
        }

        $model = $this->findModel($id);

        if (!$model->is_ativo || $model->is_excluido || !$model->indicador->is_ativo || $model->indicador->is_excluido) {
            return $this->renderAjax("/_graficos/empty");
        }

        if ($post = Yii::$app->request->post()) {
            $index = $post['index'];
            $data = SqlMagic::getData($model, $index, isset($post['filtro']) ? $post['filtro'] : null, $post['token'], $previous, 100000000, TRUE);

            if ($data['error']) {
                return $this->render("/_graficos/error");
            } else {
                return $this->renderAjax("/_graficos/_general/pshipped", compact('index', 'data', 'model', 'color'));
            }
        }
    }

    public function actionView($id, $color = null) {
        $this->layout = '//content/main';

        if (!$id) {
            return $this->renderAjax("/_graficos/empty");
        }

        $model = $this->findModel($id);

        if (!$model->is_ativo || $model->is_excluido || !$model->indicador->is_ativo || $model->indicador->is_excluido) {
            return $this->renderAjax("/_graficos/empty");
        }

        $index = 0;
        $data = SqlMagic::getData($model, 0, null, "", FALSE, 100000000, TRUE);

        if ($data['error']) {
            return $this->render("/_graficos/error");
        } else {
            return $this->render("/shipped/view", compact('index', 'data', 'model', 'color'));
        }
    }

    protected function findModel($id) {
        if (($model = Consulta::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'controller.mensagem_erro_404'));
    }

}
