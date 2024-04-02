<?php

namespace app\controllers;

use Yii;
use app\models\Indicador;
use app\models\IndicadorCampo;
use app\models\searches\IndicadorCampoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class IndicadorCampoController extends BaseController {

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
                                    'create', //campo-cadastrar
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
        $indicador = Indicador::find()->andWhere(['id' => $id, 'is_excluido' => FALSE])->one();

        if (!$indicador) {
            throw new NotFoundHttpException(\Yii::t('app', 'controller.mensagem_erro_404'));
        }

        $searchModel = new IndicadorCampoSearch();
        $dataProvider = $searchModel->search($id, Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'indicador' => $indicador
        ]);
    }

    public function actionCreate($id_indicador) {
        $db = Yii::$app->db;
        $max = $db->createCommand("SELECT max(ordem) FROM bpbi_indicador_campo WHERE id_indicador = {$id_indicador}")->queryScalar();

        $model = new IndicadorCampo();
        $model->id_indicador = $id_indicador;
        $model->tipo = "formulavalor";
        $model->ordem = ($max) ? (int) $max + 1 : 1;

        if ($model->load(Yii::$app->request->post()) && !$model->reload_form && $model->save()) {
            \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_cadastrada', [
                'model' => Yii::t('app', 'geral.formula')
            ]));

            return $this->redirect(['update', 'id_indicador' => $model->id_indicador, 'id' => $model->id]);
        } elseif (!$model->reload_form) {
            $model->validate();
        }

        $model->reload_form = 0;

        return $this->render('create', [
                    'model' => $model
        ]);
    }

    public function actionUpdate($id_indicador, $id, $preview = FALSE) {
        $model = $this->findModel($id_indicador, $id);
        $ordem = $model->ordem - 1;

        if ($model->tipo == 'formulavalor' || $model->tipo == 'formulatexto') {
            $campo = $model->campo;
            preg_match_all('/[^{{\}}]+(?=}})/', $model->campo, $matches);

            foreach ($matches as $match) {
                if ($match) {
                    foreach ($match as $mt) {
                        $nomecampo = (int) $mt - 1;
                        $campo = str_replace("{{{$mt}}}", "valor{$nomecampo}", $campo);
                    }
                }
            }

            if ($model->variavel_formula) {
                Yii::$app->db->createCommand($model->variavel_formula)->execute();
            }

            $sql = <<<SQL

                SELECT {$campo} as x
                    FROM bpbi_indicador{$id_indicador}
                    ORDER BY {$campo} DESC
                    LIMIT 10;

SQL;
        } else {
            $sql = <<<SQL
    
                SELECT valor{$ordem} as x
                    FROM bpbi_indicador{$id_indicador}
                    WHERE valor{$ordem} IS NOT NULL AND valor{$ordem} != ''
                GROUP BY valor{$ordem}
                    ORDER BY valor{$ordem} DESC
                    LIMIT 10;
                
SQL;
        }

        try {
            $preview = Yii::$app->db->createCommand($sql)->queryAll();
        } catch (\yii\db\Exception $ex) {
            $preview['error'] = ['x' => $ex->getMessage()];
        }

        if ($model->load(Yii::$app->request->post()) && !$model->reload_form && $model->save()) {
            \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_alterado', [
                'model' => Yii::t('app', 'geral.campo')
            ]));

            return $this->redirect(['update', 'id_indicador' => $model->id_indicador, 'id' => $model->id]);
        }

        $model->reload_form = 0;

        return $this->render('update', [
                    'model' => $model,
                    'preview' => $preview
        ]);
    }

    public function actionDelete($id_indicador, $id) {
        $model = $this->findModel($id_indicador, $id);

        $model->is_excluido = TRUE;

        if ($model->save()) {
            \Yii::$app->getSession()->setFlash('toast-success', Yii::t('app', 'controller.mensagem_removido', [
                'model' => Yii::t('app', 'geral.campo')
            ]));
        }

        return true;
    }

    protected function findModel($id_indicador, $id) {
        $model = IndicadorCampo::find()->andWhere(['id_indicador' => $id_indicador, 'id' => $id])->one();

        if ($model !== null && !$model->is_excluido) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'controller.mensagem_erro_404'));
    }

}
