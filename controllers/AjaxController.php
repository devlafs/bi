<?php

namespace app\controllers;

use app\models\RelatorioData;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\Pasta;
use app\models\Consulta;
use app\magic\MenuMagic;
use yii\web\Response;
use yii\helpers\Url;
use app\models\IndicadorCampo;
use app\magic\FiltroMagic;
use app\models\ConsultaFiltroUsuario;
use app\models\UrlShare;
use app\magic\CacheMagic;
use app\models\ConsultaGraficoUsuario;
use app\models\ConsultaItem;
use app\models\UltimaTelaAcesso;
use yii\web\NotFoundHttpException;
use app\models\Painel;
use Da\QrCode\QrCode;
use yii\base\DynamicModel;
use yii\base\Exception;
use app\models\AdminUsuario;
use yii\helpers\Json;

class AjaxController extends BaseController {

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
                                    'open-filter-view', //consulta-filtrar
                                    'and-filter-view', //consulta-filtrar
                                    'or-filter-view', //consulta-filtrar
                                    'get-type-view', //consulta-filtrar
                                    'get-field-view', //consulta-filtrar
                                    'save-filter-view', //consulta-filtrar
                                    'change-user-graph', //consulta-grafico
                                    'restaure-graph', //consulta-grafico
                                    'field-list', //

