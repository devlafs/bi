<?php

namespace app\controllers;

use app\models\AdminUsuario;
use app\models\Painel;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class PainelEmbarcadoController extends BaseController {

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

    public function actionV($c, $u, $p, $b = null) {
        $this->layout = '//url-publica/main';
        $model = $this->findModel($c);

        if (!$model) {
            return $this->render("_404");
        }

        if (!$this->validateUser($u, $p)) {
            return $this->render("_403");
        }

        return $this->render('_view-painel', [
            'model' => $model,
            'usuario_id' => $u,
            'color' => $b
        ]);
    }

    protected function findModel($id) {
        if (($model = Painel::find()->andWhere([
                'id' => $id,
                'is_ativo' => TRUE,
                'is_excluido' => FALSE
            ])->one()) !== null) {
            return $model;
        }

        return null;
    }
}
