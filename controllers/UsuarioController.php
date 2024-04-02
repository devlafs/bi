<?php

namespace app\controllers;

use Yii;
use app\models\AdminUsuario;
use app\models\searches\AdminUsuarioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class UsuarioController extends BaseController {

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
                                    'index', //usuario-visualizar
                                    'view', //usuario-visualizar
                                    'create', //usuario-cadastrar
                                    'update', //usuario-alterar
                                    'delete', //usuario-excluir
                                    'password' //usuario-senha
                                ],
                                'allow' => true,
                                'roles' => ['@'],
                                'matchCallback' => function ($rule, $action) {
                                    return \Yii::$app->permissaoGeral->can($this->id, $this->action->id);
                                },
                            ],
                        ],
                    ]
        ];
    }

    public function actionIndex() {
        $searchModel = new AdminUsuarioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate() {
        $model = new AdminUsuario();
        $model->scenario = 'create';
        $model->status = 'Ativo';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_cadastrado', [
                'model' => Yii::t('app', 'geral.usuario')
            ]));

            return $this->redirect(['index']);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $model->scenario = 'update';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_alterado', [
                'model' => Yii::t('app', 'geral.usuario')
            ]));

            return $this->redirect(['index']);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    public function actionDelete($id) {
        $model = $this->findModel($id);
        $status = !$model->is_excluido;
        $model->is_excluido = $status;
        $model->acesso_bi = FALSE;
        $model->status = 'Excluido';

        if ($model->save()) {
            \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_removido', [
                'model' => Yii::t('app', 'geral.usuario')
            ]));
        }

        return true;
    }

    public function actionPassword($id) {
        $model = $this->findModel($id);

        if ($model->sendPassword()) {
            \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_enviada', [
                'model' => Yii::t('app', 'geral.senha')
            ]));
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    protected function findModel($id) {
        if (($model = AdminUsuario::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'controller.mensagem_erro_404'));
    }

}
