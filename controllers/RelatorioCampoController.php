<?php

namespace app\controllers;

use Yii;
use app\models\Relatorio;
use app\models\RelatorioCampo;
use app\models\searches\RelatorioCampoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class RelatorioCampoController extends BaseController {

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
                                    'index', //campo-visualizar
                                    'generate', //campo-cadastrar
                                    'update', //campo-alterar
                                    'delete' //campo-excluir
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

    public function actionIndex($id) {
        $relatorio = Relatorio::find()->andWhere(['id' => $id, 'is_excluido' => FALSE])->one();

        if (!$relatorio) {
            throw new NotFoundHttpException(\Yii::t('app', 'controller.mensagem_erro_404'));
        }

        $searchModel = new RelatorioCampoSearch();
        $dataProvider = $searchModel->search($id, Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'relatorio' => $relatorio
        ]);
    }

    public function actionGenerate($id_relatorio)
    {
        $relatorio = $this->findRelatorio($id_relatorio);
        $return = $relatorio->generateFields();

        if ($return['status'] == '1')
        {
            \Yii::$app->getSession()->setFlash('toast-success', $return['msg']);
        }
        else
        {
            \Yii::$app->getSession()->setFlash('toast-error', $return['msg']);
        }

        return $this->redirect(['index', 'id' => $id_relatorio]);
    }

    public function actionUpdate($id_relatorio, $id) {
        $model = $this->findModel($id_relatorio, $id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_alterado', [
                'model' => Yii::t('app', 'geral.campo')
            ]));

            return $this->redirect(['update', 'id_relatorio' => $model->id_relatorio, 'id' => $model->id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    public function actionDelete($id_relatorio, $id) {
        $model = $this->findModel($id_relatorio, $id);

        $model->is_excluido = TRUE;

        if ($model->save()) {
            \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_removido', [
                'model' => Yii::t('app', 'geral.campo')
            ]));
        }

        return true;
    }

    protected function findModel($id_relatorio, $id) {
        $model = RelatorioCampo::find()->andWhere(['id_relatorio' => $id_relatorio, 'id' => $id])->one();

        if ($model !== null && !$model->is_excluido) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'controller.mensagem_erro_404'));
    }

    protected function findRelatorio($id)
    {
        if (($model = Relatorio::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
