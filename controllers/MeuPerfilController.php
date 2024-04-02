<?php

namespace app\controllers;

use Yii;
use app\models\AdminUsuario;
use yii\web\Controller;
use yii\filters\AccessControl;

class MeuPerfilController extends BaseController {

    public function behaviors() {
        return
                [
                    'access' =>
                    [
                        'class' => AccessControl::className(),
                        'rules' =>
                        [
                            [
                                'actions' => ['index'], //@
                                'allow' => true,
                                'roles' => ['@']
                            ],
                        ],
                    ]
        ];
    }

    public function actionIndex() {
        $user_id = Yii::$app->user->identity->id;
        $model = AdminUsuario::findOne($user_id);
        $model->scenario = 'update';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_alterado', [
                'model' => Yii::t('app', 'geral.perfil')
            ]));

            return $this->redirect(['index']);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

}
