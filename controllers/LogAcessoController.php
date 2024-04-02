<?php

namespace app\controllers;

use app\models\RbaAcesso;
use app\models\searches\LogAcessoSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class LogAcessoController extends BaseController {

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
        $searchModel = new LogAcessoSearch();
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

    protected function findModel($id) {
        if (($model = RbaAcesso::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(\Yii::t('app', 'controller.mensagem_erro_404'));
        }
    }

}
