<?php

namespace app\controllers;

use app\models\LogItem;
use app\models\searches\LogSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class LogController extends BaseController {

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
                                    'index', //log-visualizar
                                    'view', //log-visualizar
                                    'restore'
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
        $searchModel = new LogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRestore($id) {
        $model = $this->findModel($id);
        $class = 'app\\models\\' . $model->relatedObjectType;
        $instance = $class::findOne($model->relatedObjectId);

        if($instance && (!$instance->is_ativo || $instance->is_excluido))
        {
            $instance->is_ativo = 1;
            $instance->is_excluido = 0;
            if($instance->save(false, ['is_ativo', 'is_excluido']))
            {
                \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.objeto_restaurado_sucesso'));
            }
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    protected function findModel($id) {
        if (($model = LogItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(\Yii::t('app', 'controller.mensagem_erro_404'));
        }
    }

}
