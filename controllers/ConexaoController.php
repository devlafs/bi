<?php

namespace app\controllers;

use Yii;
use app\models\Conexao;
use app\models\searches\ConexaoSearch;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class ConexaoController extends BaseController {

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
                                    'index', //conexao-visualizar
                                    'view', //conexao-visualizar
                                    'create', //conexao-cadastrar
                                    'update', //conexao-alterar
                                    'delete' //conexao-excluir
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
        $searchModel = new ConexaoSearch();
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
        $model = new Conexao();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_cadastrada', [
                'model' => Yii::t('app', 'geral.conexao')
            ]));

            return $this->redirect(['index']);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_alterada', [
                'model' => Yii::t('app', 'geral.conexao')
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

        if ($model->save()) {
            \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_removida', [
                'model' => Yii::t('app', 'geral.conexao')
            ]));
        }

        return true;
    }

    protected function findModel($id) {
        if (($model = Conexao::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'controller.mensagem_erro_404'));
    }

}
