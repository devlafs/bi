<?php

namespace app\controllers;

use app\models\AdminUsuario;
use app\models\Consulta;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\magic\SqlMagic;

class ConsultaEmbarcadaController extends BaseController {

    public function behaviors() {
        return
        [
            'access' =>
            [
                'class' => AccessControl::className(),
                'rules' =>
                [
                    [
                        'actions' => ['v'], //all
                        'allow' => true,
                    ],
                ],
            ]
        ];
    }

    private function validateUser($u, $p)
    {
        $user = AdminUsuario::findOne($u);
        return $user->senha == base64_decode($p);
    }

    public function actionV($c, $u, $p, $b = null, $previous = FALSE) {
        $this->layout = '//url-publica/main';
        $model = $this->findModel($c);

        if (!$model) {
            return $this->render("_404");
        }

        if (!$this->validateUser($u, $p)) {
            return $this->render("_403");
        }

        if ($post = Yii::$app->request->post()) {
            $index = $post['index'];
            $data = SqlMagic::getData($model, $index, isset($post['filtro']) ? $post['filtro'] : null, $post['token'], $previous, 100000000, TRUE, $u);

            if ($data['error']) {
                return $this->renderAjax("/_graficos/error", compact('index', 'data', 'model'));
            } else {
                return $this->renderAjax("/_graficos/_general/cshipped", compact('index', 'data', 'model'));
            }
        }
        else
        {
            $data = SqlMagic::getData($model, 0, null, '', FALSE, 100000000, true, $u);

            if ($data['error']) {
                return $this->render("_error", compact('model'));
            } else {
                return $this->render('_view-consulta', [
                    'model' => $model,
                    'index' => 0,
                    'data' => $data,
                    'action' => 'share',
                    'color' => $b
                ]);
            }
        }
    }

    protected function findModel($id) {
        if (($model = Consulta::find()->joinWith('indicador')->andWhere([
                'bpbi_consulta.id' => $id,
                'bpbi_consulta.is_ativo' => TRUE,
                'bpbi_consulta.is_excluido' => FALSE,
                'bpbi_indicador.is_ativo' => TRUE,
                'bpbi_indicador.is_excluido' => FALSE,
            ])->one()) !== null) {
            return $model;
        }

        return null;
    }
}
