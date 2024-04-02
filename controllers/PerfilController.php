<?php

namespace app\controllers;

use app\magic\MenuPerfilMagic;
use app\models\PerfilComplemento;
use Yii;
use app\models\AdminPerfil;
use app\models\searches\AdminPerfilSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use app\models\PermissaoGeral;

class PerfilController extends BaseController {

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
                                    'index', //perfil-visualizar
                                    'view', //perfil-visualizar
                                    'create', //perfil-cadastrar
                                    'update', //perfil-alterar
                                    'delete' //perfil-excluir
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
        $searchModel = new AdminPerfilSearch();
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
        $model = new AdminPerfil();
        $complemento = new PerfilComplemento();

        $modelPermissoes = PermissaoGeral::find()->andWhere(['is_ativo' => TRUE,
                    'is_excluido' => FALSE])->orderBy('gerenciador ASC')->all();

        $model->permissoes = [];

        foreach ($modelPermissoes as $modelPermissao) {
            $model->permissoes[$modelPermissao->gerenciador][$modelPermissao->id] = [
                        'attributes' => $modelPermissao->attributes,
                        'value' => FALSE
            ];
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $complemento->id_perfil = $model->id;
            if($complemento->load(Yii::$app->request->post()) && $complemento->save()) {
                \app\components\PermissaoGeral::setProfilePermissions();
                \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_cadastrado', [
                    'model' => Yii::t('app', 'geral.perfil')
                ]));

                return $this->redirect(['index']);
            }
            else
            {
                return $this->redirect([
                    'update',
                    'id' => $model->id
                ]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'complemento' => $complemento,
            'menuConsulta' => MenuPerfilMagic::getMenuConsulta(null),
            'menuRelatorio' => MenuPerfilMagic::getMenuRelatorio(null),
            'menuPainel' => MenuPerfilMagic::getMenuPainel(null)
        ]);
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);

        $complemento = PerfilComplemento::find()->andWhere(['id_perfil' => $id])->one();

        if(!$complemento)
        {
            $complemento = new PerfilComplemento();
            $complemento->id_perfil = $id;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save() && $complemento->load(Yii::$app->request->post()) && $complemento->save()) {
            \app\components\PermissaoGeral::setProfilePermissions();
            \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_alterado', [
                'model' => Yii::t('app', 'geral.perfil')
            ]));

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'complemento' => $complemento,
            'menuConsulta' => MenuPerfilMagic::getMenuConsulta($model),
            'menuRelatorio' => MenuPerfilMagic::getMenuRelatorio($model),
            'menuPainel' => MenuPerfilMagic::getMenuPainel($model)
        ]);
    }

    public function actionDelete($id) {
        $model = $this->findModel($id);
        $status = !$model->is_excluido;
        $model->is_excluido = $status;

        if ($model->save()) {
            \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_removido', [
                'model' => Yii::t('app', 'geral.perfil')
            ]));
        }

        return true;
    }

    protected function findModel($id) {
        if (($model = AdminPerfil::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'controller.mensagem_erro_404'));
    }

}
