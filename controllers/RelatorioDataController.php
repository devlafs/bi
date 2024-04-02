<?php

namespace app\controllers;

use app\magic\FiltroMagic;
use app\magic\MenuMagic;
use app\magic\SqlMagic;
use app\models\PermissaoRelatorio;
use app\models\Relatorio;
use app\models\RelatorioCampo;
use app\models\RelatorioData;
use app\models\RelatorioDataItem;
use app\models\RelatorioPermissao;
use app\models\searches\RelatorioDinamicoSearch;
use app\models\searches\RelatorioSearch;
use app\models\UltimaTelaAcesso;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class RelatorioDataController extends Controller
{
    public $contemFiltro = TRUE;

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
                                            'visualizar', //relatorio-visualizar
                                            'alterar', //relatorio-alterar
                                            'open-filter-update',
                                            'get-type-update',
                                            'and-filter-update',
                                            'or-filter-update',
                                            'save-filter-update',
                                            'save-config-relatorio',
                                            'permission-relatorio',
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

    public function actionVisualizar($id, $sql = false)
    {
        $this->layout = '//relatorio-view/main';

        $model = $this->findModel($id);
        $campos = $this->findCampos($id);
        $searchModel = new RelatorioDinamicoSearch();
        $searchModel->relatorio = $model;
        $searchModel->campos = $campos;
        $searchModel->filtros = $model->condicao;
        $searchModel->getAttributesDynamicFields();

        foreach($model->condicao as $fpadrao)
        {
            if(isset($fpadrao[1]) && isset($fpadrao[1]['value']) && $fpadrao[1]['value'] != '')
            {
                $filtro = 'dynamic_' . $fpadrao[1]['field'];
                $searchModel[$filtro] = $fpadrao[1]['value'];
            }
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $sql);

        $this->salvarHistorico($id);

        return $this->render('/relatorio-view/view', [
            'model' => $model,
            'campos' => $campos,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAlterar($id, $sqlMode = FALSE) {
        $this->layout = '//consulta-update/main';

        $model = $this->findModel($id);

        if ($data = Yii::$app->getRequest()->getBodyParams()) {
            $model->saveData($data);
            MenuMagic::updateMenus();
            return true;
        }

        return $this->render('/relatorio-update/update', compact('model', 'sqlMode'));
    }

    protected function findModel($id)
    {
        if (($model = RelatorioData::findOne($id)) !== null)
        {
            if($model->is_ativo && !$model->is_excluido)
            {
                $user = Yii::$app->user->identity;

                if($user->perfil->is_admin)
                {
                    return $model;
                }

                $possui_permissao = RelatorioPermissao::find()->andWhere([
                    'id_perfil' => $user->perfil->id,
                    'id_relatorio_data' => $id,
                    'is_ativo' => true,
                    'is_excluido' => false
                ])->exists();

                if($possui_permissao)
                {
                    return $model;
                }
            }
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findCampos($id)
    {
        $argumentos = RelatorioDataItem::find()->andWhere(['id_relatorio_data'=> $id, 'is_ativo' => 1, 'is_excluido' => 0, 'parametro' => 'argumento'])
            ->orderBy('ordem ASC')->all();

        $valores = RelatorioDataItem::find()->andWhere(['id_relatorio_data'=> $id, 'is_ativo' => 1, 'is_excluido' => 0, 'parametro' => 'valor'])
            ->orderBy('ordem ASC')->all();

        $data = [];

        if($argumentos)
        {
            foreach ($argumentos as $argumento)
            {
                $data['x'][] = ['campo' => $argumento->campo, 'id' => $argumento->id];
            }

        }

        if($valores)
        {
            foreach ($valores as $valor)
            {
                $data['y'] = ['campo' => $valor->campo, 'id' => $argumento->id];
            }

        }

        return $data;
    }

    protected function findFiltros($id)
    {
        if (($models = RelatorioFiltro::find()->andWhere(['id_relatorio'=> $id, 'is_ativo' => 1, 'is_excluido' => 0])
                ->orderBy('ordem ASC')->all()) !== null) {
            return $models;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function salvarHistorico($id_relatorio)
    {
        $user_id = Yii::$app->user->identity->id;

        $model = new UltimaTelaAcesso();
        $model->id_usuario = $user_id;
        $model->view = UltimaTelaAcesso::VIEW_RELATORIO;
        $model->id_relatorio_data = $id_relatorio;
        $model->id_painel = null;
        $model->id_consulta = null;
        $model->index = 0;
        $model->token = null;
        $model->save();
    }

    public function actionOpenFilterUpdate($id) {
        $this->layout = FALSE;
        $model = RelatorioData::findOne($id);

        return $this->renderAjax('//relatorio-update/_layouts/_filter_update', [
            'model' => $model
        ]);
    }

    public function actionAndFilterUpdate($id, $index) {
        $this->layout = FALSE;
        $model = RelatorioData::findOne($id);

        return $this->renderAjax('//relatorio-update/_layouts/_partials/_and', [
            'model' => $model,
            'index' => $index,
            'condicao' => null
        ]);
    }

    public function actionGetTypeUpdate($id = null, $and, $or) {
        $this->layout = FALSE;
        $list = [];

        if ($id) {
            $model = RelatorioCampo::findOne($id);
            $list = FiltroMagic::getRelatorioListByType($model->tipo);
        }

        return $this->renderAjax('//relatorio-update/_layouts/_partials/_type', [
            'list' => $list,
            'indexAnd' => $and,
            'indexOr' => $or
        ]);
    }

    public function actionSaveFilterUpdate($id) {
        $this->layout = FALSE;
        $model = RelatorioData::findOne($id);
        $request = \Yii::$app->getRequest();

        if ($request->isPost) {
            $model->aplicaFiltro($request->post());
        }
    }

    public function actionSaveConfigRelatorio($id) {
        $this->layout = FALSE;
        $model = RelatorioData::findOne($id);

        $request = \Yii::$app->getRequest();

        if ($request->isPost && $model->load($request->post()) && $model->save()) {
            return 1;
        }
    }

    public function actionPermissionRelatorio($relatorio_id, $perfil_id, $permissao_id, $state) {
        $permissao = PermissaoRelatorio::findOne($permissao_id);

        if ($permissao->gerenciador == 'visualizar' && $state == 'false') {
            RelatorioPermissao::deleteAll([
                'id_relatorio_data' => $relatorio_id,
                'id_perfil' => $perfil_id
            ]);
        } else {
            RelatorioPermissao::deleteAll([
                'id_relatorio_data' => $relatorio_id,
                'id_perfil' => $perfil_id,
                'id_permissao' => $permissao_id
            ]);
        }

        if ($state == 'true') {
            $model = new RelatorioPermissao();
            $model->id_relatorio_data = $relatorio_id;
            $model->id_perfil = $perfil_id;
            $model->id_permissao = $permissao_id;
            $model->save();
        }
    }
}
