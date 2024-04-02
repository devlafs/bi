<?php

namespace app\controllers;

use app\magic\FiltroMagic;
use app\models\form\ImageForm;
use app\models\IndicadorCampo;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\Painel;
use yii\web\NotFoundHttpException;
use app\magic\MenuMagic;
use app\models\UltimaTelaAcesso;
use app\models\Consulta;
use app\models\PermissaoPainel;
use app\models\PainelPermissao;
use yii\web\Response;

class PainelController extends BaseController {

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
                                    'alterar', //painel-alterar
                                    'permission-painel', //painel-alterar
                                    'save-config-painel', //painel-alterar
                                ],
                                'allow' => true,
                                'roles' => ['@'],
                                'matchCallback' => function ($rule, $action) {
                                    return \Yii::$app->permissaoGeral->can($this->id, $this->action->id);
                                },
                            ],
                            [
                                'actions' => ['visualizar'], //painel-visualizar
                                'allow' => true,
                                'roles' => ['@'],
                                'matchCallback' => function ($rule, $action) {
                                    $request = Yii::$app->request;
                                    $painel_id = $request->get('id');

                                    return \Yii::$app->permissaoGeral->can($this->id, $this->action->id) &&
                                            \Yii::$app->permissaoPainel->can($painel_id, $this->id, $this->action->id);
                                },
                            ],
                            [
                                'actions' =>
                                [
                                    'upload-image', //painel-visualizar
                                    'and-filter-update', //painel-alterar
                                    'or-filter-update', //painel-alterar
                                    'get-field-update', //painel-alterar
                                    'get-type-update', //painel-alterar
                                    'save-filter-update', //painel-alterar
                                ],
                                'allow' => true,
                                'roles' => ['@']
                            ],
                        ],
                    ]
        ];
    }

    public function actionVisualizar($id) {
        $this->layout = '//painel-view/main';

        $model = $this->findModel($id);
        $this->salvarHistorico($id);

        return $this->render('/painel-view/view', compact("model"));
    }

    public function actionAlterar($id) {
        $this->layout = '//painel-update/main';

        $model = $this->findModel($id);

        $consultas = Consulta::find()->joinWith(['indicador'])->andWhere([
                    'bpbi_consulta.is_ativo' => TRUE,
                    'bpbi_consulta.is_excluido' => FALSE,
                    'bpbi_indicador.is_ativo' => TRUE,
                    'bpbi_indicador.is_excluido' => FALSE,
                ])->orderBy('bpbi_consulta.nome ASC')->all();

        $select = $data_consulta = [];

        foreach ($consultas as $consulta) {
            $select[$consulta->id] = mb_strtoupper(trim($consulta->getPathName(FALSE)));
            $data_consulta[$consulta->id] = array_merge($consulta->attributes, ['chart' => $consulta->getChart()], ['fullname' => mb_strtoupper(trim($consulta->getPathName(FALSE)))]);
        }

        asort($select);

        $select = json_encode($select);

        if ($data = Yii::$app->getRequest()->getBodyParams()) {
            $model->nome = $data['nome'];
            $model->data = json_decode($data['data']);
            $model->save();

            MenuMagic::updateMenus();
            return true;
        }

        return $this->render('/painel-update/update', compact('model', 'select', 'data_consulta'));
    }

    public function actionUploadImage()
    {
        if (isset($_POST))
        {
            $files = ImageForm::saveTempAttachments($_FILES);
            Yii::$app->response->format = trim(Response::FORMAT_JSON);

            if($files)
            {
                return $files[0]["fileName"];
            }

            return ['file'];
        }
    }

    public function actionPermissionPainel($painel_id, $perfil_id, $permissao_id, $state) {
        $permissao = PermissaoPainel::findOne($permissao_id);

        if ($permissao->gerenciador == 'visualizar' && $state == 'false') {
            PainelPermissao::deleteAll([
                'id_painel' => $painel_id,
                'id_perfil' => $perfil_id
            ]);
        } else {
            PainelPermissao::deleteAll([
                'id_painel' => $painel_id,
                'id_perfil' => $perfil_id,
                'id_permissao' => $permissao_id
            ]);
        }

        if ($state == 'true') {
            $model = new PainelPermissao();
            $model->id_painel = $painel_id;
            $model->id_perfil = $perfil_id;
            $model->id_permissao = $permissao_id;
            $model->save();
        }
    }

    public function actionSaveConfigPainel($id) {
        $this->layout = FALSE;
        $model = Painel::findOne($id);

        $request = \Yii::$app->getRequest();

        if ($request->isPost && $model->load($request->post()) && $model->save()) {
            return 1;
        }
    }

    protected function salvarHistorico($id_painel) {
        $user_id = Yii::$app->user->identity->id;

        $model = new UltimaTelaAcesso();
        $model->id_usuario = $user_id;
        $model->view = UltimaTelaAcesso::VIEW_PAINEL;
        $model->id_painel = $id_painel;
        $model->index = 0;
        $model->id_consulta = null;
        $model->token = null;
        $model->save();
    }

    public function actionAndFilterUpdate($id, $index) {
        $this->layout = FALSE;
        $model = Painel::findOne($id);

        return $this->renderAjax('//painel-update/_layouts/_partials/_and', [
            'model' => $model,
            'index' => $index,
            'condicao' => null
        ]);
    }

    public function actionOrFilterUpdate($id, $indexAnd, $indexOr) {
        $this->layout = FALSE;
        $model = Painel::findOne($id);

        return $this->renderAjax('//painel-update/_layouts/_partials/_or', [
            'model' => $model,
            'indexAnd' => $indexAnd,
            'indexOr' => $indexOr,
            'data' => null
        ]);
    }

    public function actionGetFieldUpdate($id = null, $and, $or) {
        $this->layout = FALSE;
        $argumentos = [];

        if ($id) {
            $argumentos = IndicadorCampo::find()
                ->andWhere(['id_indicador' => $id, 'is_ativo' => TRUE, 'is_excluido' => FALSE])
                ->orderBy('nome ASC')->all();
        }

        return $this->renderAjax('//painel-update/_layouts/_partials/_field', [
            'argumentos' => $argumentos,
            'indexAnd' => $and,
            'indexOr' => $or
        ]);
    }

    public function actionGetTypeUpdate($id = null, $and, $or) {
        $this->layout = FALSE;
        $list = [];

        if ($id) {
            $model = IndicadorCampo::findOne($id);
            $list = FiltroMagic::getPainelListByType($model->tipo);
        }

        return $this->renderAjax('//painel-update/_layouts/_partials/_type', [
            'list' => $list,
            'indexAnd' => $and,
            'indexOr' => $or
        ]);
    }

    public function actionSaveFilterUpdate($id) {
        $this->layout = FALSE;
        $model = Painel::findOne($id);
        $request = \Yii::$app->getRequest();

        if ($request->isPost) {
            $model->aplicaFiltro($request->post());
        }
    }

    protected function findModel($id) {
        if (($model = Painel::find()->andWhere([
                    'id' => $id,
                    'is_ativo' => TRUE,
                    'is_excluido' => FALSE
                ])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'controller.mensagem_erro_404'));
    }

}
