<?php

namespace app\controllers;

use Yii;
use app\models\Mapa;
use app\models\searches\MapaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

class MapaController extends BaseController {

    public function behaviors() {
        return
                [
                    'access' =>
                    [
                        'class' => AccessControl::className(),
                        'rules' =>
                        [
                            [
                                'actions' => ['index', 'create', 'update', 'delete'],
                                'allow' => true,
                                'roles' => ['@'],
                                'matchCallback' => function ($rule, $action) {
                                    return \Yii::$app->permissaoGeral->can($this->id, $this->action->id);
                                },
                            ],
                            [
                                'actions' => ['view'],
                                'allow' => true,
                            ],
                        ],
                    ]
        ];
    }

    public function actionIndex() {
        $searchModel = new MapaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id, $device = 0) {

        $this->layout = '//url-publica/main';

        return $this->render('view', [
            'model' => $this->findModel($id),
            'device' => $device
        ]);
    }

    public function actionCreate() {
        $model = new Mapa();
        $model->corfundo_ativo = '#007EC3';
        $model->corfundo_inativo = '#eceeef';
        $model->corborda = '#004c89';

        if (Yii::$app->request->isPost) {
//            $model->file = UploadedFile::getInstance($model, 'file');
//            $filename = 'maps/' . $model->file->baseName . '_' . time() . '.' . $model->file->extension;
//            $model->file->saveAs($filename);

            if ($model->load(Yii::$app->request->post())) {
//                $model->file = $filename;

                if ($model->save()) {
                    \Yii::$app->getSession()->setFlash('toast-success', 'Mapa cadastrado com sucesso.');
                    return $this->redirect(['index']);
                }
            } else {
                var_dump($model->getErrors());
                die;
            }
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            if ($model->file) {
                $model->file = UploadedFile::getInstance($model, 'file');
                $filename = 'maps/' . $model->file->baseName . '_' . time() . '.' . $model->file->extension;
                $model->file->saveAs($filename);
                $model->file = $filename;
            }

            if ($model->load(Yii::$app->request->post())) {
                if ($model->save()) {
                    \Yii::$app->getSession()->setFlash('toast-success', 'Mapa cadastrado com sucesso.');
                    return $this->redirect(['index']);
                }
            } else {
                var_dump($model->getErrors());
                die;
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('toast-success', 'Mapa alterado com sucesso.');
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
            \Yii::$app->getSession()->setFlash('toast-success', 'Mapa removido com sucesso.');
        }

        return true;
    }

    protected function findModel($id) {
        if (($model = Mapa::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'controller.mensagem_erro_404'));
    }

}
