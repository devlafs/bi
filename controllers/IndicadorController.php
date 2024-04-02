<?php

namespace app\controllers;

use Yii;
use app\models\Indicador;
use app\models\searches\IndicadorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use app\magic\MenuMagic;

class IndicadorController extends BaseController {

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
                                    'index', //indicador-visualizar
                                    'view', //indicador-visualizar
                                    'update', //indicador-alterar
                                    'create', //indicador-cadastrar
                                    'status', //indicador-status
                                    'carga', //indicador-carga
                                    'delete' //indicador-excluir
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
        $searchModel = new IndicadorSearch();
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
        $model = new Indicador();
        $model->setScenario('create');
        $model->tipo = 'database';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_cadastrado', [
                'model' => Yii::t('app', 'geral.indicador')
            ]));

            return (Yii::$app->permissaoGeral->can('indicador-campo', 'index')) ?
                    $this->redirect(['/indicador-campo/index', 'id' => $model->id]) :
                    $this->redirect(['index']);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_alterado', [
                'model' => Yii::t('app', 'geral.indicador')
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
            MenuMagic::updateMenus();
            \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_removido', [
                'model' => Yii::t('app', 'geral.indicador')
            ]));
        }

        return true;
    }

    public function actionStatus($id) {
        $model = $this->findModel($id);
        $status = !$model->is_ativo;
        $model->is_ativo = $status;

        if ($model->save()) {
            \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_alterado', [
                'model' => Yii::t('app', 'geral.status')
            ]));
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    public function actionCarga($id) {
        $model = $this->findModel($id);
        $model->executed_at = null;
        $model->save();
        \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.carga_agendada_sucesso'));
        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    protected function findModel($id) {
        if (($model = Indicador::find()->andWhere(['id' => $id, 'is_excluido' => FALSE])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'controller.mensagem_erro_404'));
    }

}
