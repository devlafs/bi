<?php

namespace app\controllers;

use app\models\ConsultaItemCor;
use Yii;
use app\models\Consulta;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use app\magic\SqlMagic;
use app\magic\PreviewMagic;
use app\models\UltimaTelaAcesso;
use app\models\ConsultaPermissao;
use app\models\PermissaoConsulta;
use app\models\IndicadorCampo;
use app\magic\FiltroMagic;
use app\models\ConsultaItemConfiguracao;
use yii\db\Query;
use \yii\db\Expression;
use app\magic\MenuMagic;
use kartik\mpdf\Pdf;
use yii2tech\spreadsheet\Spreadsheet;
use yii2tech\csvgrid\CsvGrid;
use yii\data\ArrayDataProvider;
use app\magic\ExcelMagic;
use app\models\ConsultaCampoConfiguracao;
use app\models\GraficoConfiguracao;
use yii\web\Response;
use app\magic\CacheMagic;

class ConsultaController extends BaseController {

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
                                            'alterar', //consulta-alterar
                                            'preview', //consulta-alterar
                                            'update-pallete', //consulta-alterar
                                            'open-filter-update', //consulta-alterar
                                            'and-filter-update', //consulta-alterar
                                            'or-filter-update', //consulta-alterar
                                            'get-type-update', //consulta-alterar
                                            'get-field-update', //consulta-alterar
                                            'save-filter-update', //consulta-alterar
                                            'permission-consulta', //consulta-alterar
                                            'save-config-consulta', //**consulta-alterar
                                            'config-field', //**consulta-alterar
                                            'config-color', //**consulta-alterar
                                            'config-color-row', //**consulta-alterar
                                            'salvar-configuracoes', //**consulta-alterar
                                            'salvar-cores' //**consulta-alterar
                                        ],
                                    'allow' => true,
                                    'roles' => ['@'],
                                    'matchCallback' => function ($rule, $action) {
                                        return \Yii::$app->permissaoGeral->can($this->id, $this->action->id);
                                    },
                                ],
                                [
                                    'actions' =>
                                        [
                                            'export-pdf', //consulta-exportar
                                            'export-excel', //consulta-exportar
                                            'export-csv' //consulta-exportar
                                        ],
                                    'allow' => true,
                                    'roles' => ['@'],
                                    'matchCallback' => function ($rule, $action) {
                                        $request = Yii::$app->request;
                                        $consulta_id = $request->get('id');

                                        return \Yii::$app->permissaoConsulta->can($consulta_id, $this->id, $this->action->id);
                                    },
                                ],
                                [
                                    'actions' => ['visualizar'], //consulta-visualizar
                                    'allow' => true,
                                    'roles' => ['@'],
                                    'matchCallback' => function ($rule, $action) {
                                        $request = Yii::$app->request;
                                        $consulta_id = $request->get('id');

                                        return \Yii::$app->permissaoGeral->can($this->id, $this->action->id) &&
                                            \Yii::$app->permissaoConsulta->can($consulta_id, $this->id, $this->action->id);
                                    },
                                ],
                                [
                                    'actions' =>
                                        [
                                            'advanced-config' //bpone
                                        ],
                                    'allow' => true,
                                    'roles' => ['@'],
                                    'matchCallback' => function ($rule, $action) {
                                        return (Yii::$app->user->identity->id == 1);
                                    },
                                ],
                            ],
                    ]
            ];
    }

    public function actionVisualizar($id, $index = 0, $token = null) {
        $this->layout = '//consulta-view/main';

        $model = $this->findModel($id);
        $data = SqlMagic::getData($model, $index, null, $token, ($token != null), 100000000, TRUE);
        $modifications = SqlMagic::getTotalUserChanges($model->id);

        if ($data['error']) {
            return $this->render("/consulta-view/_error", compact('index', 'data', 'model'));
        } else {
            $this->salvarHistorico($id, $index, $token);

            return $this->render('/consulta-view/view', [
                'model' => $model,
                'index' => $index,
                'data' => $data,
                'modifications' => $modifications
            ]);
        }
    }

    public function actionExportPdf($id, $orientation = Pdf::ORIENT_PORTRAIT, $index = 0, $token = '') {
        $this->layout = '//print/main';

        ini_set("pcre.backtrack_limit", "5000000");

        $model = $this->findModel($id);

        $data = SqlMagic::getData($model, $index, null, $token, FALSE, 100000000, TRUE, null, TRUE);

        if (!$data['error']) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;
            $company_name = CacheMagic::getSystemData('name');
            $logo = Yii::getAlias('@app/web/') . CacheMagic::getSystemData('logo');

            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8,
                'orientation' => $orientation,
                'destination' => Pdf::DEST_DOWNLOAD,
                'marginTop' => 50,
                'content' => $this->renderPartial("/_graficos/_print", compact('data', 'model')),
                'filename' => ' ' . $model->nome . '.pdf',
                'options' =>
                    [
                        'title' => ' ' . $model->nome,
                    ],
                'methods' => [
                    'SetTitle' => ' ' . $model->nome,
                    'SetHeader' => ['<img style="width:120px;" src="' . $logo . '" /> | <p><h3>' . $company_name . '</h3></p> <p>' . mb_strtoupper($model->nome) . '</p> |'],
//                    'SetFooter' => [mb_strtoupper('Página {PAGENO} | <span style="font-size: 10px;">Powered by</span><br><span style="font-size: 14px;">BP1 Sistemas</span> |' . date('d/m/Y H:i:s', time()))],
                ]
            ]);

            return $pdf->render();
        }
    }

    public function actionExportExcel($id, $index = 0, $token = '') {
        $this->layout = '//print/main';

        ini_set("pcre.backtrack_limit", "5000000");

        $model = $this->findModel($id);

        $data = SqlMagic::getData($model, $index, null, $token, FALSE, 100000000, TRUE, null, TRUE);

        $count = (isset($data['dataProvider'][0])) ? sizeof($data['dataProvider'][0]) : 2;
        $dataSize = sizeof($data['dataProvider']) + 1;

        $alpha = range('A', 'Z');
        $letter = $alpha[$count - 1];

        if (!$data['error']) {
            $dataProvider = ExcelMagic::getData($data);

            $exporter = new Spreadsheet([
                'title' => 'Bp1 sistemas - www.bp1bi.com.br',
                'writerType' => 'Xlsx',
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $dataProvider['models'],
                ]),
                'columns' => $dataProvider['attributes']
            ]);

            $style_header = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => '227584',
                    ],
                ],
                'font' => [
                    'color' => [
                        'rgb' => 'ffffff',
                    ],
                ]
            ];

            $style_data = [
                'borders' =>
                    [
                        'allBorders' =>
                            [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                    ],
            ];

            $worksheet = $exporter->document->getActiveSheet();

            $exporter->applyCellStyle("A1:{$letter}1", $style_header);
            $exporter->applyCellStyle("A2:{$letter}{$dataSize}", $style_data);

            foreach (range('A', $letter) as $columnID) {
                $worksheet->getColumnDimension($columnID)->setAutoSize(true);
            }

            return $exporter->send(" {$model->nome}.xls");
        }
    }

    public function actionExportCsv($id, $index = 0, $token = '') {
        $this->layout = '//print/main';

        ini_set("pcre.backtrack_limit", "5000000");

        $model = $this->findModel($id);

        $data = SqlMagic::getData($model, $index, null, $token, FALSE, 100000000, TRUE, null, TRUE);

        if (!$data['error']) {
            $dataProvider = ExcelMagic::getData($data);

            $exporter = new CsvGrid([
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $dataProvider['models'],
                ]),
                'columns' => $dataProvider['attributes']
            ]);

            return $exporter->export()->send(" {$model->nome}.csv");
        }
    }

    public function actionAlterar($id, $sqlMode = FALSE) {
        $this->layout = '//consulta-update/main';

        $model = $this->findModel($id);
        $modifications = SqlMagic::getTotalChanges($model->id);

        if ($data = Yii::$app->getRequest()->getBodyParams()) {
            $model->saveData($data);
            MenuMagic::updateMenus();
            return true;
        }

        return $this->render('/consulta-update/update', compact('model', 'sqlMode', 'modifications'));
    }

    protected function findModel($id) {
        if (($model = Consulta::find()->joinWith('indicador')->andWhere([
                'bpbi_consulta.id' => $id,
                'bpbi_consulta.is_ativo' => TRUE,
                'bpbi_consulta.is_excluido' => FALSE,
                'bpbi_indicador.is_ativo' => TRUE,
                'bpbi_indicador.is_excluido' => FALSE,
            ])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'controller.mensagem_erro_404'));
    }

    public function actionPreview($id, $type = null, $sqlMode = FALSE) {
        $this->layout = FALSE;
        $model = $this->findModel($id);

        if ($post = Yii::$app->request->post()) {
            $index = $post['index'];
            $data = PreviewMagic::getData($model, $post, $index, $type, $sqlMode);

            return $this->renderAjax("/consulta-update/preview/_data", compact('index', 'data', 'model', 'sqlMode'));
        }
    }

    protected function salvarHistorico($id_consulta, $index, $token = null) {
        $user_id = Yii::$app->user->identity->id;

        $model = new UltimaTelaAcesso();
        $model->id_usuario = $user_id;
        $model->view = UltimaTelaAcesso::VIEW_CONSULTA;
        $model->id_consulta = $id_consulta;
        $model->id_painel = null;
        $model->index = $index;
        $model->token = $token;
        $model->save();
    }

    public function actionOpenFilterUpdate($id) {
        $this->layout = FALSE;
        $model = Consulta::findOne($id);

        return $this->renderAjax('//consulta-update/_layouts/_filter_update', [
            'model' => $model
        ]);
    }

    public function actionAndFilterUpdate($id, $index) {
        $this->layout = FALSE;
        $model = Consulta::findOne($id);

        return $this->renderAjax('//consulta-update/_layouts/_partials/_and', [
            'model' => $model,
            'index' => $index,
            'condicao' => null
        ]);
    }

    public function actionOrFilterUpdate($id, $indexAnd, $indexOr) {
        $this->layout = FALSE;
        $model = Consulta::findOne($id);

        return $this->renderAjax('//consulta-update/_layouts/_partials/_or', [
            'model' => $model,
            'indexAnd' => $indexAnd,
            'indexOr' => $indexOr,
            'data' => null,
            'isLast' => TRUE
        ]);
    }

    public function actionGetTypeUpdate($id = null, $and, $or) {
        $this->layout = FALSE;
        $list = [];

        if ($id) {
            $model = IndicadorCampo::findOne($id);
            $list = FiltroMagic::getListByType($model->tipo);
        }

        return $this->renderAjax('//consulta-update/_layouts/_partials/_type', [
            'list' => $list,
            'indexAnd' => $and,
            'indexOr' => $or
        ]);
    }

    public function actionGetFieldUpdate($id = null, $value, $and, $or, $tag = 0) {
        $this->layout = FALSE;
        $list = [];

        if ($id) {
            $model = IndicadorCampo::findOne($id);
            $list = FiltroMagic::getResults($model, $value);
        }

        return $this->renderAjax('//consulta-update/_layouts/_partials/_field_' . $model->tipo, [
            'type' => $value,
            'list' => $list,
            'indexAnd' => $and,
            'indexOr' => $or,
            'data' => null,
            'tag' => $tag,
            'campo' => $model
        ]);
    }

    public function actionSaveFilterUpdate($id) {
        $this->layout = FALSE;
        $model = Consulta::findOne($id);
        $request = \Yii::$app->getRequest();

        if ($request->isPost) {
            $model->aplicaFiltro($request->post());
        }
    }

    public function actionSaveConfigConsulta($id) {
        $this->layout = FALSE;
        $model = Consulta::findOne($id);

        $request = \Yii::$app->getRequest();

        if ($request->isPost && $model->load($request->post()) && $model->save()) {
            return 1;
        }
    }

    public function actionUpdatePallete($id, $pallete_id) {
        $this->layout = FALSE;
        $model = Consulta::findOne($id);
        $model->id_pallete = (int) $pallete_id;
        $model->save();
    }

    public function actionPermissionConsulta($consulta_id, $perfil_id, $permissao_id, $state) {
        $permissao = PermissaoConsulta::findOne($permissao_id);

        if ($permissao->gerenciador == 'visualizar' && $state == 'false') {
            ConsultaPermissao::deleteAll([
                'id_consulta' => $consulta_id,
                'id_perfil' => $perfil_id
            ]);
        } else {
            ConsultaPermissao::deleteAll([
                'id_consulta' => $consulta_id,
                'id_perfil' => $perfil_id,
                'id_permissao' => $permissao_id
            ]);
        }

        if ($state == 'true') {
            $model = new ConsultaPermissao();
            $model->id_consulta = $consulta_id;
            $model->id_perfil = $perfil_id;
            $model->id_permissao = $permissao_id;
            $model->save();
        }
    }

    public function actionConfigField($id, $item_id) {
        $this->layout = FALSE;

        $model = Consulta::findOne($id);
        $item = IndicadorCampo::findOne($item_id);

        $subQuery = (new Query)
            ->select([new Expression('1')])
            ->from('bpbi_consulta_item_configuracao item')
            ->andWhere(['id_consulta' => $id])
            ->andWhere(['id_item' => $item_id])
            ->andWhere('item.id_campo = bpbi_indicador_campo.id');

        $query = IndicadorCampo::find()
            ->andWhere(['id_indicador' => $model->id_indicador])
            ->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE])
