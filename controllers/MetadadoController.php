<?php

namespace app\controllers;

use Yii;
use app\models\Metadado;
use app\models\searches\MetadadoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\db\Expression;

class MetadadoController extends BaseController {

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
                                            'index', //metadado-visualizar
                                            'view', //metadado-visualizar
                                            'create', //metadado-cadastrar
                                            'update', //metadado-alterar
                                            'delete' //metadado-excluir
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
        $searchModel = new MetadadoSearch();
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
        $model = new Metadado();

        if ($model->load(Yii::$app->request->post())) {

            $model->file = UploadedFile::getInstance($model, 'file');
            $caminho = \Yii::$app->security
                    ->generateRandomString().'.'.$model->file->extension;

            if ($model->validate() && $model->file->saveAs(dirname(__FILE__) . '/../web/uploads/' . $caminho)) {
                $model->caminho = $caminho;
                $model->executed_at =  new Expression('NOW()');
            }

            if ($model->save()) {
                \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_cadastrado', [
                    'model' => Yii::t('app', 'geral.metadado')
                ]));

                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->validate() && $model->file && $model->file->saveAs(dirname(__FILE__) . '/../web/uploads/' . $model->caminho)) {
                $model->executed_at =  new Expression('NOW()');
            }

            if ($model->save()) {
                \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_alterado', [
                    'model' => Yii::t('app', 'geral.metadado')
                ]));

                return $this->redirect(['index']);
            }
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
            \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_removido', [
                'model' => Yii::t('app', 'geral.metadado')
            ]));
        }

        return true;
    }

    protected function findModel($id) {
        if (($model = Metadado::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'controller.mensagem_erro_404'));
    }

}