                                    'open-filter-painel', //consulta-filtrar
                                ],
                                'allow' => true,
                                'roles' => ['@'],
                                'matchCallback' => function ($rule, $action) {
                                    $request = Yii::$app->request;
                                    $consulta_id = $request->get('id');

                                    if (in_array($this->action->id, ['get-field-view', 'get-type-view', 'field-list'])) {
                                        return true;
                                    }

                                    return \Yii::$app->permissaoConsulta->can($consulta_id, $this->id, $this->action->id);
                                },
                            ],
                            [
                                'actions' =>
                                [
                                    'generate-url-publica', //consulta/painel-url
                                    'send-url-publica', //consulta/painel-email
                                ],
                                'allow' => true,
                                'roles' => ['@'],
                                'matchCallback' => function ($rule, $action) {
                                    $request = Yii::$app->request;
                                    $id_consulta = $request->get('id_consulta');
                                    $id_painel = $request->get('id_painel');

                                    return ($id_consulta != 'null') ? \Yii::$app->permissaoConsulta->can($id_consulta, $this->id, $this->action->id) : \Yii::$app->permissaoPainel->can($id_painel, $this->id, $this->action->id);
                                },
                            ],
                            [
                                'actions' =>
                                [
                                    'pasta', //menu-cadastrar
                                    'delete-folder', //menu-excluir
                                    'move-menu', //menu-mover
                                    'consulta', //consulta-cadastrar
                                    'duplicate-consulta', //consulta-duplicar
                                    'rename-consulta', //consulta-alterar
                                    'delete-consulta', //consulta-excluir
                                    'painel', //painel-cadastrar
                                    'duplicate-painel', //painel-duplicar
                                    'rename-painel', //painel-alterar
                                    'delete-painel', //painel-delete
                                    'relatorio', //relatorio-cadastrar
                                    'duplicate-relatorio', //relatorio-duplicar
                                    'rename-relatorio', //relatorio-alterar
                                    'delete-relatorio', //relatorio-delete
                                ],
                                'allow' => true,
                                'roles' => ['@'],
                                'matchCallback' => function ($rule, $action) {
                                    $controller_id = $this->id;
                                    $action_id = $this->action->id;

                                    if ($this->action->id == 'rename-consulta' || $this->action->id == 'rename-painel') {
                                        $controller_id = ($this->action->id == 'rename-consulta') ? 'consulta' : 'painel';
                                        $action_id = 'alterar';
                                    }

                                    if ($this->action->id == 'rename-relatorio') {
                                        $controller_id = 'relatorio';
                                        $action_id = 'alterar';
                                    }

                                    return \Yii::$app->permissaoGeral->can($controller_id, $action_id);
                                },
                            ],
                            [
                                'actions' =>
                                    [
                                        'update-menu-session'
                                    ],
                                'allow' => true,
                                'roles' => ['@'],
                            ]
                        ],
                    ]
        ];
    }

    public function actionPasta($tipo = "CONSULTA", $id = null, $update = false) {
        $model = new Pasta();
        $this->layout = FALSE;

        if ($id) {
            if ($update) {
                $model = Pasta::findOne($id);
            } else {
                $model->id_pasta = $id;
            }
        }

        $model->tipo = $tipo;

        $request = \Yii::$app->getRequest();

        if ($request->isPost && $model->load($request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $success = $model->save();

            if ($success) {
                \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_salva', [
                    'model' => Yii::t('app', 'geral.pasta')
                ]));

                MenuMagic::updateMenus();

                return ['success' => true, 'form' => null];
            } else {
                return
                        [
                            'success' => false,
                            'form' => $this->renderAjax('_form-pasta', [
                                'model' => $model,
                                'update' => $update,
                                'tipo' => $tipo
                            ])
                ];
            }
        }

        return $this->renderAjax('_form-pasta', [
                    'model' => $model,
                    'update' => $update,
                    'tipo' => $tipo
        ]);
    }

    public function actionMoveMenu() {
        $this->layout = FALSE;
        $request = \Yii::$app->getRequest();

        if ($request->isPost) {
            $post = $request->post();

            if ($post['class'] == 'pasta') {
                $model = Pasta::findOne($post['current_id']);
            } elseif ($post['class'] == 'painel') {
                $model = Painel::findOne($post['current_id']);
            } elseif ($post['class'] == 'relatorio') {
                $model = RelatorioData::findOne($post['current_id']);
            } else {
                $model = Consulta::findOne($post['current_id']);
            }

            $modelAux = Pasta::findOne($post['parent_id']);

            if ($model) {
                if ($modelAux) {
                    $id_pasta = ($post['position'] == 'inside') ? $modelAux->id : $modelAux->id_pasta;
                    $model->id_pasta = $id_pasta;
                } else {
                    $model->id_pasta = null;
                }

                $model->save();
                MenuMagic::updateMenus();
            }
        }
    }

    public function actionDeleteFolder($id) {
        $model = Pasta::findOne($id);

        if ($model->consultas) {
            foreach ($model->consultas as $consulta) {
                $consulta->id_pasta = null;
                $consulta->save();
            }
        }

        if ($model->paineis) {
            foreach ($model->paineis as $painel) {
                $painel->id_pasta = null;
                $painel->save();
            }
        }

        if ($model->pastas) {
            foreach ($model->pastas as $pasta) {
                $pasta->id_pasta = null;
                $pasta->save();
            }
        }

        $model->is_ativo = FALSE;
        $model->is_excluido = TRUE;
        $model->save();

        MenuMagic::updateMenus();

        \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_removida', [
            'model' => Yii::t('app', 'geral.pasta')
        ]));

        return true;
    }

    public function actionConsulta($id = null) {
        $this->layout = FALSE;
        $model = new Consulta();

        $pallete_id = CacheMagic::getSystemData('pallete');
        $model->id_pallete = $pallete_id;
        $model->id_pasta = $id;

        $request = \Yii::$app->getRequest();

        if ($request->isPost && $model->load($request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $success = $model->save();

            if ($success) {

                \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_inserida', [
                    'model' => Yii::t('app', 'geral.consulta')
                ]));

                MenuMagic::updateMenus();

                return ['success' => true, 'form' => null, 'url' => Url::toRoute(['/consulta/alterar', 'id' => $model->id])];
            } else {
                return
                        [
                            'success' => false,
                            'url' => '',
                            'form' => $this->renderAjax('_form-consulta', [
                                'model' => $model,
                            ])
                ];
            }
        }

        return $this->renderAjax('_form-consulta', [
                    'model' => $model,
        ]);
    }

    public function actionDuplicateConsulta($id) {
        $this->layout = FALSE;
        $model = Consulta::findOne($id);

        if ($model) {
            $request = \Yii::$app->getRequest();

            if ($request->isPost && $model->load($request->post())) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                $novoModel = new Consulta();
                $success = $novoModel->duplicar($model);

                if ($success) {

                    \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_duplicada', [
                        'model' => Yii::t('app', 'geral.consulta')
                    ]));

                    MenuMagic::updateMenus();

                    return ['success' => true, 'form' => null, 'url' => Url::toRoute(['/consulta/visualizar', 'id' => $novoModel->id])];
                } else {
                    return
                            [
                                'success' => false,
                                'url' => '',
                                'form' => $this->renderAjax('_form-consulta-duplicate', [
                                    'model' => $model,
                                ])
                    ];
                }
            }

            return $this->renderAjax('_form-consulta-duplicate', [
                        'model' => $model,
            ]);
        }
    }

    public function actionRenameConsulta($id) {
        $this->layout = FALSE;
        $model = Consulta::findOne($id);

        if ($model) {
            $request = \Yii::$app->getRequest();

            if ($request->isPost && $model->load($request->post())) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                $success = $model->save();

                if ($success) {

                    \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_renomeada', [
                        'model' => Yii::t('app', 'geral.consulta')
                    ]));

                    MenuMagic::updateMenus();

                    return ['success' => true, 'form' => null, 'url' => Url::toRoute(['/consulta/visualizar', 'id' => $model->id])];
                } else {
                    return
                            [
                                'success' => false,
                                'url' => '',
                                'form' => $this->renderAjax('_form-consulta-rename', [
                                    'model' => $model,
                                ])
                    ];
                }
            }

            return $this->renderAjax('_form-consulta-rename', [
                        'model' => $model,
            ]);
        }
    }

    public function actionDeleteConsulta($id) {
        $model = Consulta::findOne($id);
        $model->is_ativo = FALSE;
        $model->is_excluido = TRUE;
        $model->save();

        UltimaTelaAcesso::updateAll([
            'is_ativo' => false,
            'is_excluido' => true
                ], ['id_consulta' => $model->id]);

        MenuMagic::updateMenus();

        \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_removida', [
            'model' => Yii::t('app', 'geral.consulta')
        ]));

        return true;
    }

    public function actionPainel($id = null) {
        $this->layout = FALSE;
        $model = new Painel();

        $model->id_pasta = $id;

        $request = \Yii::$app->getRequest();

        if ($request->isPost && $model->load($request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $success = $model->save();

            if ($success) {
                \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_inserido', [
                    'model' => Yii::t('app', 'geral.painel')
                ]));

                MenuMagic::updateMenus();

                return ['success' => true, 'form' => null, 'url' => Url::toRoute(['/painel/alterar', 'id' => $model->id])];
            } else {
                return
                        [
                            'success' => false,
                            'url' => '',
                            'form' => $this->renderAjax('_form-painel', [
                                'model' => $model,
                            ])
                ];
            }
        }

        return $this->renderAjax('_form-painel', [
                    'model' => $model,
        ]);
    }

    public function actionDuplicatePainel($id) {
        $this->layout = FALSE;
        $model = Painel::findOne($id);

        if ($model) {
            $request = \Yii::$app->getRequest();

            if ($request->isPost && $model->load($request->post())) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                $novoModel = new Painel();
                $success = $novoModel->duplicar($model);

                if ($success) {
                    \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_duplicado', [
                        'model' => Yii::t('app', 'geral.painel')
                    ]));

                    MenuMagic::updateMenus();

                    return ['success' => true, 'form' => null, 'url' => Url::toRoute(['/painel/visualizar', 'id' => $novoModel->id])];
                } else {
                    return
                            [
                                'success' => false,
                                'url' => '',
                                'form' => $this->renderAjax('_form-painel-duplicate', [
                                    'model' => $model,
                                ])
                    ];
                }
            }

            return $this->renderAjax('_form-painel-duplicate', [
                        'model' => $model,
            ]);
        }
    }

    public function actionRenamePainel($id) {
        $this->layout = FALSE;
        $model = Painel::findOne($id);

        if ($model) {
            $request = \Yii::$app->getRequest();

            if ($request->isPost && $model->load($request->post())) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                $success = $model->save();

                if ($success) {
                    \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_renomeado', [
                        'model' => Yii::t('app', 'geral.painel')
                    ]));

                    MenuMagic::updateMenus();

                    return ['success' => true, 'form' => null, 'url' => Url::toRoute(['/painel/visualizar', 'id' => $model->id])];
                } else {
                    return
                            [
                                'success' => false,
                                'url' => '',
                                'form' => $this->renderAjax('_form-painel-rename', [
                                    'model' => $model,
                                ])
                    ];
                }
            }

            return $this->renderAjax('_form-painel-rename', [
                        'model' => $model,
            ]);
        }
    }

    public function actionDeletePainel($id) {
        $model = Painel::findOne($id);
        $model->is_ativo = FALSE;
        $model->is_excluido = TRUE;
        $model->save();

        UltimaTelaAcesso::updateAll([
            'is_ativo' => false,
            'is_excluido' => true
                ], ['id_painel' => $model->id]);

        MenuMagic::updateMenus();

        \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_removido', [
            'model' => Yii::t('app', 'geral.painel')
        ]));

        return true;
    }

    public function actionRelatorio($id = null) {
        $this->layout = FALSE;
        $model = new RelatorioData();

        $model->id_pasta = $id;

        $request = \Yii::$app->getRequest();

        if ($request->isPost && $model->load($request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $success = $model->save();

            if ($success) {
                \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_inserido', [
                    'model' => Yii::t('app', 'geral.relatorio')
                ]));

                MenuMagic::updateMenus();

                return ['success' => true, 'form' => null, 'url' => Url::toRoute(['/relatorio-data/alterar', 'id' => $model->id])];
            } else {
                return
                    [
                        'success' => false,
                        'url' => '',
                        'form' => $this->renderAjax('_form-relatorio', [
                            'model' => $model,
                        ])
                    ];
            }
        }

        return $this->renderAjax('_form-relatorio', [
            'model' => $model,
        ]);
    }

    public function actionDuplicateRelatorio($id) {
        $this->layout = FALSE;
        $model = RelatorioData::findOne($id);

        if ($model) {
            $request = \Yii::$app->getRequest();

            if ($request->isPost && $model->load($request->post())) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                $novoModel = new RelatorioData();
                $success = $novoModel->duplicar($model);

                if ($success) {
                    \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_duplicado', [
                        'model' => Yii::t('app', 'geral.relatorio')
                    ]));

                    MenuMagic::updateMenus();

                    return ['success' => true, 'form' => null, 'url' => Url::toRoute(['/relatorio-data/visualizar', 'id' => $novoModel->id])];
                } else {
                    return
                        [
                            'success' => false,
                            'url' => '',
                            'form' => $this->renderAjax('_form-relatorio-duplicate', [
                                'model' => $model,
                            ])
                        ];
                }
            }

            return $this->renderAjax('_form-relatorio-duplicate', [
                'model' => $model,
            ]);
        }
    }

    public function actionRenameRelatorio($id) {
        $this->layout = FALSE;
        $model = RelatorioData::findOne($id);

        if ($model) {
            $request = \Yii::$app->getRequest();

            if ($request->isPost && $model->load($request->post())) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                $success = $model->save();

                if ($success) {
                    \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_renomeado', [
                        'model' => Yii::t('app', 'geral.relatorio')
                    ]));

                    MenuMagic::updateMenus();

                    return ['success' => true, 'form' => null, 'url' => Url::toRoute(['/relatorio-data/visualizar', 'id' => $model->id])];
                } else {
                    return
                        [
                            'success' => false,
                            'url' => '',
                            'form' => $this->renderAjax('_form-relatorio-rename', [
                                'model' => $model,
                            ])
                        ];
                }
            }

            return $this->renderAjax('_form-relatorio-rename', [
                'model' => $model,
            ]);
        }
    }

    public function actionDeleteRelatorio($id) {
        $model = RelatorioData::findOne($id);
        $model->is_ativo = FALSE;
        $model->is_excluido = TRUE;
        $model->save();

        UltimaTelaAcesso::updateAll([
            'is_ativo' => false,
            'is_excluido' => true
        ], ['id_relatorio_data' => $model->id]);

        MenuMagic::updateMenus();

        \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_removido', [
            'model' => Yii::t('app', 'geral.relatorio')
        ]));

        return true;
    }

    public function actionOpenFilterView($id) {
        $this->layout = FALSE;

        $consulta = Consulta::findOne($id);
        $user_id = Yii::$app->user->id;

        $model = ConsultaFiltroUsuario::find()->andWhere([
                    'id_usuario' => $user_id,
                    'id_consulta' => $consulta->id
                ])->one();

        if (!$model) {
            $model = new ConsultaFiltroUsuario();
            $model->id_usuario = $user_id;
            $model->id_consulta = $consulta->id;
        }

        return $this->renderAjax('//consulta-view/_layouts/_filter_view', [
                    'model' => $model,
                    'consulta' => $consulta,
        ]);
    }

    public function actionOpenFilterPainel($id) {
        $this->layout = FALSE;

        $model = Painel::findOne($id);

        return $this->renderAjax('//painel-update/_layouts/_filter_update', [
            'model' => $model,
        ]);
    }

    public function actionAndFilterView($id, $index) {
        $this->layout = FALSE;
        $consulta = Consulta::findOne($id);
        $user_id = Yii::$app->user->id;

        return $this->renderAjax('//consulta-view/_layouts/_partials/_and', [
                    'consulta' => $consulta,
                    'index' => $index,
                    'condicao' => null
        ]);
    }

    public function actionOrFilterView($id, $indexAnd, $indexOr) {
        $this->layout = FALSE;
        $model = Consulta::findOne($id);

        return $this->renderAjax('//consulta-view/_layouts/_partials/_or', [
                    'consulta' => $model,
                    'indexAnd' => $indexAnd,
                    'indexOr' => $indexOr,
                    'data' => null,
                    'isLast' => TRUE
        ]);
    }

    public function actionGetTypeView($id = null, $and, $or) {
        $this->layout = FALSE;
        $list = [];

        if ($id) {
            $model = IndicadorCampo::findOne($id);
            $list = FiltroMagic::getListByType($model->tipo);
        }

        return $this->renderAjax('//consulta-view/_layouts/_partials/_type', [
                    'list' => $list,
                    'indexAnd' => $and,
                    'indexOr' => $or
        ]);
    }

    public function actionGetFieldView($id = null, $value, $and, $or, $tag = 0) {
        $this->layout = FALSE;
        $list = [];

        if ($id) {
            $model = IndicadorCampo::findOne($id);
            $list = FiltroMagic::getResults($model, $value);
        }

        return $this->renderAjax('//consulta-view/_layouts/_partials/_field_' . $model->tipo, [
                    'type' => $value,
                    'list' => $list,
                    'indexAnd' => $and,
                    'indexOr' => $or,
                    'data' => null,
                    'tag' => $tag,
                    'campo' => $model
        ]);
    }

    public function actionSaveFilterView($id) {
        $this->layout = FALSE;

        $consulta = Consulta::findOne($id);
        $user_id = Yii::$app->user->id;

        $model = ConsultaFiltroUsuario::find()->andWhere([
                    'id_usuario' => $user_id,
                    'id_consulta' => $consulta->id,
                    'is_ativo' => TRUE,
                    'is_excluido' => FALSE
                ])->one();

        if (!$model) {
            $model = new ConsultaFiltroUsuario();
            $model->id_usuario = $user_id;
            $model->id_consulta = $consulta->id;
            $model->save();
        }

        $request = \Yii::$app->getRequest();

        if ($request->isPost) {
            $model->aplicaFiltro($request->post());
        }
    }

    public function actionRestaureGraph($id) {
        $user_id = Yii::$app->user->id;
        $consulta = Consulta::findOne($id);

        if ($consulta) {
            $configuracoes = ConsultaGraficoUsuario::find()
                            ->andWhere([
                                'is_ativo' => TRUE,
                                'is_excluido' => FALSE,
                                'id_usuario' => $user_id,
                                'id_consulta' => $consulta->id,
                            ])->all();

            if ($configuracoes) {
                foreach ($configuracoes as $configuracao) {
                    $configuracao->delete();
                }
            }
        } else {
            throw new NotFoundHttpException(\Yii::t('app', 'controller.mensagem_erro_404'));
        }
    }

    public function actionChangeUserGraph($id, $field, $graph) {
        $this->layout = FALSE;

        $consulta = Consulta::findOne($id);
        $user_id = Yii::$app->user->id;

        $item = ConsultaItem::findOne($field);

        if ($item) {
            $configuracao = ConsultaGraficoUsuario::find()
                            ->andWhere([
                                'is_ativo' => TRUE,
                                'is_excluido' => FALSE,
                                'id_usuario' => $user_id,
                                'id_consulta' => $consulta->id,
                                'campo' => $item->campo->campo,
                            ])->one();

            if (!$configuracao) {
                $configuracao = new ConsultaGraficoUsuario();
                $configuracao->id_usuario = $user_id;
                $configuracao->id_consulta = $consulta->id;
                $configuracao->campo = $item->campo->campo;
            }

            $configuracao->tipo_grafico = $graph;
            $configuracao->save();
        } else {
            throw new NotFoundHttpException(\Yii::t('app', 'controller.mensagem_erro_404'));
        }
    }

    public function actionGenerateUrlPublica($id_consulta = 'null', $id_painel = 'null', $id_relatorio = 'null', $view) {
        $this->layout = FALSE;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $user_id = Yii::$app->user->id;
        $token = Yii::$app->security->generateRandomString() . time();
        $pass = Yii::$app->security->generateRandomString();

        $model = new UrlShare();
        $model->id_consulta = ($id_consulta == 'null') ? null : $id_consulta;
        $model->id_painel = ($id_painel == 'null') ? null : $id_painel;
        $model->id_relatorio_data = ($id_relatorio == 'null') ? null : $id_relatorio;
        $model->view = $view;
        $model->id_usuario = $user_id;
        $model->token = $token;
        $model->password = $pass;
        $model->type = 'url';

        if ($model->save()) {
            $url = CacheMagic::getSystemData('url') . "/share/v?c={$model->id}&t={$token}";
            return ['success' => TRUE, 'url' => $url, 'pass' => $pass];
        } else {
            return ['success' => FALSE, 'url' => null, 'pass' => null];
        }
    }

    public function actionSendUrlPublica($id_consulta = 'null', $id_painel = 'null', $id_relatorio = 'null', $view) {
        $this->layout = FALSE;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = \Yii::$app->getRequest();

        $user_id = Yii::$app->user->id;
        $token = Yii::$app->security->generateRandomString() . time();
        $pass = Yii::$app->security->generateRandomString();

        if ($request->isPost) {
            $post = $request->post();
            $email = $post['email'];

            $consulta = ($id_consulta == 'null') ? null : Consulta::findOne($id_consulta);

            $dynamicModel = new DynamicModel(compact('email'));
            $dynamicModel->addRule('email', 'email')->validate();

            if ($dynamicModel->hasErrors()) {
                throw new Exception(\Yii::t('app', 'erro.email_invalido'));
            } else {
                if ($consulta) {
                    if (!$consulta->email_externo) {
                        $usuario = AdminUsuario::findOne(['email' => $email]);

                        if (!$usuario) {
                            throw new Exception(\Yii::t('app', 'erro.email_nao_cadastrado'));
                        }
                    }
                }

                $model = new UrlShare();
                $model->id_usuario = $user_id;
                $model->id_consulta = ($id_consulta == 'null') ? null : $id_consulta;
                $model->id_painel = ($id_painel == 'null') ? null : $id_painel;
                $model->id_relatorio_data = ($id_relatorio == 'null') ? null : $id_relatorio;
                $model->view = $view;
                $model->token = $token;
                $model->password = $pass;
                $model->type = 'email';
                $model->email = $email;

                if ($model->save()) {
                    if($model->view == UrlShare::VIEW_CONSULTA)
                    {
                        $aux_model = $model->consulta;
                    }
                    elseif($model->view == UrlShare::VIEW_PAINEL)
                    {
                        $aux_model = $model->painel;
                    }
                    else
                    {
                        $aux_model = $model->relatorio;
                    }

                    $url = CacheMagic::getSystemData('url') . "/share/v?c={$model->id}&t={$token}";
                    $this->sendMail($model, $view, $aux_model, $email, $url);

                    return ['success' => TRUE];
                }
            }
        }
    }

    protected function sendMail($model, $view, $aux_model, $email, $url) {
        \Yii::$app->mailer->htmlLayout = "@app/mail/layouts/html";

        if (strpos($email, ',') !== false) {
            $email = array_map('trim', explode(',', $email));
        }

        $qrCode = (new QrCode($url))
                ->setSize(130)
                ->setMargin(10)
                ->useForegroundColor(34, 117, 132)
                ->useBackgroundColor(255, 255, 255)
                ->useEncoding('UTF-8');

        $filename = $model->id . '_' . time() . '.png';
        $qrCode->writeFile(\Yii::getAlias('@app/web/qrcode/' . $filename));
        $cid = (\Yii::getAlias('@app/web/qrcode/' . $filename));

        if($model->view == UrlShare::VIEW_CONSULTA)
        {
            $title = \Yii::t('app', 'geral.consulta');
        }
        elseif($model->view == UrlShare::VIEW_PAINEL)
        {
            $title = \Yii::t('app', 'geral.painel');
        }
        else
        {
            $title = \Yii::t('app', 'geral.relatorio');
        }

        $message = \Yii::$app->mailer->compose(['html' => '@app/mail/views/send-url'], ['model' => $model, 'title' => $title, 'aux_model' => $aux_model, 'usuario' => Yii::$app->user->identity, 'url' => $url, 'cid' => $cid]);
        $message->setFrom(CacheMagic::getSystemData('systemEmail'));
        $message->setTo($email);
        $message->setSubject("{$title}:: " . $aux_model->nome);
        $message->send();
    }

    public function actionFieldList($field_id, $q = null) {
        $out = [];
        $campo = IndicadorCampo::findOne($field_id);

        $this->enableCsrfValidation = false;
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!is_null($q)) {
            $data = FiltroMagic::getValorCampo($campo, $q);
            $out['results'] = array_values($data);
        } else {
            $out['results'] = ['id' => '', 'text' =>  \Yii::t('app', 'erro.nenhum_resultado_encontrado')];
        }

        echo Json::encode($out);
    }

    public function actionUpdateMenuSession()
    {
        $key = 'menu_' . Yii::$app->user->id;

        if(isset($_SESSION[$key]))
        {
            $_SESSION[$key] = !$_SESSION[$key];
        }
        else
        {
            $_SESSION[$key] = false;
        }
    }

}