//            ->andWhere('id <> ' . $item_id)
            ->andWhere(['not exists', $subQuery])
            ->orderBy('nome ASC');

        $camposDisponiveis = $query->all();

        $camposUtilizados = ConsultaItemConfiguracao::find()
            ->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE])
            ->andWhere(['id_consulta' => $id, 'id_item' => $item_id])->orderBy('ordem ASC')->all();

        return $this->renderAjax('//consulta-update/_layouts/_form-config', compact('model', 'item', 'camposDisponiveis', 'camposUtilizados'));
    }

    public function actionConfigColor($id, $item_id) {
        $this->layout = FALSE;

        $model = Consulta::findOne($id);
        $campo = IndicadorCampo::findOne($item_id);

        $query = ConsultaItemCor::find()
            ->andWhere(['id_consulta' => $model->id])
            ->andWhere(['id_campo' => $campo->id])
            ->andWhere(['is_ativo' => TRUE, 'is_excluido' => FALSE])
            ->orderBy('ordem ASC');

        $campoCores = $query->all();

        return $this->renderAjax('//consulta-update/_layouts/_form-color', compact('model', 'campo', 'campoCores'));
    }

    public function actionConfigColorRow($item_id, $ordem) {
        $this->layout = FALSE;

        $campo = IndicadorCampo::findOne($item_id);
        return $this->renderAjax('//consulta-update/_layouts/_form-color-field', ['model' => null, 'campo_id' => $campo->id, 'ordem' => $ordem]);
    }

    public function actionAdvancedConfig($id, $campo_id, $view, $type, $is_serie = FALSE) {
        if (Yii::$app->user->identity->id == 1) {
            $this->layout = FALSE;

            $is_serie = (!in_array($type, ['table', 'pie', 'donut', 'funnel']) && $is_serie == "1");

            $model = ConsultaCampoConfiguracao::find()->andWhere([
                'is_ativo' => TRUE,
                'is_excluido' => FALSE,
                'id_consulta' => $id,
                'id_campo' => $campo_id,
                'view' => $view,
                'tipo' => $type,
                'is_serie' => $is_serie
            ])->one();

            if (!$model) {
                $configuracao = GraficoConfiguracao::find()->andWhere([
                    'view' => $view,
                    'tipo' => $type,
                    'is_serie' => $is_serie
                ])->one();

                $model = new ConsultaCampoConfiguracao();
                $model->id_consulta = $id;
                $model->id_campo = $campo_id;
                $model->view = $view;
                $model->tipo = $type;
                $model->is_serie = $is_serie;

                if ($configuracao) {
                    $model->data = $configuracao->data;
                    $model->data_serie = $configuracao->data_serie;
                    $model->data_timeline = $configuracao->data_timeline;
                }
            }

            $request = \Yii::$app->getRequest();

            if ($request->isPost && $model->load($request->post())) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                $success = $model->save();

                if ($success) {
                    return ['success' => true, 'form' => null];
                } else {
                    return
                        [
                            'success' => false,
                            'form' => $this->renderAjax('_form-advanced', compact('model', 'type', 'is_serie'))
                        ];
                }
            }

            return $this->renderAjax('//consulta-update/_layouts/_form-advanced', compact('model', 'type', 'is_serie'));
        } else {
            throw new NotFoundHttpException(\Yii::t('app', 'controller.mensagem_erro_404'));
        }
    }

    public function actionSalvarConfiguracoes($consulta_id, $item_id) {
        if ($data = Yii::$app->getRequest()->getBodyParams()) {
            if ($data["saveAll"] && $data["saveAll"] == 'true') {
                ConsultaItemConfiguracao::deleteAll([
                    'is_ativo' => TRUE,
                    'is_excluido' => FALSE,
                    'id_consulta' => $consulta_id,
                ]);

                if (isset($data['campos'])) {
                    foreach ($data['campos'] as $index => $campo_id) {
                        foreach ($data['argumentos'] as $item_id) {
                            $model = new ConsultaItemConfiguracao();
                            $model->id_consulta = $consulta_id;
                            $model->id_item = $item_id;
                            $model->id_campo = $campo_id;
                            $model->ordem = ($index + 1);
                            $model->save();
                        }
                    }
                }
            } else {
                ConsultaItemConfiguracao::deleteAll([
                    'is_ativo' => TRUE,
                    'is_excluido' => FALSE,
                    'id_consulta' => $consulta_id,
                    'id_item' => $item_id
                ]);

                if (isset($data['campos'])) {
                    foreach ($data['campos'] as $index => $campo_id) {
                        $model = new ConsultaItemConfiguracao();
                        $model->id_consulta = $consulta_id;
                        $model->id_item = $item_id;
                        $model->id_campo = $campo_id;
                        $model->ordem = ($index + 1);
                        $model->save();
                    }
                }
            }
        }
    }

    public function actionSalvarCores($consulta_id, $item_id) {
        if ($data = Yii::$app->getRequest()->getBodyParams()) {

            ConsultaItemCor::deleteAll([
                'is_ativo' => TRUE,
                'is_excluido' => FALSE,
                'id_consulta' => $consulta_id,
                'id_campo' => $item_id
            ]);

            if(isset($data['Form']) && is_array($data['Form']))
            {
                $idx = 1;

                foreach ($data['Form'] as $form)
                {
                    if($form['valor'] != '' && $form['cor'] != '')
                    {
                        $consultaCor = new ConsultaItemCor();
                        $consultaCor->ordem = $idx;
                        $consultaCor->id_consulta = $consulta_id;
                        $consultaCor->id_campo = $item_id;
                        $consultaCor->valor = $form['valor'];
                        $consultaCor->cor = $form['cor'];
                        $consultaCor->save();

                        $idx++;
                    }
                }
            }
        }
    }
}
