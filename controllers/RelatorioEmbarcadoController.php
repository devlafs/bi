<?php

namespace app\controllers;

use app\models\AdminUsuario;
use app\models\Painel;
use app\models\RelatorioData;
use app\models\RelatorioDataItem;
use app\models\searches\RelatorioDinamicoSearch;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class RelatorioEmbarcadoController extends BaseController {

    public function behaviors() {
        return
                [
                    'access' =>
                    [
                        'class' => AccessControl::className(),
                        'rules' =>
                        [
                            [
                                'actions' => ['v'], //all
                                'allow' => true,
                            ],
                        ],
                    ]
        ];
    }

    private function validateUser($u, $p)
    {
        $user = AdminUsuario::findOne($u);
        return $user->senha == base64_decode($p);
    }

    public function actionV($c, $u, $p, $b = null) {
        $this->layout = '//url-publica/main';
        $model = $this->findModel($c);

        if (!$model) {
            return $this->render("_404");
        }

        if (!$this->validateUser($u, $p)) {
            return $this->render("_403");
        }

        $argumentos = RelatorioDataItem::find()->andWhere(['id_relatorio_data'=> $model->id, 'is_ativo' => 1, 'is_excluido' => 0, 'parametro' => 'argumento'])
            ->orderBy('ordem ASC')->all();

        $valores = RelatorioDataItem::find()->andWhere(['id_relatorio_data'=> $model->id, 'is_ativo' => 1, 'is_excluido' => 0, 'parametro' => 'valor'])
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

        $campos = $data;
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

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('_view-relatorio', [
            'model' => $model,
            'usuario_id' => $u,
            'color' => $b,
            'p' => $p,
            'c' => $c,
            'campos' => $campos,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    protected function findModel($id) {
        if (($model = RelatorioData::find()->andWhere([
                'id' => $id,
                'is_ativo' => TRUE,
                'is_excluido' => FALSE
            ])->one()) !== null) {
            return $model;
        }

        return null;
    }
}
