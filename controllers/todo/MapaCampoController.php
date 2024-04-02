<?php

namespace app\controllers;

use Yii;
use app\models\Mapa;
use app\models\MapaCampo;
use app\models\searches\MapaCampoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use app\models\IndicadorCampo;

class MapaCampoController extends BaseController {

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
                                'actions' => ['load-campos'],
                                'allow' => true,
                                'roles' => ['@']
                            ],
                        ],
                    ]
        ];
    }

    public function actionIndex($id) {
        $mapa = Mapa::find()->andWhere(['id' => $id, 'is_excluido' => FALSE])->one();

        if (!$mapa) {
            throw new NotFoundHttpException(\Yii::t('app', 'controller.mensagem_erro_404'));
        }

        $searchModel = new MapaCampoSearch();
        $dataProvider = $searchModel->search($id, Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'mapa' => $mapa
        ]);
    }

    public function actionCreate($id_mapa) {
        $model = new MapaCampo();
        $model->id_mapa = $id_mapa;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('toast-success', 'Campo cadastrado com sucesso.');

            return $this->redirect(['index', 'id' => $model->id_mapa]);
        }

        return $this->render('create', [
                    'model' => $model
        ]);
    }

    public function actionUpdate($id_mapa, $id) {
        $model = $this->findModel($id_mapa, $id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('toast-success', 'Campo alterado com sucesso.');

            return $this->redirect(['index', 'id' => $model->id_mapa]);
        }

        return $this->render('update', [
                    'model' => $model
        ]);
    }

    public function actionDelete($id_mapa, $id) {
        $model = $this->findModel($id_mapa, $id);

        $model->is_excluido = TRUE;

        if ($model->save()) {
            \Yii::$app->getSession()->setFlash('toast-success', 'Campo removido com sucesso.');
        }

        return true;
    }

    public function actionLoadCampos($id) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $query = new \yii\db\Query();

        $data = $query->select('id AS id, nome AS text')
                        ->from('bpbi_indicador_campo')
                        ->andWhere([
                            'is_ativo' => TRUE,
                            'is_excluido' => FALSE,
                            'id_indicador' => $id
                        ])->orderBy('nome ASC')->all();

        return array_values($data);
    }

    protected function findModel($id_mapa, $id) {
        $model = MapaCampo::find()->andWhere(['id_mapa' => $id_mapa, 'id' => $id])->one();

        if ($model !== null && !$model->is_excluido) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'controller.mensagem_erro_404'));
    }

}
